

<?php $__env->startSection('title', 'User Management'); ?>
<?php $__env->startSection('page-title', 'User Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage all registered users'); ?>

<?php $__env->startSection('content'); ?>
<div>

    <!-- Stats -->
    <div class="grid grid-4 mb-24">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['total']); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="stat-card secondary">
            <div class="stat-icon secondary"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['students']); ?></div>
                <div class="stat-label">Students</div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['staff']); ?></div>
                <div class="stat-label">Staff</div>
            </div>
        </div>
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fas fa-user-shield"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['admins']); ?></div>
                <div class="stat-label">Admins</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Users</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px;"><?php echo e($users->total()); ?> registered users</div>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                    <div class="input-group" style="width:240px;">
                        <span class="input-icon"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo e(request('search')); ?>" style="padding:8px 8px 8px 40px;border-radius:var(--radius-full);">
                    </div>
                    <select name="role" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="student" <?php echo e(request('role') === 'student' ? 'selected' : ''); ?>>Student</option>
                        <option value="staff" <?php echo e(request('role') === 'staff' ? 'selected' : ''); ?>>Staff</option>
                        <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                    </select>
                </form>
                <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add User
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>University ID</th>
                        <th>Role</th>
                        <th>Vehicle</th>
                        <th>Reservations</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <img src="<?php echo e($user->avatar_url); ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                                <div>
                                    <div style="font-weight:700;font-size:14px;"><?php echo e($user->name); ?></div>
                                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($user->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;font-weight:600;"><?php echo e($user->university_id ?? '—'); ?></td>
                        <td>
                            <span class="badge <?php echo e($user->role === 'admin' ? 'badge-danger' : ($user->role === 'staff' ? 'badge-info' : 'badge-primary')); ?>">
                                <?php echo e(ucfirst($user->role)); ?>

                            </span>
                        </td>
                        <td>
                            <div style="font-size:13px;font-weight:600;"><?php echo e($user->vehicle_plate ?? '—'); ?></div>
                            <?php if($user->vehicle_model): ?>
                            <div style="font-size:11px;color:var(--text-muted);"><?php echo e($user->vehicle_model); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-size:16px;font-weight:800;color:var(--primary);"><?php echo e($user->reservations_count); ?></span>
                        </td>
                        <td>
                            <span class="badge <?php echo e($user->is_active ? 'badge-success' : 'badge-secondary'); ?>">
                                <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td style="font-size:13px;color:var(--text-muted);"><?php echo e($user->created_at->format('M d, Y')); ?></td>
                        <td>
                            <div class="action-btn-group">
                                <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="btn-action btn-action-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.users.toggle-status', $user->id)); ?>" method="POST" style="margin:0;padding:0;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action" title="<?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>" style="background:<?php echo e($user->is_active ? '#f59e0b' : '#10b981'); ?>;">
                                        <i class="fas fa-<?php echo e($user->is_active ? 'user-slash' : 'user-check'); ?>"></i>
                                    </button>
                                </form>
                                <?php if(auth()->id() !== $user->id): ?>
                                <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST" style="margin:0;padding:0;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-action btn-action-delete" data-confirm="Delete user <?php echo e($user->name); ?>? This cannot be undone.">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="text-align:center;padding:50px;color:var(--text-muted);">No users found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                <?php if($users->onFirstPage()): ?>
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                <?php else: ?>
                    <a href="<?php echo e($users->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                <?php endif; ?>
                <?php $__currentLoopData = $users->getUrlRange(1, $users->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $users->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($users->hasMorePages()): ?>
                    <a href="<?php echo e($users->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                <?php else: ?>
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) e.preventDefault();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\MAMP\htdocs\unipark\resources\views/admin/users/index.blade.php ENDPATH**/ ?>