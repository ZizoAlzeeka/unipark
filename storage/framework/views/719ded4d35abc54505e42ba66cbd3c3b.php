<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — UniPark</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/unipark.css')); ?>">
</head>
<body>
<div class="auth-page">

    <!-- ── Left Panel ── -->
    <div class="auth-left">
        <div class="landing-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-5"></div>
        </div>
        <div class="auth-left-content">
            <div class="auth-illustration">
                <i class="fas fa-parking" style="color:#fff;"></i>
            </div>
            <h1>Welcome Back!</h1>
            <p>Sign in to access your smart campus parking dashboard. Reserve spots, track your history, and more.</p>

            <div style="display:grid;gap:14px;margin-top:36px;text-align:left;">
                <div class="auth-feature-pill">
                    <div class="auth-feature-pill-icon">🗺️</div>
                    <div>
                        <div class="auth-feature-pill-text">Real-Time Parking Map</div>
                        <div class="auth-feature-pill-sub">See available spots instantly</div>
                    </div>
                </div>
                <div class="auth-feature-pill">
                    <div class="auth-feature-pill-icon">📅</div>
                    <div>
                        <div class="auth-feature-pill-text">Advance Reservations</div>
                        <div class="auth-feature-pill-sub">Book your spot before you arrive</div>
                    </div>
                </div>
                <div class="auth-feature-pill">
                    <div class="auth-feature-pill-icon">🔔</div>
                    <div>
                        <div class="auth-feature-pill-text">Smart Notifications</div>
                        <div class="auth-feature-pill-sub">Stay updated in real-time</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Right Panel ── -->
    <div class="auth-right">
        <div class="auth-form-container">

            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <i class="fas fa-parking"></i>
                </div>
                <div class="auth-logo-text">
                    <h2>UniPark</h2>
                    <span>University of Ha'il</span>
                </div>
            </div>

            <h1 class="auth-title">Sign in to your account</h1>
            <p class="auth-subtitle">Enter your university email and password to access the dashboard.</p>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="alert-content">
                        <div class="alert-title">Please fix the following:</div>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="alert-content"><?php echo e(session('success')); ?></div>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="you@uoh.edu.sa" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><i class="fas fa-times-circle"></i> <?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Password <span class="required">*</span>
                        <a href="#" style="float:right;font-size:12px;color:var(--primary);font-weight:600;">Forgot password?</a>
                    </label>
                    <div class="input-group" style="position:relative;">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="passwordInput" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Your password" required style="padding-right:44px;">
                        <button type="button" onclick="togglePassword()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:15px;" id="pwToggle">
                            <i class="fas fa-eye" id="pwIcon"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><i class="fas fa-times-circle"></i> <?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;">
                    <label class="toggle-switch" style="flex-shrink:0;">
                        <input type="checkbox" name="remember">
                        <span class="toggle-slider"></span>
                    </label>
                    <span style="font-size:14px;color:var(--text-secondary);font-weight:600;">Remember me for 30 days</span>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content:center;">
                    <i class="fas fa-sign-in-alt"></i> Sign In to UniPark
                </button>
            </form>

            <div class="auth-divider">or</div>

            <div style="background:rgba(79,70,229,0.04);border-radius:var(--radius-md);padding:18px;border:1px solid rgba(79,70,229,0.1);">
                <div style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;color:var(--text-muted);margin-bottom:12px;">Demo Accounts</div>
                <div style="display:grid;gap:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="font-weight:700;color:var(--primary);"><i class="fas fa-user-shield fa-fw"></i> Admin</span>
                        <span style="color:var(--text-muted);font-family:monospace;">admin@uoh.edu.sa</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="font-weight:700;color:var(--secondary);"><i class="fas fa-chalkboard-teacher fa-fw"></i> Staff</span>
                        <span style="color:var(--text-muted);font-family:monospace;">staff@uoh.edu.sa</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="font-weight:700;color:var(--success);"><i class="fas fa-user-graduate fa-fw"></i> Student</span>
                        <span style="color:var(--text-muted);font-family:monospace;">student1@uoh.edu.sa</span>
                    </div>
                    <div style="border-top:1px solid rgba(79,70,229,0.08);margin-top:4px;padding-top:8px;font-size:12px;color:var(--text-muted);text-align:center;">
                        All passwords: <strong style="color:var(--primary);">password</strong>
                    </div>
                </div>
            </div>

            <div class="auth-footer-link">
                Don't have an account?
                <a href="<?php echo e(route('register')); ?>">Create account &rarr;</a>
            </div>

            <div style="text-align:center;margin-top:16px;">
                <a href="<?php echo e(route('home')); ?>" style="font-size:13px;color:var(--text-muted);">
                    <i class="fas fa-arrow-left"></i> Back to Homepage
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('pwIcon');
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
<?php /**PATH E:\xamp\htdocs\unipark\resources\views/auth/login.blade.php ENDPATH**/ ?>