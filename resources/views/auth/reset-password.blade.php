<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — UniPark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/unipark.css') }}">
</head>
<body style="background:var(--bg-main); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px;">

<div style="width:100%; max-width:460px;">
    <div class="card" style="border-radius:var(--radius-xl); box-shadow:var(--shadow-xl); padding:40px;">
        <div style="text-align:center; margin-bottom:32px;">
            <div style="width:60px;height:60px;background:var(--grad-primary);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:24px;font-weight:800;color:#fff;">U</div>
            <h1 style="font-size:26px; font-weight:800; margin-bottom:8px;">Set New Password</h1>
            <p style="color:var(--text-muted); font-size:15px;">Create a strong password for your account</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly style="background:rgba(108,99,255,0.03);">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg">
                <i class="fas fa-save"></i>
                Reset Password
            </button>
        </form>

        <div style="text-align:center; margin-top:20px;">
            <a href="{{ route('login') }}" style="font-size:14px; color:var(--text-muted);">Back to Sign In</a>
        </div>
    </div>
</div>

</body>
</html>
