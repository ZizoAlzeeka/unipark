<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email — UniPark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/unipark.css') }}">
    <link rel="stylesheet" href="{{ asset('css/creative-design.css') }}">
    <style>
        .otp-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .otp-inputs input {
            width: 52px;
            height: 60px;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 800;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.2);
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .otp-inputs input:focus {
            border-color: #4F46E5;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }

        .otp-inputs input.filled {
            border-color: #4F46E5;
            background: rgba(79, 70, 229, 0.1);
        }
        
        @media (max-width: 400px) {
            .otp-inputs input { width: 44px; height: 50px; }
        }
    </style>
</head>
<body class="creative-layout">

    <div class="animated-bg"></div>
    <div class="orb orb-3"></div>
    <div class="orb orb-1"></div>

    <div class="auth-wrapper">
        <div class="glass-panel auth-card" style="max-width: 540px; padding: 3rem;">
            
            <div style="text-align: center; margin-bottom: 30px;">
                <div class="logo-icon-creative" style="margin: 0 auto 20px; width: 60px; height: 60px; font-size: 28px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">Verify Your Email</h1>
                <p style="color: rgba(255,255,255,0.7); max-width: 90%; margin: 0 auto; line-height: 1.6;">
                    We've sent a 6-digit verification code to your email inbox. Enter it below to activate your account.
                </p>
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

            @if (session('success'))
                <div style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #6ee7b7; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div style="background: rgba(245, 158, 11, 0.1); border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #fcd34d; font-size: 14px;">
                    {{ session('warning') }}
                </div>
            @endif

            <form action="{{ route('verification.verify') }}" method="POST" id="verifyForm">
                @csrf

                <!-- Hidden input that receives the assembled code -->
                <input type="hidden" name="code" id="codeHidden">

                <div class="otp-inputs" id="otpInputs">
                    <input type="text" maxlength="1" class="otp-digit" data-index="0" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code">
                    <input type="text" maxlength="1" class="otp-digit" data-index="1" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="2" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="3" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="4" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="5" inputmode="numeric" pattern="[0-9]">
                </div>

                <button type="submit" class="btn-creative btn-creative-primary" style="width: 100%; justify-content: center; font-size: 16px; margin-top: 10px;" id="verifyBtn">
                    <i class="fas fa-check-circle"></i> Verify Account
                </button>
            </form>

            <div style="text-align: center; margin-top: 30px; font-size: 14px; color: rgba(255,255,255,0.6);">
                Didn't receive the code?
                <form action="{{ route('verification.resend') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color: #fff; font-weight: 700; cursor: pointer; text-decoration: none; border-bottom: 2px solid #0EA5E9; padding-bottom: 2px; font-family: 'Outfit', sans-serif;">
                        Resend Code
                    </button>
                </form>
            </div>
            
            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ route('login') }}" style="font-size: 14px; color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s;">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </div>

        </div>
    </div>

    <script>
        (function () {
            const digits = document.querySelectorAll('.otp-digit');
            const hidden = document.getElementById('codeHidden');
            const form = document.getElementById('verifyForm');

            digits.forEach(function (el, i) {
                el.addEventListener('input', function () {
                    el.value = el.value.replace(/\D/g, '').slice(-1);
                    el.classList.toggle('filled', el.value !== '');
                    if (el.value && i < digits.length - 1) digits[i + 1].focus();
                    assembleCode();
                });
                el.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !el.value && i > 0) digits[i - 1].focus();
                });
                el.addEventListener('paste', function (e) {
                    e.preventDefault();
                    var pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                    pasted.split('').forEach(function (ch, j) {
                        if (digits[j]) { digits[j].value = ch; digits[j].classList.add('filled'); }
                    });
                    if (digits[Math.min(pasted.length, digits.length - 1)]) {
                        digits[Math.min(pasted.length, digits.length - 1)].focus();
                    }
                    assembleCode();
                });
            });

            function assembleCode() {
                hidden.value = Array.from(digits).map(function (d) { return d.value; }).join('');
            }

            form.addEventListener('submit', function (e) {
                assembleCode();
                if (hidden.value.length < 6) {
                    e.preventDefault();
                    digits[0].focus();
                }
            });

            // Auto-focus first digit
            if (digits[0]) digits[0].focus();
        })();
    </script>
</body>
</html>
