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
            <a href="{{ route('home') }}" style="text-decoration:none;">
                <div style="width:60px;height:60px;background:var(--grad-primary);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;font-weight:800;color:#fff;box-shadow:0 6px 20px rgba(108,99,255,0.4);">U</div>
            </a>
            <div style="width:70px;height:70px;background:rgba(255,183,3,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <i class="fas fa-key" style="font-size:28px; color:var(--warning);"></i>
            </div>
            <h1 style="font-size:26px; font-weight:800; margin-bottom:8px;">Forgot Password?</h1>
            <p style="color:var(--text-muted); font-size:15px; line-height:1.6;">No worries! Enter your university email and we'll send you a reset link.</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">University Email Address</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="your.id@uoh.edu.sa" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg">
                <i class="fas fa-paper-plane"></i>
                Send Reset Link
            </button>
        </form>

        <div style="text-align:center; margin-top:24px; display:flex; flex-direction:column; gap:8px;">
            <a href="{{ route('login') }}" style="font-size:14px; font-weight:600; color:var(--primary); display:flex; align-items:center; justify-content:center; gap:6px;">
                <i class="fas fa-arrow-left"></i> Back to Sign In
            </a>
            <a href="{{ route('home') }}" style="font-size:13px; color:var(--text-muted);">
                Return to Homepage
            </a>
        </div>
    </div>
</div>

</body>
</html>
