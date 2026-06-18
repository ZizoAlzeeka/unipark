

<?php $__env->startSection('title', 'Parking Spots'); ?>
<?php $__env->startSection('page-title', 'Parking Spots'); ?>
<?php $__env->startSection('page-subtitle', 'Manage individual parking spots'); ?>

<?php $__env->startSection('content'); ?>
<div>

    <div class="grid grid-4 mb-24">
        <?php $__currentLoopData = [['label'=>'Total Spots','value'=>$stats['total'],'icon'=>'fa-parking','class'=>'primary'],['label'=>'Available','value'=>$stats['available'],'icon'=>'fa-check-circle','class'=>'success'],['label'=>'Reserved','value'=>$stats['reserved'],'icon'=>'fa-clock','class'=>'warning'],['label'=>'Occupied','value'=>$stats['occupied'],'icon'=>'fa-times-circle','class'=>'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                <div style="font-size:16px;font-weight:700;">All Spots</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px;"><?php echo e($spots->total()); ?> total spots</div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                    <select name="zone_id" class="form-select" style="width:160px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                        <option value="">All Zones</option>
                        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($zone->id); ?>" <?php echo e(request('zone_id') == $zone->id ? 'selected' : ''); ?>><?php echo e($zone->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select name="status" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <?php $__currentLoopData = ['available','reserved','occupied','maintenance']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>
                <a href="<?php echo e(route('admin.spots.create')); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Spot</a>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Spot</th>
                        <th>Zone</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $spots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="font-size:18px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo e($spot->zone->code); ?>-<?php echo e($spot->spot_number); ?></div>
                        </td>
                        <td style="color:var(--text-secondary);font-weight:600;"><?php echo e($spot->zone->name); ?></td>
                        <td>
                            <span style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;">
                                <i class="fas <?php echo e(['standard'=>'fa-car','staff'=>'fa-briefcase','disabled'=>'fa-wheelchair','vip'=>'fa-star'][$spot->type] ?? 'fa-car'); ?>" style="color:var(--primary);"></i>
                                <?php echo e(ucfirst($spot->type)); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e(['available'=>'success','reserved'=>'warning','occupied'=>'danger','maintenance'=>'secondary'][$spot->status] ?? 'secondary'); ?>">
                                <?php echo e(ucfirst($spot->status)); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo e($spot->is_active ? 'badge-success' : 'badge-secondary'); ?>"><?php echo e($spot->is_active ? 'Yes' : 'No'); ?></span>
                        </td>
                        <td data-label="Actions">
                            <div class="table-actions">
                                <a href="<?php echo e(route('admin.spots.show', $spot->id)); ?>" class="btn-action-view" title="View"><i class="fas fa-eye"></i></a>
                                <a href="<?php echo e(route('admin.spots.edit', $spot->id)); ?>" class="btn-action-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="<?php echo e(route('admin.spots.destroy', $spot->id)); ?>" method="POST" onsubmit="return confirm('Delete this spot?')" style="display:inline;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-action-delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align:center;padding:50px;color:var(--text-muted);">No spots found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($spots->hasPages()): ?>
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                <?php if($spots->onFirstPage()): ?><span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span><?php else: ?><a href="<?php echo e($spots->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a><?php endif; ?>
                <?php $__currentLoopData = $spots->getUrlRange(1, $spots->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $spots->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($spots->hasMorePages()): ?><a href="<?php echo e($spots->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a><?php else: ?><span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/spots/index.blade.php ENDPATH**/ ?>