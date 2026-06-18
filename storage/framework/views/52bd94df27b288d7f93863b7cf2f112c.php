<?php $__env->startSection('title', 'Zone: ' . $zone->name); ?>
<?php $__env->startSection('page-title', 'Zone Details: ' . $zone->name); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">
    <div class="mb-16">
        <a href="<?php echo e(route('admin.zones.index')); ?>" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Zones
        </a>
    </div>

    <div class="grid grid-2" style="gap:24px;align-items:start;">
        <div>
            <div class="card mb-20">
                <div style="background:var(--grad-primary);border-radius:var(--radius-md);padding:24px;margin-bottom:20px;position:relative;overflow:hidden;">
                    <div style="font-size:64px;font-weight:900;color:rgba(255,255,255,0.12);position:absolute;right:16px;top:8px;line-height:1;"><?php echo e($zone->code); ?></div>
                    <div style="color:#fff;">
                        <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.8px;">Zone <?php echo e($zone->code); ?></div>
                        <div style="font-size:24px;font-weight:800;margin-top:4px;"><?php echo e($zone->name); ?></div>
                        <?php if($zone->location): ?>
                        <div style="font-size:13px;opacity:0.8;margin-top:6px;"><i class="fas fa-map-marker-alt"></i> <?php echo e($zone->location); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                    $total = $zone->spots->count();
                    $available = $zone->spots->where('status','available')->count();
                    $occupied = $zone->spots->where('status','occupied')->count();
                    $reserved = $zone->spots->where('status','reserved')->count();
                    $rate = $total > 0 ? round((($occupied + $reserved) / $total) * 100) : 0;
                ?>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;margin-bottom:20px;">
                    <?php $__currentLoopData = [[$available,'Free','var(--success)'],[$reserved,'Reserved','var(--warning)'],[$occupied,'Occupied','var(--danger)'],[$total,'Total','var(--primary)']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$val,$label,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="text-align:center;padding:14px;background:rgba(108,99,255,0.03);border-radius:var(--radius-md);border:1px solid var(--border-color);">
                        <div style="font-size:22px;font-weight:800;color:<?php echo e($color); ?>;"><?php echo e($val); ?></div>
                        <div style="font-size:11px;color:var(--text-muted);font-weight:600;"><?php echo e($label); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="progress mb-8"><div class="progress-bar <?php echo e($rate < 50 ? 'success' : ($rate < 80 ? 'warning' : 'danger')); ?>" style="width:<?php echo e($rate); ?>%;"></div></div>
                <div style="font-size:12px;color:var(--text-muted);text-align:center;margin-bottom:20px;"><?php echo e($rate); ?>% occupied</div>

                <?php if($zone->description): ?>
                <p style="font-size:14px;color:var(--text-secondary);margin-bottom:16px;"><?php echo e($zone->description); ?></p>
                <?php endif; ?>

                <div style="display:flex;gap:8px;">
                    <a href="<?php echo e(route('admin.zones.edit', $zone->id)); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit Zone
                    </a>
                    <a href="<?php echo e(route('admin.spots.index')); ?>?zone_id=<?php echo e($zone->id); ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-parking"></i> Manage Spots
                    </a>
                </div>
            </div>
        </div>

        <!-- Spots Grid -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Parking Spots</div>
                <a href="<?php echo e(route('admin.spots.create')); ?>?zone=<?php echo e($zone->id); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Spot</a>
            </div>
            <div class="parking-grid" style="padding:0;grid-template-columns:repeat(auto-fill,minmax(65px,1fr));gap:8px;max-height:400px;overflow-y:auto;">
                <?php $__currentLoopData = $zone->spots->sortBy('spot_number'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('admin.spots.show', $spot->id)); ?>" class="parking-spot <?php echo e($spot->status); ?>" title="<?php echo e($zone->code); ?>-<?php echo e($spot->spot_number); ?>">
                    <i class="fas <?php echo e(['standard'=>'fa-car','staff'=>'fa-briefcase','disabled'=>'fa-wheelchair','vip'=>'fa-star'][$spot->type] ?? 'fa-car'); ?>" style="font-size:13px;"></i>
                    <span style="font-size:10px;font-weight:700;"><?php echo e($zone->code); ?>-<?php echo e($spot->spot_number); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/admin/zones/show.blade.php ENDPATH**/ ?>