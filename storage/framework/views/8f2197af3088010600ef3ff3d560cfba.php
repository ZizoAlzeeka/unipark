<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Welcome back, ' . auth()->user()->name); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">

    <!-- Stats Grid -->
    <div class="grid grid-4 mb-24">
        <div class="stat-card primary animate-fade-up stagger-1">
            <div class="stat-icon primary"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($totalReservations); ?></div>
                <div class="stat-label">Total Reservations</div>
            </div>
        </div>

        <div class="stat-card success animate-fade-up stagger-2">
            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($completedReservations); ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <div class="stat-card secondary animate-fade-up stagger-3">
            <div class="stat-icon secondary"><i class="fas fa-parking"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($availableSpots); ?></div>
                <div class="stat-label">Available Spots</div>
            </div>
        </div>

        <div class="stat-card info animate-fade-up stagger-4">
            <div class="stat-icon info"><i class="fas fa-map-marked-alt"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($totalSpots); ?></div>
                <div class="stat-label">Total Spots</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-24 animate-fade-up stagger-2">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <div>
                <h3 style="font-size:17px; font-weight:700;">Quick Actions</h3>
                <p style="font-size:13px; color:var(--text-muted); margin-top:2px;">What would you like to do today?</p>
            </div>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:16px;">
            <a href="<?php echo e(route('reservations.create')); ?>" style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:24px;background:linear-gradient(135deg,rgba(108,99,255,0.06),rgba(79,172,254,0.06));border:2px solid rgba(108,99,255,0.12);border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;background:var(--grad-primary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;box-shadow:0 4px 15px rgba(108,99,255,0.4);">
                    <i class="fas fa-plus"></i>
                </div>
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">Book a Spot</span>
            </a>

            <a href="<?php echo e(route('parking-map')); ?>" style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:24px;background:linear-gradient(135deg,rgba(0,180,216,0.06),rgba(6,214,160,0.06));border:2px solid rgba(0,180,216,0.12);border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;background:var(--grad-secondary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;box-shadow:0 4px 15px rgba(0,180,216,0.4);">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">View Map</span>
            </a>

            <a href="<?php echo e(route('reservations.index')); ?>" style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:24px;background:linear-gradient(135deg,rgba(6,214,160,0.06),rgba(67,233,123,0.06));border:2px solid rgba(6,214,160,0.12);border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;background:var(--grad-success);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;box-shadow:0 4px 15px rgba(6,214,160,0.4);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">My Bookings</span>
            </a>

            <a href="<?php echo e(route('notifications.index')); ?>" style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:24px;background:linear-gradient(135deg,rgba(255,183,3,0.06),rgba(253,160,133,0.06));border:2px solid rgba(255,183,3,0.12);border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;background:var(--grad-warning);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;box-shadow:0 4px 15px rgba(255,183,3,0.4);">
                    <i class="fas fa-bell"></i>
                </div>
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">Notifications</span>
            </a>

            <a href="<?php echo e(route('reservations.history')); ?>" style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:24px;background:linear-gradient(135deg,rgba(76,201,240,0.06),rgba(123,47,190,0.06));border:2px solid rgba(76,201,240,0.12);border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;background:var(--grad-info);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;box-shadow:0 4px 15px rgba(76,201,240,0.4);">
                    <i class="fas fa-history"></i>
                </div>
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">View History</span>
            </a>

            <a href="<?php echo e(route('profile')); ?>" style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:24px;background:linear-gradient(135deg,rgba(240,147,251,0.06),rgba(245,87,108,0.06));border:2px solid rgba(240,147,251,0.12);border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width:52px;height:52px;background:var(--grad-accent);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;box-shadow:0 4px 15px rgba(240,147,251,0.4);">
                    <i class="fas fa-user-cog"></i>
                </div>
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">My Profile</span>
            </a>
        </div>
    </div>

    <div class="grid grid-2" style="gap:24px;">
        <!-- Active Reservations -->
        <div class="card animate-fade-up stagger-3">
            <div class="card-header">
                <div>
                    <div class="card-title">Active Reservations</div>
                    <p style="font-size:13px; color:var(--text-muted); margin-top:2px;">Your current bookings</p>
                </div>
                <a href="<?php echo e(route('reservations.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New
                </a>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $activeReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="background:linear-gradient(135deg,rgba(6,214,160,0.04),rgba(67,233,123,0.04));border:1px solid rgba(6,214,160,0.15);border-radius:var(--radius-md);padding:16px;margin-bottom:12px;" class="reservation-card active-card">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                    <div>
                        <div class="reservation-spot"><?php echo e($res->spot->zone->code); ?>-<?php echo e($res->spot->spot_number); ?></div>
                        <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">
                            <?php echo e($res->spot->zone->name); ?>

                        </div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:6px;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-clock" style="color:var(--primary);font-size:11px;"></i>
                            <?php echo e($res->start_time->format('M d, H:i')); ?> — <?php echo e($res->end_time->format('H:i')); ?>

                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                        <span class="badge badge-success">Active</span>
                        <?php if($res->remaining_time): ?>
                        <span style="font-size:11px;font-weight:700;color:var(--success);"><?php echo e($res->remaining_time); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:8px;margin-top:12px;padding-top:12px;border-top:1px solid rgba(6,214,160,0.1);">
                    <span style="font-size:12px;color:var(--text-muted);">
                        <i class="fas fa-car" style="margin-right:4px;"></i><?php echo e($res->vehicle_plate ?? 'No plate'); ?>

                    </span>
                    <span style="margin-left:auto;display:flex;gap:8px;">
                        <a href="<?php echo e(route('reservations.show', $res->id)); ?>" class="btn btn-ghost btn-sm" style="font-size:12px;padding:4px 12px;">Details</a>
                        <?php if($res->canBeCancelled()): ?>
                        <form action="<?php echo e(route('reservations.cancel', $res->id)); ?>" method="POST" onsubmit="return confirm('Cancel this reservation?')">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm" style="font-size:12px;padding:4px 12px;background:rgba(239,71,111,0.08);color:var(--danger);border-radius:var(--radius-full);">Cancel</button>
                        </form>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:40px;color:var(--text-muted);">
                <i class="fas fa-calendar-times" style="font-size:40px;margin-bottom:16px;display:block;opacity:0.3;"></i>
                <p style="font-weight:600;">No active reservations</p>
                <a href="<?php echo e(route('reservations.create')); ?>" class="btn btn-primary btn-sm mt-12">Book a Spot Now</a>
            </div>
            <?php endif; ?>

            <?php if($activeReservations->count() > 0): ?>
            <a href="<?php echo e(route('reservations.index')); ?>" style="display:block;text-align:center;font-size:13px;font-weight:600;color:var(--primary);margin-top:8px;">View All Reservations →</a>
            <?php endif; ?>
        </div>

        <!-- Parking Zones Overview -->
        <div class="card animate-fade-up stagger-4">
            <div class="card-header">
                <div>
                    <div class="card-title">Parking Zones</div>
                    <p style="font-size:13px; color:var(--text-muted); margin-top:2px;">Live availability overview</p>
                </div>
                <a href="<?php echo e(route('parking-map')); ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-map-marked-alt"></i> View Map
                </a>
            </div>

            <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $total = $zone->spots->count();
                $available = $zone->spots->where('status','available')->count();
                $occupied = $zone->spots->where('status','occupied')->count();
                $reserved = $zone->spots->where('status','reserved')->count();
                $rate = $total > 0 ? round((($occupied + $reserved) / $total) * 100) : 0;
                $color = $rate < 50 ? 'success' : ($rate < 80 ? 'warning' : 'danger');
            ?>
            <div style="padding:16px;background:rgba(108,99,255,0.02);border:1px solid rgba(108,99,255,0.06);border-radius:var(--radius-md);margin-bottom:12px;transition:var(--transition);" onmouseover="this.style.background='rgba(108,99,255,0.05)'" onmouseout="this.style.background='rgba(108,99,255,0.02)'">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                    <div>
                        <div style="font-weight:700;font-size:14px;"><?php echo e($zone->name); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?php echo e($total); ?> total spots</div>
                    </div>
                    <div style="display:flex;gap:12px;font-size:12px;">
                        <span style="color:var(--success);font-weight:700;"><?php echo e($available); ?> free</span>
                        <span style="color:var(--warning);font-weight:700;"><?php echo e($reserved); ?> reserved</span>
                        <span style="color:var(--danger);font-weight:700;"><?php echo e($occupied); ?> occupied</span>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar <?php echo e($color); ?>" style="width:<?php echo e($rate); ?>%;"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:11px;color:var(--text-muted);">
                    <span><?php echo e($rate); ?>% occupied</span>
                    <a href="<?php echo e(route('parking-map')); ?>" style="color:var(--primary);font-weight:600;">Book →</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Recent Notifications -->
    <?php if($recentNotifications->count() > 0): ?>
    <div class="card mt-24 animate-fade-up" style="animation-delay:0.5s;">
        <div class="card-header">
            <div>
                <div class="card-title">Recent Notifications</div>
                <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Stay up to date</p>
            </div>
            <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-ghost btn-sm">View All</a>
        </div>

        <div style="display:flex;flex-direction:column;gap:8px;">
            <?php $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;align-items:flex-start;gap:14px;padding:14px;border-radius:var(--radius-md);<?php echo e(!$notif->is_read ? 'background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);' : 'border:1px solid var(--border-color);'); ?>;transition:var(--transition);" onmouseover="this.style.background='rgba(108,99,255,0.04)'" onmouseout="this.style.background='<?php echo e(!$notif->is_read ? 'rgba(108,99,255,0.04)' : 'transparent'); ?>'">
                <div class="notif-item-icon <?php echo e($notif->type); ?>" style="width:38px;height:38px;border-radius:10px;">
                    <i class="fas fa-<?php echo e($notif->type_icon); ?>"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);"><?php echo e($notif->title); ?></div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($notif->message); ?></div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:5px;font-weight:500;"><?php echo e($notif->created_at->diffForHumans()); ?></div>
                </div>
                <?php if(!$notif->is_read): ?>
                <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:4px;"></div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/user/dashboard.blade.php ENDPATH**/ ?>