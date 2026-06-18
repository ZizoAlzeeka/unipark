<?php $__env->startSection('title', 'History'); ?>
<?php $__env->startSection('page-title', 'Parking History'); ?>
<?php $__env->startSection('page-subtitle', 'Your past and cancelled reservations'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">
    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">Reservation History</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px;"><?php echo e($reservations->total()); ?> records found</div>
            </div>
            <a href="<?php echo e(route('reservations.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Reservation
            </a>
        </div>

        <?php if($reservations->count() > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Spot</th>
                        <th>Zone</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#<?php echo e($res->id); ?></td>
                        <td>
                            <div style="font-size:16px;font-weight:800;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo e($res->spot->zone->code); ?>-<?php echo e($res->spot->spot_number); ?></div>
                        </td>
                        <td style="color:var(--text-secondary);"><?php echo e($res->spot->zone->name); ?></td>
                        <td>
                            <div style="font-size:13px;font-weight:600;"><?php echo e($res->start_time->format('M d, Y')); ?></div>
                            <div style="font-size:12px;color:var(--text-muted);"><?php echo e($res->start_time->format('H:i')); ?> — <?php echo e($res->end_time->format('H:i')); ?></div>
                        </td>
                        <td><span style="font-size:13px;font-weight:600;color:var(--primary);"><?php echo e($res->duration); ?></span></td>
                        <td style="font-size:13px;"><?php echo e($res->vehicle_plate ?? '—'); ?></td>
                        <td>
                            <span class="badge badge-<?php echo e($res->status_color); ?>"><?php echo e(ucfirst($res->status)); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('reservations.show', $res->id)); ?>" class="btn btn-ghost btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($reservations->hasPages()): ?>
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                <?php if($reservations->onFirstPage()): ?>
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                <?php else: ?>
                    <a href="<?php echo e($reservations->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                <?php endif; ?>
                <?php $__currentLoopData = $reservations->getUrlRange(1, $reservations->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $reservations->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($reservations->hasMorePages()): ?>
                    <a href="<?php echo e($reservations->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                <?php else: ?>
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div style="padding:60px;text-align:center;color:var(--text-muted);">
            <i class="fas fa-history" style="font-size:52px;margin-bottom:16px;display:block;opacity:0.2;"></i>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px;">No history yet</div>
            <p style="margin-bottom:20px;">Your completed and cancelled reservations will appear here.</p>
            <a href="<?php echo e(route('reservations.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Make a Reservation
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/user/history.blade.php ENDPATH**/ ?>