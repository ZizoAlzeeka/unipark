

<?php $__env->startSection('title', 'Entry / Exit Logs'); ?>
<?php $__env->startSection('page-title', 'Entry / Exit Logs'); ?>
<?php $__env->startSection('page-subtitle', 'Track all parking entry and exit events'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex-between mb-20" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        <a href="<?php echo e(route('admin.entry-exit.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Log New Event
        </a>
    </div>

    <div class="grid grid-3 mb-24">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-door-open"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['entries_today']); ?></div>
                <div class="stat-label">Entries Today</div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fas fa-door-closed"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['exits_today']); ?></div>
                <div class="stat-label">Exits Today</div>
            </div>
        </div>
        <div class="stat-card secondary">
            <div class="stat-icon secondary"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['total_today']); ?></div>
                <div class="stat-label">Total Today</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Entry/Exit Events</div>
                <div style="font-size:13px;color:var(--text-muted);"><?php echo e($logs->total()); ?> records</div>
            </div>
            <form method="GET" style="display:flex;gap:10px;">
                <select name="event_type" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                    <option value="">All Events</option>
                    <option value="entry" <?php echo e(request('event_type') === 'entry' ? 'selected' : ''); ?>>Entry Only</option>
                    <option value="exit" <?php echo e(request('event_type') === 'exit' ? 'selected' : ''); ?>>Exit Only</option>
                </select>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Spot</th>
                        <th>Event</th>
                        <th>Time</th>
                        <th>Vehicle</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#<?php echo e($log->id); ?></td>
                        <td>
                            <?php if($log->user): ?>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <img src="<?php echo e($log->user->avatar_url); ?>" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                                <div>
                                    <div style="font-size:13px;font-weight:700;"><?php echo e($log->user->name); ?></div>
                                    <div style="font-size:11px;color:var(--text-muted);"><?php echo e(ucfirst($log->user->role)); ?></div>
                                </div>
                            </div>
                            <?php else: ?>
                            <span style="color:var(--text-muted);">Unknown</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($log->spot): ?>
                            <div style="font-size:16px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo e($log->spot->zone->code); ?>-<?php echo e($log->spot->spot_number); ?></div>
                            <?php else: ?> — <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?php echo e($log->event_type === 'entry' ? 'badge-success' : 'badge-danger'); ?>">
                                <i class="fas fa-arrow-<?php echo e($log->event_type === 'entry' ? 'right' : 'left'); ?>"></i>
                                <?php echo e(ucfirst($log->event_type)); ?>

                            </span>
                        </td>
                        <td style="font-size:13px;font-weight:600;"><?php echo e($log->event_time->format('M d, Y H:i')); ?></td>
                        <td style="font-size:13px;"><?php echo e($log->vehicle_plate ?? '—'); ?></td>
                        <td>
                            <span class="badge badge-secondary"><?php echo e(ucfirst($log->method ?? 'manual')); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;padding:50px;color:var(--text-muted);">No logs found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($logs->hasPages()): ?>
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                <?php if($logs->onFirstPage()): ?><span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span><?php else: ?><a href="<?php echo e($logs->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a><?php endif; ?>
                <?php $__currentLoopData = $logs->getUrlRange(1, $logs->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $logs->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($logs->hasMorePages()): ?><a href="<?php echo e($logs->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a><?php else: ?><span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/entry-exit/index.blade.php ENDPATH**/ ?>