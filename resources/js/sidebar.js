// ===== UniPark — Sidebar Renderer =====
function renderSidebar(role, activePage) {

  const studentNav = `
    <div class="nav-section">
      <div class="nav-section-label">Main</div>
      <div class="nav-item ${activePage==='dashboard'?'active':''}" onclick="nav('dashboard.html')">
        <div class="nav-icon">🏠</div><span class="nav-label">Dashboard</span>
      </div>
      <div class="nav-item ${activePage==='reserve'?'active':''}" onclick="nav('reserve.html')">
        <div class="nav-icon">🅿️</div><span class="nav-label">Reserve a Spot</span>
        <span class="nav-badge amber">New</span>
      </div>
      <div class="nav-item ${activePage==='my-reservations'?'active':''}" onclick="nav('my-reservations.html')">
        <div class="nav-icon">📋</div><span class="nav-label">My Reservations</span>
        <span class="nav-badge" id="reserveBadge">2</span>
      </div>
    </div>
    <div class="nav-section">
      <div class="nav-section-label">Info</div>
      <div class="nav-item ${activePage==='parking-map'?'active':''}" onclick="nav('parking-map.html')">
        <div class="nav-icon">🗺️</div><span class="nav-label">Parking Map</span>
      </div>
      <div class="nav-item ${activePage==='history'?'active':''}" onclick="nav('history.html')">
        <div class="nav-icon">📜</div><span class="nav-label">History</span>
      </div>
      <div class="nav-item ${activePage==='violations'?'active':''}" onclick="nav('violations.html')">
        <div class="nav-icon">⚠️</div><span class="nav-label">Violations</span>
      </div>
    </div>
    <div class="nav-section">
      <div class="nav-section-label">Account</div>
      <div class="nav-item ${activePage==='profile'?'active':''}" onclick="nav('profile.html')">
        <div class="nav-icon">👤</div><span class="nav-label">Profile</span>
      </div>
      <div class="nav-item ${activePage==='notifications'?'active':''}" onclick="nav('notifications.html')">
        <div class="nav-icon">🔔</div><span class="nav-label">Notifications</span>
        <span class="nav-badge red" id="notifBadge">3</span>
      </div>
      <div class="nav-item" onclick="confirmLogout()">
        <div class="nav-icon">🚪</div><span class="nav-label">Sign Out</span>
      </div>
    </div>
  `;

  const adminNav = `
    <div class="nav-section">
      <div class="nav-section-label">Overview</div>
      <div class="nav-item ${activePage==='admin-dashboard'?'active':''}" onclick="nav('admin-dashboard.html')">
        <div class="nav-icon">📊</div><span class="nav-label">Dashboard</span>
      </div>
      <div class="nav-item ${activePage==='parking-map'?'active':''}" onclick="nav('parking-map.html')">
        <div class="nav-icon">🗺️</div><span class="nav-label">Live Map</span>
      </div>
    </div>
    <div class="nav-section">
      <div class="nav-section-label">Management</div>
      <div class="nav-item ${activePage==='users'?'active':''}" onclick="nav('users.html')">
        <div class="nav-icon">👥</div><span class="nav-label">Users</span>
        <span class="nav-badge">156</span>
      </div>
      <div class="nav-item ${activePage==='parking-zones'?'active':''}" onclick="nav('parking-zones.html')">
        <div class="nav-icon">🏗️</div><span class="nav-label">Parking Zones</span>
      </div>
      <div class="nav-item ${activePage==='reservations'?'active':''}" onclick="nav('reservations.html')">
        <div class="nav-icon">📋</div><span class="nav-label">All Reservations</span>
      </div>
      <div class="nav-item ${activePage==='entry-exit'?'active':''}" onclick="nav('entry-exit.html')">
        <div class="nav-icon">🚦</div><span class="nav-label">Entry/Exit Log</span>
      </div>
      <div class="nav-item ${activePage==='violations'?'active':''}" onclick="nav('violations.html')">
        <div class="nav-icon">⚠️</div><span class="nav-label">Violations</span>
        <span class="nav-badge red">4</span>
      </div>
    </div>
    <div class="nav-section">
      <div class="nav-section-label">Analytics</div>
      <div class="nav-item ${activePage==='reports'?'active':''}" onclick="nav('reports.html')">
        <div class="nav-icon">📈</div><span class="nav-label">Reports</span>
      </div>
      <div class="nav-item" onclick="confirmLogout()">
        <div class="nav-icon">🚪</div><span class="nav-label">Sign Out</span>
      </div>
    </div>
  `;

  const user = role === 'admin'
    ? { name: 'Dr. Admin', role: 'System Administrator', initials: 'AD' }
    : { name: 'Renad Alshammari', role: role === 'staff' ? 'Staff Member' : 'Student', initials: 'RA' };

  return `
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-logo">
        <img src="https://static.wixstatic.com/media/c71a6e_a40141bf9c9e40fdbfa579f1eb5d48a7~mv2.png"
             alt="UniPark Logo" class="sidebar-logo-img">
        <div>
          <div class="sidebar-logo-text">Uni<span>Park</span></div>
          <div class="sidebar-logo-sub">Smart Campus Parking</div>
        </div>
      </div>
      <nav class="sidebar-nav"style="background: var(--sidebar-bg);">
        ${role === 'admin' ? adminNav : studentNav}
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user" onclick="nav('profile.html')">
          <div class="avatar">${user.initials}</div>
          <div>
            <div class="user-name">${user.name}</div>
            <div class="user-role">${user.role}</div>
          </div>
          <span class="settings-icon">⚙️</span>
        </div>
      </div>
    </aside>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
  `;
}

function nav(page) { window.location.href = page; }

function confirmLogout() {
  if (confirm('Are you sure you want to sign out?')) window.location.href = 'index.html';
}

function initSidebar() {
  const wrapper = document.getElementById('pageWrapper');
  if (wrapper) {
    wrapper.style.marginLeft = window.innerWidth > 1024 ? 'var(--sidebar-w)' : '0';
    window.addEventListener('resize', () => {
      wrapper.style.marginLeft = window.innerWidth > 1024 ? 'var(--sidebar-w)' : '0';
    });
  }
}