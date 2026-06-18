<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — UniPark</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/unipark.css')); ?>">

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">U</div>
            <div>
                <div class="sidebar-title">UniPark</div>
                <div class="sidebar-subtitle">Smart Campus Parking</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-label">Main Menu</span>

            <div class="nav-item">
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <span class="nav-icon-box nic-indigo"><i class="fas fa-th-large"></i></span>
                    Dashboard
                </a>
            </div>

            <div class="nav-item">
                <a href="<?php echo e(route('parking-map')); ?>" class="nav-link <?php echo e(request()->routeIs('parking-map') ? 'active' : ''); ?>">
                    <span class="nav-icon-box nic-cyan"><i class="fas fa-map-marked-alt"></i></span>
                    Parking Map
                </a>
            </div>

            <span class="nav-section-label">Reservations</span>

            <div class="nav-item">
                <a href="<?php echo e(route('reservations.create')); ?>" class="nav-link <?php echo e(request()->routeIs('reservations.create') ? 'active' : ''); ?>">
                    <span class="nav-icon-box nic-emerald"><i class="fas fa-plus-circle"></i></span>
                    New Reservation
                </a>
            </div>

            <div class="nav-item">
                <a href="<?php echo e(route('reservations.index')); ?>" class="nav-link <?php echo e(request()->routeIs('reservations.index') ? 'active' : ''); ?>">
                    <span class="nav-icon-box nic-sky"><i class="fas fa-calendar-check"></i></span>
                    My Reservations
                </a>
            </div>

            <div class="nav-item">
                <a href="<?php echo e(route('reservations.history')); ?>" class="nav-link <?php echo e(request()->routeIs('reservations.history') ? 'active' : ''); ?>">
                    <span class="nav-icon-box nic-violet"><i class="fas fa-history"></i></span>
                    History
                </a>
            </div>

            <span class="nav-section-label">Account</span>

            <div class="nav-item">
                <a href="<?php echo e(route('notifications.index')); ?>" class="nav-link <?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>">
                    <span class="nav-icon-box nic-amber"><i class="fas fa-bell"></i></span>
                    Notifications
                    <?php if(auth()->user()->unread_notifications_count > 0): ?>
                        <span class="nav-badge"><?php echo e(auth()->user()->unread_notifications_count); ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="nav-item">
                <a href="<?php echo e(route('profile')); ?>" class="nav-link <?php echo e(request()->routeIs('profile') ? 'active' : ''); ?>">
                    <span class="nav-icon-box nic-fuchsia"><i class="fas fa-user-circle"></i></span>
                    My Profile
                </a>
            </div>

            <?php if(auth()->user()->isAdmin()): ?>
            <span class="nav-section-label">Administration</span>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link">
                    <span class="nav-icon-box nic-rose"><i class="fas fa-shield-alt"></i></span>
                    Admin Panel
                </a>
            </div>
            <?php endif; ?>
        </nav>

        <div class="sidebar-footer">
            <a href="<?php echo e(route('profile')); ?>" class="user-card">
                <img src="<?php echo e(auth()->user()->avatar_url); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="user-avatar">
                <div class="user-info">
                    <div class="user-name"><?php echo e(auth()->user()->name); ?></div>
                    <div class="user-role"><?php echo e(ucfirst(auth()->user()->role)); ?></div>
                </div>
            </a>

            <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-8">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn w-full" style="border-radius: var(--radius-md); color: rgba(255,120,120,0.9); background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.2); font-family:'Cairo',sans-serif;">
                    <i class="fas fa-sign-out-alt"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Topbar -->
    <header class="topbar">
        <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <div>
            <div class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></div>
            <?php if (! empty(trim($__env->yieldContent('page-subtitle')))): ?>
            <div class="topbar-subtitle"><?php echo $__env->yieldContent('page-subtitle'); ?></div>
            <?php endif; ?>
        </div>

        <div class="topbar-actions">
            <!-- Notification Bell -->
            <div style="position:relative;">
                <button class="notif-bell" id="notifBell" onclick="toggleNotifDropdown(event)">
                    <i class="fas fa-bell bell-icon"></i>
                    <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
                </button>

                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-dropdown-header">
                        <span class="notif-dropdown-title">Notifications</span>
                        <a href="<?php echo e(route('notifications.read-all')); ?>" onclick="markAllRead(event)" style="font-size:12px; color:var(--primary); font-weight:600;">Mark all read</a>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="flex-center" style="padding:30px; color:var(--text-muted);">
                            <div class="spinner"></div>
                        </div>
                    </div>
                    <div class="notif-dropdown-footer">
                        <a href="<?php echo e(route('notifications.index')); ?>">View All Notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Avatar -->
            <a href="<?php echo e(route('profile')); ?>" style="display:flex; align-items:center; gap:10px; text-decoration:none; padding:6px 14px; border-radius:var(--radius-full); background:rgba(108,99,255,0.06); transition:var(--transition);"
               onmouseover="this.style.background='rgba(108,99,255,0.12)'" onmouseout="this.style.background='rgba(108,99,255,0.06)'">
                <img src="<?php echo e(auth()->user()->avatar_url); ?>" alt="" class="avatar avatar-sm">
                <span style="font-size:13px; font-weight:700; color:var(--text-primary); display:none;" class="d-sm-block"><?php echo e(auth()->user()->name); ?></span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-content">
            <!-- Flash Messages -->
            <?php if(session('success')): ?>
            <div class="alert alert-success animate-fade-up">
                <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                <div class="alert-content"><?php echo e(session('success')); ?></div>
                <button onclick="this.closest('.alert').remove()" style="background:none;border:none;cursor:pointer;color:inherit;margin-left:auto;padding:0;"><i class="fas fa-times"></i></button>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="alert alert-danger animate-fade-up">
                <span class="alert-icon"><i class="fas fa-times-circle"></i></span>
                <div class="alert-content"><?php echo e(session('error')); ?></div>
                <button onclick="this.closest('.alert').remove()" style="background:none;border:none;cursor:pointer;color:inherit;margin-left:auto;padding:0;"><i class="fas fa-times"></i></button>
            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="alert alert-danger animate-fade-up">
                <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                <div class="alert-content">
                    <div class="alert-title">Please fix the following errors:</div>
                    <ul style="margin:6px 0 0; padding-left:18px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <!-- Scripts -->
    <script>
        // ── Sidebar Toggle ──
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle  = document.getElementById('sidebarToggle');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            if (toggle) toggle.classList.toggle('is-open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
            const t = document.getElementById('sidebarToggle');
            if (t) t.classList.remove('is-open');
        }

        // ── Notification Dropdown ──
        function toggleNotifDropdown(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notifDropdown');
            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) {
                loadNotifications();
            }
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notifDropdown');
            if (dropdown && !dropdown.contains(e.target) && e.target.id !== 'notifBell') {
                dropdown.classList.remove('show');
            }
        });

        function loadNotifications() {
            fetch('<?php echo e(route("api.notifications.latest")); ?>')
                .then(r => r.json())
                .then(data => {
                    updateNotifBadge(data.unread_count);
                    renderNotifications(data.notifications);
                })
                .catch(() => {});
        }

        function updateNotifBadge(count) {
            const badge = document.getElementById('notifBadge');
            if (badge) {
                if (count > 0) {
                    badge.style.display = 'flex';
                    badge.textContent = count > 99 ? '99+' : count;
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        function renderNotifications(notifications) {
            const list = document.getElementById('notifList');
            if (!list) return;

            if (notifications.length === 0) {
                list.innerHTML = '<div style="padding:30px; text-align:center; color:var(--text-muted);"><i class="fas fa-bell-slash" style="font-size:32px; margin-bottom:10px; display:block; opacity:0.4;"></i><div style="font-size:14px; font-weight:500;">No notifications</div></div>';
                return;
            }

            const icons = { success: 'check-circle', reservation: 'calendar-check', warning: 'exclamation-triangle', error: 'times-circle', violation: 'exclamation-circle', info: 'bell' };

            list.innerHTML = notifications.map(n => `
                <div class="notif-item ${n.is_read ? '' : 'unread'}" onclick="markRead(${n.id}, this, '${n.action_url || ''}')">
                    <div class="notif-item-icon ${n.type}">
                        <i class="fas fa-${icons[n.type] || 'bell'}"></i>
                    </div>
                    <div class="notif-item-content">
                        <div class="notif-item-title">${n.title}</div>
                        <div class="notif-item-message">${n.message}</div>
                        <div class="notif-item-time">${n.time_ago}</div>
                    </div>
                    ${!n.is_read ? '<div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:4px;"></div>' : ''}
                </div>
            `).join('');
        }

        function markRead(id, el, url) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Content-Type': 'application/json' }
            }).then(() => {
                el.classList.remove('unread');
                const dot = el.querySelector('[style*="border-radius:50%"]');
                if (dot) dot.remove();
                loadNotifCount();
                if (url) window.location.href = url;
            });
        }

        function markAllRead(e) {
            e.preventDefault();
            fetch('<?php echo e(route("notifications.read-all")); ?>', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Content-Type': 'application/json' }
            }).then(() => {
                document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
                updateNotifBadge(0);
                document.querySelectorAll('.nav-badge').forEach(el => el.remove());
            });
        }

        function loadNotifCount() {
            fetch('<?php echo e(route("api.notifications.count")); ?>')
                .then(r => r.json())
                .then(data => updateNotifBadge(data.count));
        }

        // Load count on page load
        loadNotifCount();

        // Auto-refresh count every 30s
        setInterval(loadNotifCount, 30000);

        // ── Auto dismiss alerts ──
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\xamp\htdocs\unipark\resources\views/layouts/app.blade.php ENDPATH**/ ?>