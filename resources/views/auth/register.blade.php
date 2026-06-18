<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — UniPark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Base styles -->
    <link rel="stylesheet" href="{{ asset('css/unipark.css') }}">
    <!-- New Creative UI Styles -->
    <link rel="stylesheet" href="{{ asset('css/creative-design.css') }}">
</head>
<body class="creative-layout">

    <div class="animated-bg"></div>
    <div class="orb orb-2"></div>

    <div class="auth-wrapper">
        <div class="glass-panel auth-card" style="max-width: 600px; padding: 2.5rem;">
            
            <div style="text-align: center; margin-bottom: 30px;">
                <a href="{{ route('home') }}" class="logo-creative" style="justify-content: center; margin-bottom: 20px;">
                    <div class="logo-icon-creative">
                        <i class="fas fa-parking"></i>
                    </div>
                </a>
                <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">Join <span class="text-grad-2">UniPark</span></h1>
                <p style="color: rgba(255,255,255,0.6);">Create your free account to access smart campus parking</p>
            </div>

            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #fca5a5; font-size: 14px;">
                    <div style="font-weight: 700; margin-bottom: 4px;">Please fix the following:</div>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="creative-input-group">
                    <label class="creative-label">Full Name <span style="color:#ef4444;">*</span></label>
                    <i class="fas fa-user input-icon-creative"></i>
                    <input type="text" name="name" class="creative-input" placeholder="Your full name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="creative-input-group">
                    <label class="creative-label">University Email <span style="color:#ef4444;">*</span></label>
                    <i class="fas fa-envelope input-icon-creative"></i>
                    <input type="email" name="email" class="creative-input" placeholder="student@uoh.edu.sa" value="{{ old('email') }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="creative-input-group">
                        <label class="creative-label">Role <span style="color:#ef4444;">*</span></label>
                        <i class="fas fa-user-tag input-icon-creative" style="z-index: 1;"></i>
                        <select name="role" class="creative-input" required style="appearance: none; cursor: pointer; padding-left: 44px;">
                            <option value="" style="color: #000;">Select role...</option>
                            <option value="student" style="color: #000;" {{ old('role') == 'student' ? 'selected' : '' }}>🎓 Student</option>
                            <option value="staff" style="color: #000;" {{ old('role') == 'staff' ? 'selected' : '' }}>👨‍💼 Staff</option>
                        </select>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 16px; top: 40px; color: rgba(255,255,255,0.5); pointer-events: none;"></i>
                    </div>

                    <div class="creative-input-group">
                        <label class="creative-label">Vehicle Plate</label>
                        <i class="fas fa-car input-icon-creative"></i>
                        <input type="text" name="vehicle_plate" class="creative-input" placeholder="e.g. ABC-1234" value="{{ old('vehicle_plate') }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="creative-input-group">
                        <label class="creative-label">Password <span style="color:#ef4444;">*</span></label>
                        <i class="fas fa-lock input-icon-creative"></i>
                        <input type="password" name="password" id="pw1" class="creative-input" placeholder="Min 8 chars" required>
                        <button type="button" onclick="togglePw('pw1','icon1')" style="position: absolute; right: 16px; top: 40px; background: none; border: none; color: rgba(255,255,255,0.5); cursor: pointer;">
                            <i class="fas fa-eye" id="icon1"></i>
                        </button>
                    </div>

                    <div class="creative-input-group">
                        <label class="creative-label">Confirm Password <span style="color:#ef4444;">*</span></label>
                        <i class="fas fa-lock input-icon-creative"></i>
                        <input type="password" name="password_confirmation" id="pw2" class="creative-input" placeholder="Repeat password" required>
                        <button type="button" onclick="togglePw('pw2','icon2')" style="position: absolute; right: 16px; top: 40px; background: none; border: none; color: rgba(255,255,255,0.5); cursor: pointer;">
                            <i class="fas fa-eye" id="icon2"></i>
                        </button>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 30px; margin-top: 10px;">
                    <input type="checkbox" id="terms" required style="margin-top: 4px; accent-color: #4F46E5; width: 16px; height: 16px; cursor: pointer; flex-shrink: 0;">
                    <label for="terms" style="font-size: 13px; color: rgba(255,255,255,0.7); cursor: pointer; line-height: 1.5;">
                        I agree to the <a href="#" style="color: #fff; font-weight: 700; text-decoration: none;">Terms of Service</a> and 
                        <a href="#" style="color: #fff; font-weight: 700; text-decoration: none;">Privacy Policy</a>.
                    </label>
                </div>

                <button type="submit" class="btn-creative btn-creative-primary" style="width: 100%; justify-content: center; font-size: 16px;">
                    Create My Account <i class="fas fa-user-plus" style="margin-left: 8px;"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: 30px; font-size: 14px; color: rgba(255,255,255,0.6);">
                Already have an account? 
                <a href="{{ route('login') }}" style="color: #fff; font-weight: 700; text-decoration: none; border-bottom: 2px solid #0EA5E9; padding-bottom: 2px;">Sign in</a>
            </div>
            
        </div>
    </div>

    <script>
    function togglePw(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    </script>
</body>
</html>
