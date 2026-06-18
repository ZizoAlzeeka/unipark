

<?php $__env->startSection('title', 'User: ' . $user->name); ?>
<?php $__env->startSection('page-title', 'User Profile'); ?>
<?php $__env->startSection('page-subtitle', $user->name . ' — ' . ucfirst($user->role)); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="mb-16">
        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="grid grid-2" style="gap:24px;align-items:start;">
        <div>
            <div class="card mb-20">
                <div style="text-align:center;padding:20px 0 8px;">
                    <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>"
                         style="width:90px;height:90px;border-radius:50%;border:4px solid var(--primary);object-fit:cover;margin-bottom:16px;">
                    <h2 style="font-size:20px;font-weight:800;"><?php echo e($user->name); ?></h2>
                    <p style="color:var(--text-muted);font-size:13px;"><?php echo e($user->email); ?></p>
                    <div style="display:flex;gap:8px;justify-content:center;margin-top:10px;">
                        <span class="badge <?php echo e($user->role === 'admin' ? 'badge-danger' : ($user->role === 'staff' ? 'badge-info' : 'badge-primary')); ?>"><?php echo e(ucfirst($user->role)); ?></span>
                        <span class="badge <?php echo e($user->is_active ? 'badge-success' : 'badge-secondary'); ?>"><?php echo e($user->is_active ? 'Active' : 'Inactive'); ?></span>
                    </div>
                </div>

                <div style="margin-top:24px;border-top:1px solid var(--border-color);padding-top:20px;display:flex;flex-direction:column;gap:12px;">
                    <?php $__currentLoopData = [
                        ['label' => 'University ID', 'value' => $user->university_id ?? '—', 'icon' => 'fa-id-badge'],
                        ['label' => 'Phone', 'value' => $user->phone ?? '—', 'icon' => 'fa-phone'],
                        ['label' => 'Vehicle Plate', 'value' => $user->vehicle_plate ?? '—', 'icon' => 'fa-car'],
                        ['label' => 'Vehicle Model', 'value' => $user->vehicle_model ?? '—', 'icon' => 'fa-car-side'],
                        ['label' => 'Joined', 'value' => $user->created_at->format('M d, Y'), 'icon' => 'fa-calendar'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <span style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);">
                            <i class="fas <?php echo e($item['icon']); ?>"></i> <?php echo e($item['label']); ?>

                        </span>
                        <span style="font-size:13px;font-weight:700;"><?php echo e($item['value']); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div style="display:flex;gap:10px;margin-top:20px;">
                    <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="btn btn-primary" style="flex:1;">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <form action="<?php echo e(route('admin.users.toggle-status', $user->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn <?php echo e($user->is_active ? 'btn-danger' : 'btn-success'); ?>">
                            <i class="fas fa-<?php echo e($user->is_active ? 'user-slash' : 'user-check'); ?>"></i>
                            <?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>

                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="card">
                <div class="card-title" style="margin-bottom:16px;">Activity Statistics</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <?php $__currentLoopData = [
                        ['value' => $user->reservations->count(), 'label' => 'Total Bookings', 'color' => 'var(--primary)'],
                        ['value' => $user->reservations->where('status','completed')->count(), 'label' => 'Completed', 'color' => 'var(--success)'],
                        ['value' => $user->reservations->where('status','cancelled')->count(), 'label' => 'Cancelled', 'color' => 'var(--danger)'],
                        ['value' => $user->violations->count(), 'label' => 'Violations', 'color' => 'var(--warning)'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="padding:16px;background:rgba(108,99,255,0.03);border:1px solid rgba(108,99,255,0.08);border-radius:var(--radius-md);text-align:center;">
                        <div style="font-size:26px;font-weight:800;color:<?php echo e($stat['color']); ?>;"><?php echo e($stat['value']); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);font-weight:600;"><?php echo e($stat['label']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Recent Reservations -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Recent Reservations</div>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $user->reservations()->with('spot.zone')->latest()->take(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);margin-bottom:8px;">
                <div style="font-size:18px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;min-width:60px;">
                    <?php echo e($res->spot->zone->code); ?>-<?php echo e($res->spot->spot_number); ?>

                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:600;"><?php echo e($res->start_time->format('M d, H:i')); ?> — <?php echo e($res->end_time->format('H:i')); ?></div>
                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($res->duration); ?></div>
                </div>
                <span class="badge badge-<?php echo e($res->status_color); ?>"><?php echo e(ucfirst($res->status)); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:30px;color:var(--text-muted);">No reservations yet</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/users/show.blade.php ENDPATH**/ ?>