<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — UniPark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Base styles -->
    <link rel="stylesheet" href="<?php echo e(asset('css/unipark.css')); ?>">
    <!-- New Creative UI Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('css/creative-design.css')); ?>">
</head>
<body class="creative-layout">

    <div class="animated-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-3"></div>

    <div class="auth-wrapper">
        <div class="glass-panel auth-card auth-card-wide">
            
            <!-- Left Image Side -->
            <div class="auth-image-side">
                <div style="position:relative; z-index:10;">
                    <a href="<?php echo e(route('home')); ?>" class="logo-creative" style="margin-bottom: 40px; display:inline-flex;">
                        <div class="logo-icon-creative">
                            <i class="fas fa-parking"></i>
                        </div>
                        UniPark
                    </a>
                    
                    <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 16px; line-height: 1.2;">
                        Welcome back to <br><span class="text-grad-1">Smart Parking.</span>
                    </h2>
                    <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem; margin-bottom: 30px; max-width: 80%;">
                        Access your dashboard, manage your reservations, and view real-time campus parking availability.
                    </p>
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px; font-weight: 600; color: rgba(255,255,255,0.9);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #4F46E5;">
                                <i class="fas fa-bolt"></i>
                            </div>
                            Lightning fast reservations
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; font-weight: 600; color: rgba(255,255,255,0.9);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #FF3366;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            Secure campus integration
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Side -->
            <div class="auth-form-side">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">Sign In</h1>
                    <p style="color: rgba(255,255,255,0.6);">Enter your credentials to access your account</p>
                </div>

                <?php if($errors->any()): ?>
                    <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #fca5a5; font-size: 14px;">
                        <div style="font-weight: 700; margin-bottom: 4px;">Please fix the following:</div>
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #6ee7b7; font-size: 14px;">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('login')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="creative-input-group">
                        <label class="creative-label">Email Address</label>
                        <i class="fas fa-envelope input-icon-creative"></i>
                        <input type="email" name="email" class="creative-input" placeholder="student@uoh.edu.sa" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>

                    <div class="creative-input-group">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label class="creative-label">Password</label>
                            <a href="#" style="font-size: 13px; color: #4F46E5; font-weight: 600; text-decoration: none;">Forgot password?</a>
                        </div>
                        <i class="fas fa-lock input-icon-creative"></i>
                        <input type="password" name="password" id="passwordInput" class="creative-input" placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword()" style="position: absolute; right: 16px; top: 40px; background: none; border: none; color: rgba(255,255,255,0.5); cursor: pointer;">
                            <i class="fas fa-eye" id="pwIcon"></i>
                        </button>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 30px;">
                        <input type="checkbox" name="remember" id="remember" style="accent-color: #4F46E5; width: 16px; height: 16px; cursor: pointer;">
                        <label for="remember" style="font-size: 14px; color: rgba(255,255,255,0.7); cursor: pointer;">Remember me for 30 days</label>
                    </div>

                    <button type="submit" class="btn-creative btn-creative-primary" style="width: 100%; justify-content: center; font-size: 16px;">
                        Sign In <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div style="text-align: center; margin-top: 30px; font-size: 14px; color: rgba(255,255,255,0.6);">
                    Don't have an account? 
                    <a href="<?php echo e(route('register')); ?>" style="color: #fff; font-weight: 700; text-decoration: none; border-bottom: 2px solid #FF3366; padding-bottom: 2px;">Create account</a>
                </div>
                
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); text-align: center;">
                    <p style="font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Demo Access</p>
                    <div style="display: flex; justify-content: center; gap: 16px; font-size: 12px; color: rgba(255,255,255,0.7);">
                        <span><strong style="color:#fff;">Admin:</strong> admin@uoh.edu.sa</span>
                        <span><strong style="color:#fff;">Student:</strong> student1@uoh.edu.sa</span>
                    </div>
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
<?php /**PATH H:\MAMP\htdocs\unipark\resources\views/auth/login.blade.php ENDPATH**/ ?>