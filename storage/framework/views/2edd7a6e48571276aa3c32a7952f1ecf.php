<?php $__env->startSection('title', 'Add Spot'); ?>
<?php $__env->startSection('page-title', 'Add Parking Spot'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="<?php echo e(route('admin.spots.index')); ?>" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Spots
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">New Parking Spot</div>
        </div>

        <form action="<?php echo e(route('admin.spots.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Zone <span class="required">*</span></label>
                    <select name="zone_id" class="form-select" required>
                        <option value="">Select Zone</option>
                        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($zone->id); ?>" <?php echo e((old('zone_id', request('zone')) == $zone->id) ? 'selected' : ''); ?>><?php echo e($zone->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Spot Number <span class="required">*</span></label>
                    <input type="text" name="spot_number" class="form-control" value="<?php echo e(old('spot_number')); ?>" placeholder="e.g. 001" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Spot Type <span class="required">*</span></label>
                    <select name="type" class="form-select" required>
                        <?php $__currentLoopData = ['standard','staff','disabled','vip']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>" <?php echo e(old('type') === $type ? 'selected' : ''); ?>><?php echo e(ucfirst($type)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php $__currentLoopData = ['available','reserved','occupied','maintenance']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status','available') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Floor</label>
                    <input type="text" name="floor" class="form-control" value="<?php echo e(old('floor', 'G')); ?>" placeholder="G, 1, 2...">
                </div>
                <div class="form-group">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control" value="<?php echo e(old('section')); ?>" placeholder="A, B, C...">
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:600;">
                    <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--primary);width:18px;height:18px;">
                    Spot Active
                </label>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Spot</button>
                <a href="<?php echo e(route('admin.spots.index')); ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/spots/create.blade.php ENDPATH**/ ?>