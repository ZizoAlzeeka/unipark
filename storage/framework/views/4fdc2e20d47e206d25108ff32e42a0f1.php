

<?php $__env->startSection('title', 'Reports & Analytics'); ?>
<?php $__env->startSection('page-title', 'Reports & Analytics'); ?>
<?php $__env->startSection('page-subtitle', 'Comprehensive parking system insights and statistics'); ?>

<?php $__env->startSection('content'); ?>
<div>

    <!-- Date Range Filter -->
    <div class="card mb-24">
        <form method="GET" style="display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e($dateFrom); ?>" style="width:160px;">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e($dateTo); ?>" style="width:160px;">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="<?php echo e(route('admin.reports.export')); ?>?date_from=<?php echo e($dateFrom); ?>&date_to=<?php echo e($dateTo); ?>&type=reservations" class="btn btn-ghost">
                <i class="fas fa-download"></i> Export Reservations CSV
            </a>
            <a href="<?php echo e(route('admin.reports.export')); ?>?date_from=<?php echo e($dateFrom); ?>&date_to=<?php echo e($dateTo); ?>&type=violations" class="btn btn-ghost">
                <i class="fas fa-download"></i> Export Violations CSV
            </a>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-4 mb-24">
        <?php $__currentLoopData = [
            ['label' => 'Total Reservations', 'value' => number_format($summary['total_reservations']), 'icon' => 'fa-calendar-check', 'class' => 'primary'],
            ['label' => 'Total Users', 'value' => number_format($summary['total_users']), 'icon' => 'fa-users', 'class' => 'success'],
            ['label' => 'Total Fines (SAR)', 'value' => number_format($summary['total_fines'], 0), 'icon' => 'fa-money-bill-wave', 'class' => 'warning'],
            ['label' => 'Violations', 'value' => number_format($summary['total_violations']), 'icon' => 'fa-exclamation-triangle', 'class' => 'danger'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="stat-card <?php echo e($s['class']); ?>">
            <div class="stat-icon <?php echo e($s['class']); ?>"><i class="fas <?php echo e($s['icon']); ?>"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:22px;"><?php echo e($s['value']); ?></div>
                <div class="stat-label"><?php echo e($s['label']); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-2 mb-24">
        <!-- Zone Usage -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Zone Usage</div>
                <i class="fas fa-map" style="color:var(--secondary);font-size:20px;"></i>
            </div>
            <?php $__currentLoopData = $zoneUsage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $total = max($zone['total_spots'], 1);
                $rate = $total > 0 ? round($zone['total_reservations'] / max($zone['total_reservations'], 1) * 100) : 0;
                // Simple ratio: available/total
                $occupancyRate = $total > 0 ? round((($total - $zone['available_spots']) / $total) * 100) : 0;
            ?>
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <span style="font-size:14px;font-weight:700;"><?php echo e($zone['name']); ?> <small style="color:var(--text-muted);font-weight:500;">(<?php echo e($zone['code']); ?>)</small></span>
                    <div style="display:flex;gap:12px;font-size:12px;color:var(--text-muted);">
                        <span><?php echo e($zone['total_reservations']); ?> reservations</span>
                        <span style="font-weight:800;color:<?php echo e($occupancyRate < 50 ? 'var(--success)' : ($occupancyRate < 80 ? 'var(--warning)' : 'var(--danger)')); ?>;"><?php echo e($occupancyRate); ?>%</span>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar <?php echo e($occupancyRate < 50 ? 'success' : ($occupancyRate < 80 ? 'warning' : 'danger')); ?>" style="width:<?php echo e($occupancyRate); ?>%;"></div>
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px;"><?php echo e($zone['available_spots']); ?> / <?php echo e($zone['total_spots']); ?> spots available</div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Violation Types -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Violations by Type</div>
                <i class="fas fa-chart-pie" style="color:var(--danger);font-size:20px;"></i>
            </div>
            <?php if($violationStats->count() > 0): ?>
            <?php $__currentLoopData = $violationStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);margin-bottom:8px;">
                <div>
                    <div style="font-size:14px;font-weight:700;"><?php echo e(str_replace('_',' ', ucfirst($vStat->type))); ?></div>
                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($vStat->count); ?> violation(s)</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:14px;font-weight:800;color:var(--danger);">SAR <?php echo e(number_format($vStat->total_fines, 0)); ?></div>
                    <span class="badge badge-warning" style="font-size:10px;"><?php echo e($vStat->count); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <div style="text-align:center;padding:30px;color:var(--text-muted);">No violations in this period</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-2 mb-24">
        <!-- Peak Hours -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Peak Parking Hours</div>
                <i class="fas fa-clock" style="color:var(--warning);font-size:20px;"></i>
            </div>
            <?php $maxCount = $peakHours->max('count') ?: 1; ?>
            <?php if($peakHours->count() > 0): ?>
            <?php $__currentLoopData = $peakHours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $pct = round($hour->count / $maxCount * 100); ?>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                <div style="min-width:50px;font-size:13px;font-weight:700;color:var(--text-secondary);"><?php echo e(str_pad($hour->hour, 2, '0', STR_PAD_LEFT)); ?>:00</div>
                <div style="flex:1;">
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar <?php echo e($pct >= 80 ? 'danger' : ($pct >= 60 ? 'warning' : 'success')); ?>" style="width:<?php echo e($pct); ?>%;"></div>
                    </div>
                </div>
                <div style="min-width:36px;font-size:13px;font-weight:700;color:var(--text-muted);text-align:right;"><?php echo e($hour->count); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <div style="text-align:center;padding:30px;color:var(--text-muted);">No data for peak hours</div>
            <?php endif; ?>
        </div>

        <!-- Top Users -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Most Active Users</div>
                <i class="fas fa-trophy" style="color:var(--warning);font-size:20px;"></i>
            </div>
            <?php $__currentLoopData = $topUsers->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;align-items:center;gap:12px;padding:10px;border-radius:var(--radius-md);<?php echo e($i < 3 ? 'background:rgba(255,183,3,0.04);border:1px solid rgba(255,183,3,0.1);' : ''); ?>margin-bottom:6px;">
                <div style="width:28px;height:28px;border-radius:50%;<?php echo e($i === 0 ? 'background:linear-gradient(135deg,#FFD700,#FFA500);' : ($i === 1 ? 'background:linear-gradient(135deg,#C0C0C0,#808080);' : ($i === 2 ? 'background:linear-gradient(135deg,#CD7F32,#8B4513);' : 'background:rgba(108,99,255,0.1);'))); ?>display:flex;align-items:center;justify-content:center;color:<?php echo e($i < 3 ? '#fff' : 'var(--primary)'); ?>;font-weight:800;font-size:12px;flex-shrink:0;">
                    <?php echo e($i + 1); ?>

                </div>
                <img src="<?php echo e($user->avatar_url); ?>" style="width:34px;height:34px;border-radius:50%;border:2px solid var(--primary);object-fit:cover;">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($user->name); ?></div>
                    <div style="font-size:11px;color:var(--text-muted);"><?php echo e(ucfirst($user->role)); ?></div>
                </div>
                <span style="font-size:14px;font-weight:800;color:var(--primary);"><?php echo e($user->reservations_count); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Daily Reservations table -->
    <?php if($reservationStats->count() > 0): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Daily Reservations Breakdown</div>
            <div style="font-size:13px;color:var(--text-muted);"><?php echo e($dateFrom); ?> to <?php echo e($dateTo); ?></div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Active</th>
                        <th>Completed</th>
                        <th>Cancelled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reservationStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="font-weight:600;"><?php echo e(\Carbon\Carbon::parse($day->date)->format('M d, Y (D)')); ?></td>
                        <td><span style="font-weight:800;color:var(--primary);"><?php echo e($day->total); ?></span></td>
                        <td><span style="font-weight:700;color:var(--success);"><?php echo e($day->active); ?></span></td>
                        <td><span style="font-weight:700;color:var(--secondary);"><?php echo e($day->completed); ?></span></td>
                        <td><span style="font-weight:700;color:var(--danger);"><?php echo e($day->cancelled); ?></span></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>