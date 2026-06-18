

<?php $__env->startSection('title', 'My Profile'); ?>
<?php $__env->startSection('page-title', 'My Profile'); ?>
<?php $__env->startSection('page-subtitle', 'Manage your account information and preferences'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="grid grid-2" style="gap:28px;align-items:start;">
        <!-- Profile Overview -->
        <div>
            <div class="card mb-20">
                <div style="text-align:center;padding:20px 0 8px;">
                    <div style="position:relative;display:inline-block;">
                        <img src="<?php echo e(auth()->user()->avatar_url); ?>" alt="<?php echo e(auth()->user()->name); ?>"
                             style="width:100px;height:100px;border-radius:50%;border:4px solid var(--primary);box-shadow:0 8px 30px rgba(108,99,255,0.3);object-fit:cover;">
                        <div style="position:absolute;bottom:4px;right:4px;width:24px;height:24px;border-radius:50%;background:var(--success);border:2px solid #fff;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-check" style="font-size:10px;color:#fff;"></i>
                        </div>
                    </div>
                    <h2 style="font-size:22px;font-weight:800;margin-top:16px;"><?php echo e(auth()->user()->name); ?></h2>
                    <p style="color:var(--text-muted);font-size:14px;"><?php echo e(auth()->user()->email); ?></p>

                    <div style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:6px 16px;border-radius:var(--radius-full);<?php echo e(auth()->user()->role === 'admin' ? 'background:rgba(239,71,111,0.1);color:var(--danger)' : (auth()->user()->role === 'staff' ? 'background:rgba(0,180,216,0.1);color:var(--secondary)' : 'background:rgba(108,99,255,0.1);color:var(--primary)')); ?>;">
                        <i class="fas <?php echo e(auth()->user()->role === 'admin' ? 'fa-shield-alt' : (auth()->user()->role === 'staff' ? 'fa-chalkboard-teacher' : 'fa-user-graduate')); ?>"></i>
                        <span style="font-size:13px;font-weight:700;"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                    </div>
                </div>

                <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div style="text-align:center;padding:16px;background:rgba(108,99,255,0.04);border-radius:var(--radius-md);">
                            <div style="font-size:24px;font-weight:800;color:var(--primary);"><?php echo e($reservationStats['total']); ?></div>
                            <div style="font-size:12px;color:var(--text-muted);font-weight:600;">Total Bookings</div>
                        </div>
                        <div style="text-align:center;padding:16px;background:rgba(6,214,160,0.04);border-radius:var(--radius-md);">
                            <div style="font-size:24px;font-weight:800;color:var(--success);"><?php echo e($reservationStats['completed']); ?></div>
                            <div style="font-size:12px;color:var(--text-muted);font-weight:600;">Completed</div>
                        </div>
                    </div>
                </div>

                <div style="margin-top:16px;display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <span style="font-size:13px;color:var(--text-muted);">University ID</span>
                        <span style="font-size:13px;font-weight:700;"><?php echo e(auth()->user()->university_id ?? '—'); ?></span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <span style="font-size:13px;color:var(--text-muted);">Phone</span>
                        <span style="font-size:13px;font-weight:700;"><?php echo e(auth()->user()->phone ?? '—'); ?></span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <span style="font-size:13px;color:var(--text-muted);">Vehicle Plate</span>
                        <span style="font-size:13px;font-weight:700;"><?php echo e(auth()->user()->vehicle_plate ?? '—'); ?></span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <span style="font-size:13px;color:var(--text-muted);">Member Since</span>
                        <span style="font-size:13px;font-weight:700;"><?php echo e(auth()->user()->created_at->format('M Y')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Forms -->
        <div>
            <!-- Profile Form -->
            <div class="card mb-20">
                <div class="card-header">
                    <div>
                        <div class="card-title">Personal Information</div>
                        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Update your profile details</p>
                    </div>
                    <i class="fas fa-user-edit" style="color:var(--primary);font-size:20px;"></i>
                </div>

                <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', auth()->user()->name)); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">University Email</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" value="<?php echo e(auth()->user()->email); ?>" readonly style="background:rgba(108,99,255,0.03);cursor:not-allowed;">
                        </div>
                        <p class="form-hint">University email cannot be changed</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-phone"></i></span>
                            <input type="tel" name="phone" class="form-control" value="<?php echo e(old('phone', auth()->user()->phone)); ?>" placeholder="+966-17-000-0000">
                        </div>
                    </div>

                    <div style="border-top:1px solid var(--border-color);margin:20px 0;padding-top:20px;">
                        <p style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:16px;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-car"></i> Vehicle Information
                        </p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">License Plate</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-id-card"></i></span>
                                    <input type="text" name="vehicle_plate" class="form-control" value="<?php echo e(old('vehicle_plate', auth()->user()->vehicle_plate)); ?>" placeholder="ABC 123" style="text-transform:uppercase;">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Vehicle Model</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-car"></i></span>
                                    <input type="text" name="vehicle_model" class="form-control" value="<?php echo e(old('vehicle_model', auth()->user()->vehicle_model)); ?>" placeholder="Toyota Camry">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-12" style="margin-bottom:0;">
                            <label class="form-label">Vehicle Color</label>
                            <div class="input-group">
                                <span class="input-icon"><i class="fas fa-palette"></i></span>
                                <input type="text" name="vehicle_color" class="form-control" value="<?php echo e(old('vehicle_color', auth()->user()->vehicle_color)); ?>" placeholder="White, Black, Silver...">
                            </div>
                        </div>
                    </div>

                    <div class="mt-20">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Form -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Change Password</div>
                        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Keep your account secure</p>
                    </div>
                    <i class="fas fa-lock" style="color:var(--danger);font-size:20px;"></i>
                </div>

                <form action="<?php echo e(route('profile.password')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
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

                    <button type="submit" class="btn btn-danger w-full">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/user/profile.blade.php ENDPATH**/ ?>