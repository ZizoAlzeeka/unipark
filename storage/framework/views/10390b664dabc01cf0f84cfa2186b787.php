<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'System overview and real-time statistics'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">

    <!-- Top Stats -->
    <div class="grid grid-4 mb-24">
        <div class="stat-card primary animate-fade-up stagger-1">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e(number_format($stats['total_users'])); ?></div>
                <div class="stat-label">Total Users</div>
                <div class="stat-change" style="color:var(--text-muted);">System registered</div>
            </div>
        </div>
        <div class="stat-card secondary animate-fade-up stagger-2">
            <div class="stat-icon secondary"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e(number_format($stats['active_reservations'])); ?></div>
                <div class="stat-label">Active Reservations</div>
                <div class="stat-change up"><i class="fas fa-clock"></i> <?php echo e($stats['total_reservations_today']); ?> today</div>
            </div>
        </div>
        <div class="stat-card success animate-fade-up stagger-3">
            <div class="stat-icon success"><i class="fas fa-parking"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['available_spots']); ?></div>
                <div class="stat-label">Available Spots</div>
                <div class="stat-change" style="color:var(--text-muted);">of <?php echo e($stats['total_spots']); ?> total</div>
            </div>
        </div>
        <div class="stat-card danger animate-fade-up stagger-4">
            <div class="stat-icon danger"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['pending_violations']); ?></div>
                <div class="stat-label">Pending Violations</div>
                <div class="stat-change down"><i class="fas fa-exclamation"></i> Needs attention</div>
            </div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="grid grid-4 mb-24">
        <div class="stat-card info">
            <div class="stat-icon info"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['total_students']); ?></div>
                <div class="stat-label">Students</div>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['total_staff']); ?></div>
                <div class="stat-label">Staff Members</div>
            </div>
        </div>
        <div class="stat-card secondary">
            <div class="stat-icon secondary"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['reserved_spots']); ?></div>
                <div class="stat-label">Reserved Spots</div>
            </div>
        </div>
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-map"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['total_zones']); ?></div>
                <div class="stat-label">Parking Zones</div>
            </div>
        </div>
    </div>

    <!-- Zones Overview & Recent Activity -->
    <div class="grid grid-2 mb-24">
        <!-- Zone Status -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Parking Zones Status</div>
                    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Real-time occupancy by zone</p>
                </div>
                <a href="<?php echo e(route('admin.zones.index')); ?>" class="btn btn-ghost btn-sm">Manage</a>
            </div>

            <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $total = $zone->spots->count();
                $available = $zone->spots->where('status','available')->count();
                $occupied = $zone->spots->where('status','occupied')->count();
                $reserved = $zone->spots->where('status','reserved')->count();
                $rate = $total > 0 ? round((($occupied + $reserved) / $total) * 100) : 0;
            ?>
            <div style="padding:16px;border:1px solid var(--border-color);border-radius:var(--radius-md);margin-bottom:12px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;background:var(--grad-primary);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:12px;"><?php echo e($zone->code); ?></div>
                        <div>
                            <div style="font-weight:700;font-size:14px;"><?php echo e($zone->name); ?></div>
                            <div style="font-size:11px;color:var(--text-muted);"><?php echo e($total); ?> total spots</div>
                        </div>
                    </div>
                    <div style="display:flex;gap:10px;font-size:12px;">
                        <span style="color:var(--success);font-weight:700;"><?php echo e($available); ?> Free</span>
                        <span style="color:var(--warning);font-weight:700;"><?php echo e($reserved); ?> Rsv</span>
                        <span style="color:var(--danger);font-weight:700;"><?php echo e($occupied); ?> Occ</span>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar <?php echo e($rate < 50 ? 'success' : ($rate < 80 ? 'warning' : 'danger')); ?>" style="width:<?php echo e($rate); ?>%;"></div>
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;"><?php echo e($rate); ?>% occupied</div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Recent Reservations -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Recent Reservations</div>
                    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Latest bookings in the system</p>
                </div>
                <a href="<?php echo e(route('admin.reservations.index')); ?>" class="btn btn-ghost btn-sm">View All</a>
            </div>

            <div style="display:flex;flex-direction:column;gap:10px;">
                <?php $__empty_1 = true; $__currentLoopData = $recentReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('admin.reservations.show', $res->id)); ?>" style="display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);text-decoration:none;transition:var(--transition);" onmouseover="this.style.background='rgba(108,99,255,0.04)'" onmouseout="this.style.background='transparent'">
                    <img src="<?php echo e($res->user->avatar_url); ?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:700;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($res->user->name); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?php echo e($res->spot->zone->code); ?>-<?php echo e($res->spot->spot_number); ?> • <?php echo e($res->start_time->format('M d H:i')); ?></div>
                    </div>
                    <span class="badge badge-<?php echo e($res->status_color); ?>"><?php echo e(ucfirst($res->status)); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align:center;padding:30px;color:var(--text-muted);">No reservations yet</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Occupancy rate display -->
    <div class="card mb-24">
        <div class="card-header">
            <div class="card-title">Campus-Wide Occupancy</div>
        </div>
        <div style="display:flex;align-items:center;gap:24px;">
            <div style="width:120px;height:120px;position:relative;flex-shrink:0;">
                <svg viewBox="0 0 36 36" style="width:120px;height:120px;transform:rotate(-90deg);">
                    <circle cx="18" cy="18" r="15.91549430918954" fill="none" stroke="var(--border-color)" stroke-width="3.5"/>
                    <circle cx="18" cy="18" r="15.91549430918954" fill="none"
                            stroke="<?php echo e($occupancyRate < 50 ? 'var(--success)' : ($occupancyRate < 80 ? 'var(--warning)' : 'var(--danger)')); ?>"
                            stroke-width="3.5"
                            stroke-dasharray="<?php echo e($occupancyRate); ?> <?php echo e(100 - $occupancyRate); ?>"
                            stroke-linecap="round"/>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;transform:rotate(0deg);">
                    <div style="font-size:22px;font-weight:900;color:var(--text-primary);"><?php echo e($occupancyRate); ?>%</div>
                    <div style="font-size:10px;color:var(--text-muted);font-weight:600;">Occupied</div>
                </div>
            </div>
            <div style="flex:1;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    <?php $__currentLoopData = [['Available','available_spots','var(--success)'],['Reserved','reserved_spots','var(--warning)'],['Occupied','occupied_spots','var(--danger)']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$key,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="text-align:center;padding:16px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <div style="font-size:28px;font-weight:900;color:<?php echo e($color); ?>;"><?php echo e($stats[$key]); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);font-weight:600;"><?php echo e($label); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Admin Links -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Quick Management</div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;">
            <?php $__currentLoopData = [
                ['route' => 'admin.users.index', 'icon' => 'fa-users', 'label' => 'Users', 'grad' => 'var(--grad-primary)', 'shadow' => 'rgba(108,99,255,0.4)'],
                ['route' => 'admin.zones.index', 'icon' => 'fa-map', 'label' => 'Zones', 'grad' => 'var(--grad-secondary)', 'shadow' => 'rgba(0,180,216,0.4)'],
                ['route' => 'admin.spots.index', 'icon' => 'fa-parking', 'label' => 'Spots', 'grad' => 'var(--grad-success)', 'shadow' => 'rgba(6,214,160,0.4)'],
                ['route' => 'admin.violations.index', 'icon' => 'fa-exclamation-triangle', 'label' => 'Violations', 'grad' => 'var(--grad-danger)', 'shadow' => 'rgba(239,71,111,0.4)'],
                ['route' => 'admin.reports.index', 'icon' => 'fa-chart-bar', 'label' => 'Reports', 'grad' => 'var(--grad-warning)', 'shadow' => 'rgba(255,183,3,0.4)'],
                ['route' => 'admin.permissions.index', 'icon' => 'fa-shield-alt', 'label' => 'Permissions', 'grad' => 'var(--grad-accent)', 'shadow' => 'rgba(240,147,251,0.4)'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($link['route'])); ?>" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:20px;border:2px solid transparent;border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);background:rgba(108,99,255,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)';this.style.borderColor='rgba(108,99,255,0.12)'" onmouseout="this.style.transform='none';this.style.boxShadow='none';this.style.borderColor='transparent'">
                <div style="width:50px;height:50px;background:<?php echo e($link['grad']); ?>;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;box-shadow:0 4px 15px <?php echo e($link['shadow']); ?>;">
                    <i class="fas <?php echo e($link['icon']); ?>"></i>
                </div>
                <span style="font-size:13px;font-weight:700;color:var(--text-primary);"><?php echo e($link['label']); ?></span>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>