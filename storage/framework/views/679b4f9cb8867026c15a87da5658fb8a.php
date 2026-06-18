

<?php $__env->startSection('title', 'All Reservations'); ?>
<?php $__env->startSection('page-title', 'Reservations'); ?>
<?php $__env->startSection('page-subtitle', 'Manage all parking reservations across the campus'); ?>

<?php $__env->startSection('content'); ?>
<div>

    <div class="grid grid-4 mb-24">
        <?php $__currentLoopData = [['label'=>'Total','value'=>$stats['total'],'icon'=>'fa-calendar-check','class'=>'primary'],['label'=>'Active','value'=>$stats['active'],'icon'=>'fa-check-circle','class'=>'success'],['label'=>'Completed','value'=>$stats['completed'],'icon'=>'fa-flag-checkered','class'=>'secondary'],['label'=>'Cancelled','value'=>$stats['cancelled'],'icon'=>'fa-times-circle','class'=>'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="stat-card <?php echo e($s['class']); ?>">
            <div class="stat-icon <?php echo e($s['class']); ?>"><i class="fas <?php echo e($s['icon']); ?>"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($s['value']); ?></div>
                <div class="stat-label"><?php echo e($s['label']); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Reservations</div>
                <div style="font-size:13px;color:var(--text-muted);"><?php echo e($reservations->total()); ?> records</div>
            </div>
            <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                <div class="input-group" style="width:220px;">
                    <span class="input-icon"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo e(request('search')); ?>" style="padding:8px 8px 8px 40px;border-radius:var(--radius-full);">
                </div>
                <select name="status" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = ['active','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <th>Start</th>
                        <th>End</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#<?php echo e($res->id); ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <img src="<?php echo e($res->user->avatar_url); ?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                                <div>
                                    <div style="font-size:13px;font-weight:700;"><?php echo e($res->user->name); ?></div>
                                    <div style="font-size:11px;color:var(--text-muted);"><?php echo e(ucfirst($res->user->role)); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:16px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo e($res->spot->zone->code); ?>-<?php echo e($res->spot->spot_number); ?></div>
                        </td>
                        <td style="font-size:13px;font-weight:600;"><?php echo e($res->start_time->format('M d, H:i')); ?></td>
                        <td style="font-size:13px;font-weight:600;"><?php echo e($res->end_time->format('M d, H:i')); ?></td>
                        <td style="font-size:13px;font-weight:600;color:var(--primary);"><?php echo e($res->duration); ?></td>
                        <td>
                            <span class="badge badge-<?php echo e($res->status_color); ?>"><?php echo e(ucfirst($res->status)); ?></span>
                        </td>
                        <td data-label="Actions">
                            <div class="table-actions">
                                <a href="<?php echo e(route('admin.reservations.show', $res->id)); ?>" class="btn-action-view" title="View"><i class="fas fa-eye"></i></a>
                                <?php if($res->status === 'active'): ?>
                                <form action="<?php echo e(route('admin.reservations.complete', $res->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action-success" title="Mark Complete"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="<?php echo e(route('admin.reservations.cancel', $res->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action-delete" title="Cancel"><i class="fas fa-times"></i></button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" style="text-align:center;padding:50px;color:var(--text-muted);">No reservations found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($reservations->hasPages()): ?>
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                <?php if($reservations->onFirstPage()): ?><span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span><?php else: ?><a href="<?php echo e($reservations->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a><?php endif; ?>
                <?php $__currentLoopData = $reservations->getUrlRange(1, $reservations->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $reservations->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($reservations->hasMorePages()): ?><a href="<?php echo e($reservations->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a><?php else: ?><span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/reservations/index.blade.php ENDPATH**/ ?>