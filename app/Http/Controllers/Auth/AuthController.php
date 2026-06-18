<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Mail\VerifyEmailMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->email_verified_at) {
                Auth::logout();
                session()->put('verify_user_id', $user->id);
                return redirect()->route('verification.notice')->with('warning', 'Please verify your email address to login.');
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.'])->withInput();
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
            }
            return redirect()->route('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput($request->except('password'));
    }

    public function showRegister()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,staff',
            'university_id' => 'nullable|string|max:50|unique:users',
            'phone' => 'nullable|string|max:20',
            'vehicle_plate' => 'nullable|string|max:20',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_color' => 'nullable|string|max:50',
        ]);

        $verificationCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'university_id' => $request->university_id,
            'phone' => $request->phone,
            'vehicle_plate' => strtoupper($request->vehicle_plate),
            'vehicle_model' => $request->vehicle_model,
            'vehicle_color' => $request->vehicle_color,
            'verification_code' => $verificationCode,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Welcome to UniPark!',
            'message' => 'Your account has been successfully created. You can now book parking spots on campus.',
            'type' => 'success',
            'icon' => 'check-circle',
        ]);

        session()->put('verify_user_id', $user->id);

        $emailSent = false;
        try {
            Mail::to($user->email)->send(new VerifyEmailMail($user, $verificationCode));
            $emailSent = true;
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        $message = $emailSent
            ? 'Account created successfully! Please check your email for the verification code.'
            : 'Account created! Email could not be sent — please use Resend Code on the next page.';

        return redirect()->route('verification.notice')->with('success', $message);
    }

    public function showVerifyEmail()
    {
        if (!session()->has('verify_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session()->get('verify_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->verification_code === $request->code) {
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->save();

            session()->forget('verify_user_id');
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Email verified successfully! Welcome to UniPark.');
        }

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    public function resendVerifyEmail(Request $request)
    {
        $userId = session()->get('verify_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if ($user) {
            $verificationCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $user->verification_code = $verificationCode;
            $user->save();

            Mail::to($user->email)->send(new VerifyEmailMail($user, $verificationCode));
            return back()->with('success', 'A new verification code has been sent to your email.');
        }

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link has been sent to your email.');
        }

        return back()->withErrors(['email' => 'Unable to send reset link. Please try again.']);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password has been reset successfully. Please log in.');
        }

        return back()->withErrors(['email' => 'Invalid or expired reset token.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
