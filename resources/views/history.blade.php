<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Parking History</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Parking History</span>
      <div class="topbar-actions">
        <button class="topbar-btn" style="position:relative">🔔<div class="notif-dot"></div></button>
        <div class="avatar">RA</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Dashboard</span> / Parking History</div>
        <div class="page-header-row">
          <div><h1>Parking History</h1><p>Your complete parking activity and reservation log</p></div>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm" onclick="showToast('Exporting','History CSV downloaded','info')">📤 Export CSV</button>
            <button class="btn btn-primary btn-sm" onclick="nav('reserve.html')">+ New Reservation</button>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="stats-grid animate-fade-up">
        <div class="stat-card teal">
          <div class="stat-icon">📋</div>
          <div class="stat-value">24</div>
          <div class="stat-label">Total Reservations</div>
          <div class="stat-change up">All time</div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon">✅</div>
          <div class="stat-value">22</div>
          <div class="stat-label">Completed</div>
          <div class="stat-change up">91.7% rate</div>
        </div>
        <div class="stat-card gold">
          <div class="stat-icon">⏱️</div>
          <div class="stat-value">2h 48m</div>
          <div class="stat-label">Average Duration</div>
          <div class="stat-change up">↑ 12 min vs last month</div>
        </div>
        <div class="stat-card blue">
          <div class="stat-icon">🅿️</div>
          <div class="stat-value">Zone B</div>
          <div class="stat-label">Most Used Zone</div>
          <div class="stat-change up">11 visits</div>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar animate-fade-up animate-delay-1">
        <div class="search-box">
          <span>🔍</span>
          <input type="text" id="histSearch" placeholder="Search by spot, zone..." oninput="filterHistory()" />
        </div>
        <select class="filter-select" id="histStatus" onchange="filterHistory()">
          <option value="all">All Status</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="no-show">No-Show</option>
        </select>
        <select class="filter-select" id="histZone" onchange="filterHistory()">
          <option value="all">All Zones</option>
          <option value="A">Zone A</option>
          <option value="B">Zone B</option>
          <option value="C">Zone C</option>
          <option value="D">Zone D</option>
        </select>
        <input type="month" class="filter-select" id="histMonth" onchange="filterHistory()" style="padding:8px 12px" />
      </div>

      <div class="card animate-fade-up animate-delay-2">
        <div class="table-wrapper">
          <table id="histTable">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>Date & Time</th>
                <th>Spot</th>
                <th>Zone</th>
                <th>Duration</th>
                <th>Entry</th>
                <th>Exit</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="histBody"></tbody>
          </table>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;flex-wrap:wrap;gap:12px;border-top:1px solid rgba(255,255,255,.05)">
          <div style="font-size:.82rem;color:var(--gray-500)">Showing <strong style="color:var(--white)" id="histCount">24</strong> records</div>
          <div style="display:flex;gap:6px">
            <button class="btn btn-ghost btn-sm">← Prev</button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-ghost btn-sm">2</button>
            <button class="btn btn-ghost btn-sm">Next →</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('student', 'history');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

const HISTORY = [
  { id:'RES-2026-0841', date:'Mar 8, 2026', spot:'B-14', zone:'B', start:'9:12 AM', end:'11:30 AM', duration:'2h 18m', status:'active' },
  { id:'RES-2026-0838', date:'Mar 7, 2026', spot:'A-03', zone:'A', start:'8:45 AM', end:'12:00 PM', duration:'3h 15m', status:'completed' },
  { id:'RES-2026-0831', date:'Mar 6, 2026', spot:'C-11', zone:'C', start:'9:00 AM', end:'1:00 PM',  duration:'4h 00m', status:'completed' },
  { id:'RES-2026-0824', date:'Mar 5, 2026', spot:'B-07', zone:'B', start:'8:30 AM', end:'11:45 AM', duration:'3h 15m', status:'completed' },
  { id:'RES-2026-0815', date:'Mar 4, 2026', spot:'B-08', zone:'B', start:'10:00 AM', end:'—',       duration:'—',      status:'cancelled' },
  { id:'RES-2026-0808', date:'Mar 3, 2026', spot:'A-12', zone:'A', start:'8:00 AM', end:'2:00 PM',  duration:'6h 00m', status:'completed' },
  { id:'RES-2026-0799', date:'Mar 2, 2026', spot:'B-03', zone:'B', start:'9:30 AM', end:'12:30 PM', duration:'3h 00m', status:'completed' },
  { id:'RES-2026-0792', date:'Mar 1, 2026', spot:'D-02', zone:'D', start:'11:00 AM', end:'2:00 PM', duration:'3h 00m', status:'completed' },
  { id:'RES-2026-0784', date:'Feb 28, 2026', spot:'A-07', zone:'A', start:'8:45 AM', end:'—',       duration:'—',      status:'no-show' },
  { id:'RES-2026-0776', date:'Feb 27, 2026', spot:'B-11', zone:'B', start:'9:00 AM', end:'11:00 AM',duration:'2h 00m', status:'completed' },
];

const STATUS_BADGE = {
  active:    '<span class="badge badge-active">● Active</span>',
  completed: '<span class="badge badge-available">✅ Completed</span>',
  cancelled: '<span class="badge badge-cancelled">✕ Cancelled</span>',
  'no-show': '<span class="badge badge-occupied">⚠ No-Show</span>',
};

function renderHistory(list) {
  const tbody = document.getElementById('histBody');
  tbody.innerHTML = list.map(h => `
    <tr>
      <td style="font-family:'DM Mono',monospace;color:var(--teal-400);font-size:.8rem">#${h.id}</td>
      <td style="font-size:.85rem">${h.date}</td>
      <td><strong style="font-family:'Syne',sans-serif">${h.spot}</strong></td>
      <td><span class="badge badge-reserved">Zone ${h.zone}</span></td>
      <td style="font-size:.85rem">${h.duration}</td>
      <td style="font-size:.82rem;color:var(--gray-300)">${h.start}</td>
      <td style="font-size:.82rem;color:var(--gray-300)">${h.end}</td>
      <td>${STATUS_BADGE[h.status]||''}</td>
      <td>
        <div style="display:flex;gap:6px">
          ${h.status==='completed'?`<button class="btn btn-ghost btn-sm" onclick="nav('reserve.html')">🔄 Rebook</button>`:''}
          <button class="btn btn-ghost btn-sm" onclick="showToast('Details','Reservation details copied','info')">👁️</button>
        </div>
      </td>
    </tr>
  `).join('');
  document.getElementById('histCount').textContent = list.length;
}

function filterHistory() {
  const q = document.getElementById('histSearch').value.toLowerCase();
  const st = document.getElementById('histStatus').value;
  const zn = document.getElementById('histZone').value;
  const filtered = HISTORY.filter(h =>
    (q === '' || h.spot.toLowerCase().includes(q) || h.id.toLowerCase().includes(q) || h.date.toLowerCase().includes(q)) &&
    (st === 'all' || h.status === st) &&
    (zn === 'all' || h.zone === zn)
  );
  renderHistory(filtered);
}

renderHistory(HISTORY);
</script>
</body>
</html>
