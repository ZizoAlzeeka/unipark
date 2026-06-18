<?php $__env->startSection('title', 'Notifications'); ?>
<?php $__env->startSection('page-title', 'Notifications'); ?>
<?php $__env->startSection('page-subtitle', 'Stay updated with your parking activity'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">
    <div class="flex-between mb-20" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        <?php if($unreadCount > 0): ?>
        <form action="<?php echo e(route('notifications.read-all')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-check-double"></i> Mark All as Read
            </button>
        </form>
        <?php endif; ?>
    </div>

    <?php if($unreadCount > 0): ?>
    <div class="alert alert-info mb-20">
        <span class="alert-icon"><i class="fas fa-bell"></i></span>
        <div>You have <strong><?php echo e($unreadCount); ?></strong> unread notification(s).</div>
    </div>
    <?php endif; ?>

    <div class="card" style="padding:0;">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="display:flex;align-items:flex-start;gap:16px;padding:18px 24px;border-bottom:1px solid rgba(108,99,255,0.06);<?php echo e(!$notif->is_read ? 'background:rgba(108,99,255,0.03);' : ''); ?>transition:var(--transition);" onmouseover="this.style.background='rgba(108,99,255,0.03)'" onmouseout="this.style.background='<?php echo e(!$notif->is_read ? 'rgba(108,99,255,0.03)' : 'transparent'); ?>'">
            <div class="notif-item-icon <?php echo e($notif->type); ?>" style="width:46px;height:46px;border-radius:var(--radius-md);flex-shrink:0;font-size:17px;">
                <i class="fas fa-<?php echo e($notif->type_icon); ?>"></i>
            </div>

            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                    <div>
                        <div style="font-size:15px;font-weight:<?php echo e(!$notif->is_read ? '700' : '600'); ?>;color:var(--text-primary);"><?php echo e($notif->title); ?></div>
                        <div style="font-size:14px;color:var(--text-secondary);margin-top:4px;line-height:1.5;"><?php echo e($notif->message); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);margin-top:6px;font-weight:500;">
                            <i class="fas fa-clock" style="margin-right:4px;"></i><?php echo e($notif->created_at->diffForHumans()); ?> &bull; <?php echo e($notif->created_at->format('M d, Y H:i')); ?>

                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                        <?php if(!$notif->is_read): ?>
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--primary);"></div>
                        <?php endif; ?>
                        <form action="<?php echo e(route('notifications.destroy', $notif->id)); ?>" method="POST" onsubmit="return confirm('Delete this notification?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm" style="background:none;border:none;cursor:pointer;color:var(--text-muted);padding:6px;border-radius:var(--radius-sm);transition:var(--transition);" onmouseover="this.style.color='var(--danger)';this.style.background='rgba(239,71,111,0.08)'" onmouseout="this.style.color='var(--text-muted)';this.style.background='none'">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <?php if($notif->action_url): ?>
                <div style="margin-top:10px;">
                    <a href="<?php echo e($notif->action_url); ?>" class="btn btn-ghost btn-sm" style="font-size:12px;">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="padding:80px;text-align:center;color:var(--text-muted);">
            <i class="fas fa-bell-slash" style="font-size:52px;margin-bottom:16px;display:block;opacity:0.2;"></i>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px;">No notifications</div>
            <p>You're all caught up! Notifications about your parking activity will appear here.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if($notifications->hasPages()): ?>
    <div class="mt-20">
        <div class="pagination">
            <?php if($notifications->onFirstPage()): ?>
                <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
            <?php else: ?>
                <a href="<?php echo e($notifications->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            <?php endif; ?>
            <?php $__currentLoopData = $notifications->getUrlRange(1, $notifications->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $notifications->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($notifications->hasMorePages()): ?>
                <a href="<?php echo e($notifications->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            <?php else: ?>
                <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/user/notifications.blade.php ENDPATH**/ ?>