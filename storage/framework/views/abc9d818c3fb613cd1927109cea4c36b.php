<?php $__env->startSection('title', 'Parking Zones'); ?>
<?php $__env->startSection('page-title', 'Parking Zones'); ?>
<?php $__env->startSection('page-subtitle', 'Manage campus parking zones'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">
    <div class="flex-between mb-24" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        <a href="<?php echo e(route('admin.zones.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Zone
        </a>
    </div>

    <div class="grid grid-3" style="gap:24px;">
        <?php $__empty_1 = true; $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $total = $zone->spots->count();
            $available = $zone->spots->where('status','available')->count();
            $occupied = $zone->spots->where('status','occupied')->count();
            $reserved = $zone->spots->where('status','reserved')->count();
            $rate = $total > 0 ? round((($occupied + $reserved) / $total) * 100) : 0;
            $grads = ['ZA' => 'var(--grad-primary)', 'ZB' => 'var(--grad-secondary)', 'ZC' => 'var(--grad-success)', 'ZD' => 'var(--grad-accent)'];
        ?>
        <div class="zone-card">
            <div class="zone-card-header" style="background:<?php echo e($grads[$zone->code] ?? 'var(--grad-primary)'); ?>;padding:24px;">
                <span class="zone-code" style="font-size:48px;opacity:0.15;right:16px;top:8px;"><?php echo e($zone->code); ?></span>
                <div style="color:#fff;">
                    <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.8px;">Zone <?php echo e($zone->code); ?></div>
                    <div style="font-size:20px;font-weight:800;margin-top:4px;"><?php echo e($zone->name); ?></div>
                    <?php if($zone->location): ?>
                    <div style="font-size:12px;opacity:0.8;margin-top:4px;"><i class="fas fa-map-marker-alt"></i> <?php echo e($zone->location); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="zone-card-body">
                <div class="zone-availability" style="margin-bottom:12px;">
                    <div class="zone-stat">
                        <div class="zone-stat-value" style="color:var(--success);"><?php echo e($available); ?></div>
                        <div class="zone-stat-label">Free</div>
                    </div>
                    <div class="zone-stat">
                        <div class="zone-stat-value" style="color:var(--warning);"><?php echo e($reserved); ?></div>
                        <div class="zone-stat-label">Reserved</div>
                    </div>
                    <div class="zone-stat">
                        <div class="zone-stat-value" style="color:var(--danger);"><?php echo e($occupied); ?></div>
                        <div class="zone-stat-label">Occupied</div>
                    </div>
                    <div class="zone-stat">
                        <div class="zone-stat-value" style="color:var(--primary);"><?php echo e($total); ?></div>
                        <div class="zone-stat-label">Total</div>
                    </div>
                </div>

                <div class="progress mb-12">
                    <div class="progress-bar <?php echo e($rate < 50 ? 'success' : ($rate < 80 ? 'warning' : 'danger')); ?>" style="width:<?php echo e($rate); ?>%;"></div>
                </div>
                <div style="font-size:12px;color:var(--text-muted);text-align:center;margin-bottom:16px;"><?php echo e($rate); ?>% occupied</div>

                <?php if($zone->description): ?>
                <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;line-height:1.5;"><?php echo e($zone->description); ?></p>
                <?php endif; ?>

                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <span class="badge <?php echo e($zone->is_active ? 'badge-success' : 'badge-secondary'); ?>"><?php echo e($zone->is_active ? 'Active' : 'Inactive'); ?></span>
                    <?php if($zone->allowed_roles): ?>
                    <span class="badge badge-info"><?php echo e(ucfirst(implode(', ', $zone->allowed_roles))); ?></span>
                    <?php endif; ?>
                </div>

                <div style="display:flex;gap:8px;margin-top:16px;padding-top:16px;border-top:1px solid var(--border-color);">
                    <a href="<?php echo e(route('admin.zones.show', $zone->id)); ?>" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center;">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="<?php echo e(route('admin.zones.edit', $zone->id)); ?>" class="btn btn-warning btn-sm" style="flex:1;justify-content:center;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="<?php echo e(route('admin.zones.destroy', $zone->id)); ?>" method="POST" onsubmit="return confirm('Delete zone <?php echo e($zone->name); ?>?')" style="display:inline;">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-action-delete" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-muted);">
            <i class="fas fa-map" style="font-size:48px;margin-bottom:16px;display:block;opacity:0.2;"></i>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px;">No zones yet</div>
            <a href="<?php echo e(route('admin.zones.create')); ?>" class="btn btn-primary mt-12">Add First Zone</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/admin/zones/index.blade.php ENDPATH**/ ?>