

<?php $__env->startSection('title', 'Violations'); ?>
<?php $__env->startSection('page-title', 'Parking Violations'); ?>
<?php $__env->startSection('page-subtitle', 'Manage and track parking violations and fines'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex-between mb-24" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        <a href="<?php echo e(route('admin.violations.create')); ?>" class="btn btn-danger">
            <i class="fas fa-plus"></i> Issue Violation
        </a>
    </div>

    <div class="grid grid-4 mb-24">
        <?php $__currentLoopData = [['label'=>'Total','value'=>$stats['total'],'icon'=>'fa-file-alt','class'=>'primary'],['label'=>'Pending','value'=>$stats['pending'],'icon'=>'fa-clock','class'=>'warning'],['label'=>'Resolved','value'=>$stats['resolved'],'icon'=>'fa-check-circle','class'=>'success'],['label'=>'Total Fines (SAR)','value'=>number_format($stats['total_fines'],0),'icon'=>'fa-money-bill-wave','class'=>'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="stat-card <?php echo e($s['class']); ?>">
            <div class="stat-icon <?php echo e($s['class']); ?>"><i class="fas <?php echo e($s['icon']); ?>"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:22px;"><?php echo e($s['value']); ?></div>
                <div class="stat-label"><?php echo e($s['label']); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Violations</div>
                <div style="font-size:13px;color:var(--text-muted);"><?php echo e($violations->total()); ?> records</div>
            </div>
            <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                <div class="input-group" style="width:220px;">
                    <span class="input-icon"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search plate, user..." value="<?php echo e(request('search')); ?>" style="padding:8px 8px 8px 40px;border-radius:var(--radius-full);">
                </div>
                <select name="status" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = ['pending','resolved','dismissed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                        <th>User / Vehicle</th>
                        <th>Type</th>
                        <th>Spot</th>
                        <th>Fine (SAR)</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $violations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#<?php echo e($v->id); ?></td>
                        <td>
                            <?php if($v->user): ?>
                            <div style="font-size:13px;font-weight:700;"><?php echo e($v->user->name); ?></div>
                            <?php endif; ?>
                            <div style="font-size:13px;font-weight:700;color:var(--primary);"><?php echo e($v->vehicle_plate); ?></div>
                        </td>
                        <td>
                            <span class="badge badge-warning"><?php echo e(str_replace('_', ' ', ucfirst($v->type))); ?></span>
                        </td>
                        <td>
                            <?php if($v->spot): ?>
                            <div style="font-weight:700;font-size:14px;"><?php echo e($v->spot->zone->code); ?>-<?php echo e($v->spot->spot_number); ?></div>
                            <?php else: ?> — <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight:800;font-size:16px;color:var(--danger);"><?php echo e(number_format($v->fine_amount, 0)); ?></div>
                            <?php if($v->fine_paid): ?><div style="font-size:11px;color:var(--success);font-weight:700;">Paid ✓</div><?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e(['pending'=>'warning','resolved'=>'success','dismissed'=>'secondary'][$v->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($v->status)); ?></span>
                        </td>
                        <td style="font-size:13px;color:var(--text-muted);"><?php echo e($v->violation_time?->format('M d, Y') ?? $v->created_at->format('M d, Y')); ?></td>
                        <td data-label="Actions">
                            <div class="table-actions">
                                <a href="<?php echo e(route('admin.violations.show', $v->id)); ?>" class="btn-action-view" title="View"><i class="fas fa-eye"></i></a>
                                <?php if($v->status === 'pending'): ?>
                                <form action="<?php echo e(route('admin.violations.resolve', $v->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Mark as resolved?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action-success" title="Resolve"><i class="fas fa-check"></i></button>
                                </form>
                                <?php endif; ?>
                                <?php if(!$v->fine_paid && $v->status !== 'dismissed'): ?>
                                <form action="<?php echo e(route('admin.violations.fine-paid', $v->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action-warning" title="Mark Fine Paid"><i class="fas fa-money-bill"></i></button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" style="text-align:center;padding:50px;color:var(--text-muted);">No violations found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($violations->hasPages()): ?>
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                <?php if($violations->onFirstPage()): ?><span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span><?php else: ?><a href="<?php echo e($violations->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a><?php endif; ?>
                <?php $__currentLoopData = $violations->getUrlRange(1, $violations->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $violations->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($violations->hasMorePages()): ?><a href="<?php echo e($violations->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a><?php else: ?><span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/violations/index.blade.php ENDPATH**/ ?>