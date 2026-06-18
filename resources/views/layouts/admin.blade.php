<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — UniPark Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/unipark.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Admin Sidebar -->
    <aside class="sidebar sidebar-new-theme" id="sidebar">
        <div class="sidebar-brand">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQoiJgzaly4WpjoeM4qy9s3llJQ3fCBGVk7gQ&s" alt="University Logo" class="sidebar-logo-img" style="width: 44px; height: 44px; border-radius: var(--radius-md); object-fit: contain; background: #fff; padding: 2px; border: 2px solid rgba(255,255,255,0.2); box-shadow: 0 4px 16px rgba(79,70,229,0.4); flex-shrink: 0;">
            <div>
                <div class="sidebar-title">UniPark</div>
                <div class="sidebar-subtitle">Smart Campus Parking</div>
            </div>
            <button class="d-md-none" onclick="closeSidebar()" style="background:none;border:none;color:#fff;font-size:20px;margin-left:auto;cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-label">Main Menu</span>

            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-indigo"><i class="fas fa-th-large"></i></span>
                    Dashboard
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('parking-map') }}" class="nav-link {{ request()->routeIs('parking-map') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-cyan"><i class="fas fa-map-marked-alt"></i></span>
                    Parking Map
                </a>
            </div>

            <span class="nav-section-label">Reservations</span>

            <div class="nav-item">
                <a href="{{ route('reservations.create') }}" class="nav-link {{ request()->routeIs('reservations.create') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-emerald"><i class="fas fa-plus-circle"></i></span>
                    New Reservation
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.index') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-sky"><i class="fas fa-calendar-check"></i></span>
                    My Reservations
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('reservations.history') }}" class="nav-link {{ request()->routeIs('reservations.history') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-violet"><i class="fas fa-history"></i></span>
                    History
                </a>
            </div>

            @if(auth()->user()->isAdmin())
            <span class="nav-section-label">Administration</span>

            <div class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-blue"><i class="fas fa-users"></i></span>
                    Users
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-violet"><i class="fas fa-shield-alt"></i></span>
                    Permissions
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.zones.index') }}" class="nav-link {{ request()->routeIs('admin.zones.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-cyan"><i class="fas fa-map"></i></span>
                    Parking Zones
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.spots.index') }}" class="nav-link {{ request()->routeIs('admin.spots.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-emerald"><i class="fas fa-parking"></i></span>
                    Parking Spots
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.reservations.index') }}" class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-sky"><i class="fas fa-calendar-alt"></i></span>
                    All Reservations
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.entry-exit.index') }}" class="nav-link {{ request()->routeIs('admin.entry-exit.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-orange"><i class="fas fa-exchange-alt"></i></span>
                    Entry / Exit Logs
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.violations.index') }}" class="nav-link {{ request()->routeIs('admin.violations.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-rose"><i class="fas fa-exclamation-triangle"></i></span>
                    Violations
                    @php $pendingViolations = \App\Models\Violation::where('status','pending')->count(); @endphp
                    @if($pendingViolations > 0)
                        <span class="nav-badge">{{ $pendingViolations }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-pink"><i class="fas fa-chart-bar"></i></span>
                    Reports
                </a>
            </div>
            @endif

            <span class="nav-section-label">Account</span>

            <div class="nav-item">
                <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-amber"><i class="fas fa-bell"></i></span>
                    Notifications
                    @if(auth()->user()->unread_notifications_count > 0)
                        <span class="nav-badge">{{ auth()->user()->unread_notifications_count }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <span class="nav-icon-box nic-fuchsia"><i class="fas fa-user-circle"></i></span>
                    My Profile
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile') }}" class="user-card">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="mt-8">
                @csrf
                <button type="submit" class="btn w-full" style="border-radius:var(--radius-md); color:rgba(255,120,120,0.9); background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.2); font-family:'Cairo',sans-serif;">
                    <i class="fas fa-sign-out-alt"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Topbar -->
    <header class="topbar topbar-new-theme">
        <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-title-wrapper">
            <div class="topbar-title">@yield('page-title', 'Admin Dashboard')</div>
            @hasSection('page-subtitle')
            <div class="topbar-subtitle">@yield('page-subtitle')</div>
            @endif
        </div>

        <div class="topbar-actions">
            <div style="position:relative;">
                <button class="notif-bell" id="notifBell" onclick="toggleNotifDropdown(event)">
                    <i class="fas fa-bell bell-icon"></i>
                    <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
                </button>

                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-dropdown-header">
                        <span class="notif-dropdown-title">Notifications</span>
                        <a href="{{ route('notifications.read-all') }}" onclick="markAllRead(event)" style="font-size:12px; color:var(--primary); font-weight:600;">Mark all read</a>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="flex-center" style="padding:30px;"><div class="spinner"></div></div>
                    </div>
                    <div class="notif-dropdown-footer">
                        <a href="{{ route('notifications.index') }}">View All Notifications</a>
                    </div>
                </div>
            </div>

            <span class="badge badge-danger" style="font-size:12px; padding:6px 14px;">
                <i class="fas fa-shield-alt" style="margin-right:4px;"></i> Admin
            </span>

            <a href="{{ route('profile') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;padding:6px 12px;border-radius:var(--radius-full);background:rgba(239,71,111,0.06);transition:var(--transition);">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="avatar avatar-sm">
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-content">
            @if(session('success'))
            <div class="alert alert-success animate-fade-up">
                <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                <div class="alert-content">{{ session('success') }}</div>
                <button onclick="this.closest('.alert').remove()" style="background:none;border:none;cursor:pointer;color:inherit;margin-left:auto;padding:0;"><i class="fas fa-times"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger animate-fade-up">
                <span class="alert-icon"><i class="fas fa-times-circle"></i></span>
                <div class="alert-content">{{ session('error') }}</div>
                <button onclick="this.closest('.alert').remove()" style="background:none;border:none;cursor:pointer;color:inherit;margin-left:auto;padding:0;"><i class="fas fa-times"></i></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger animate-fade-up">
                <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                <div class="alert-content">
                    <div class="alert-title">Please fix the following errors:</div>
                    <ul style="margin:6px 0 0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
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

        function toggleNotifDropdown(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notifDropdown');
            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) loadNotifications();
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notifDropdown');
            if (dropdown && !dropdown.contains(e.target) && e.target.id !== 'notifBell') {
                dropdown.classList.remove('show');
            }
        });

        function loadNotifications() {
            fetch('{{ route("api.notifications.latest") }}')
                .then(r => r.json())
                .then(data => {
                    updateNotifBadge(data.unread_count);
                    renderNotifications(data.notifications);
                });
        }

        function updateNotifBadge(count) {
            const badge = document.getElementById('notifBadge');
            if (badge) {
                badge.style.display = count > 0 ? 'flex' : 'none';
                badge.textContent = count > 99 ? '99+' : count;
            }
        }

        function renderNotifications(notifications) {
            const list = document.getElementById('notifList');
            if (!list) return;
            if (!notifications.length) {
                list.innerHTML = '<div style="padding:30px;text-align:center;color:var(--text-muted);"><i class="fas fa-bell-slash" style="font-size:32px;margin-bottom:10px;display:block;opacity:0.4;"></i><div style="font-size:14px;font-weight:500;">No notifications</div></div>';
                return;
            }
            const icons = { success:'check-circle', reservation:'calendar-check', warning:'exclamation-triangle', error:'times-circle', violation:'exclamation-circle', info:'bell' };
            list.innerHTML = notifications.map(n => `
                <div class="notif-item ${n.is_read ? '' : 'unread'}" onclick="markRead(${n.id},this,'${n.action_url||''}')">
                    <div class="notif-item-icon ${n.type}"><i class="fas fa-${icons[n.type]||'bell'}"></i></div>
                    <div class="notif-item-content">
                        <div class="notif-item-title">${n.title}</div>
                        <div class="notif-item-message">${n.message}</div>
                        <div class="notif-item-time">${n.time_ago}</div>
                    </div>
                    ${!n.is_read ? '<div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:4px;"></div>' : ''}
                </div>`).join('');
        }

        function markRead(id, el, url) {
            fetch(`/notifications/${id}/read`, { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'} })
                .then(() => { el.classList.remove('unread'); loadNotifCount(); if (url) window.location.href = url; });
        }

        function markAllRead(e) {
            e.preventDefault();
            fetch('{{ route("notifications.read-all") }}', { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'} })
                .then(() => { document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread')); updateNotifBadge(0); });
        }

        function loadNotifCount() {
            fetch('{{ route("api.notifications.count") }}').then(r => r.json()).then(data => updateNotifBadge(data.count));
        }

        loadNotifCount();
        setInterval(loadNotifCount, 30000);

        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);

        // Confirm delete dialogs
        document.querySelectorAll('[data-confirm]').forEach(el => {
            el.addEventListener('click', function(e) {
                if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
