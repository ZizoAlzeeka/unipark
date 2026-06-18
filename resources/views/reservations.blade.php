<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — All Reservations</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">All Reservations</span>
      <div class="topbar-search"><span style="color:var(--gray-500)">🔍</span><input type="text" placeholder="Search reservations..." id="resSearch" oninput="filterReservations()" /></div>
      <div class="topbar-actions">
        <button class="topbar-btn" style="position:relative">🔔<div class="notif-dot"></div></button>
        <div class="avatar" style="background:linear-gradient(135deg,var(--amber-500),#ffa726);color:#050d1a">AD</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Admin Dashboard</span> / All Reservations</div>
        <div class="page-header-row">
          <div><h1>All Reservations</h1><p>Manage and monitor all campus parking reservations</p></div>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm" onclick="showToast('Exporting','Reservations CSV downloaded','info')">📤 Export CSV</button>
            <button class="btn btn-primary btn-sm" onclick="openModal('addResModal')">+ Add Reservation</button>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="stats-grid animate-fade-up">
        <div class="stat-card teal"><div class="stat-icon">📋</div><div class="stat-value">37</div><div class="stat-label">Today's Reservations</div><div class="stat-change up">↑ 5 vs yesterday</div></div>
        <div class="stat-card green"><div class="stat-icon">✅</div><div class="stat-value">14</div><div class="stat-label">Active Now</div><div class="stat-change up">Live</div></div>
        <div class="stat-card gold"><div class="stat-icon">📅</div><div class="stat-value">23</div><div class="stat-label">Upcoming Today</div><div class="stat-change up">Scheduled</div></div>
        <div class="stat-card red"><div class="stat-icon">✕</div><div class="stat-value">3</div><div class="stat-label">Cancelled Today</div><div class="stat-change">8% rate</div></div>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar animate-fade-up animate-delay-1">
        <select class="filter-select" id="resStatusFilter" onchange="filterReservations()">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="upcoming">Upcoming</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <select class="filter-select" id="resZoneFilter" onchange="filterReservations()">
          <option value="all">All Zones</option>
          <option value="A">Zone A</option>
          <option value="B">Zone B</option>
          <option value="C">Zone C</option>
          <option value="D">Zone D</option>
        </select>
        <select class="filter-select" id="resTypeFilter" onchange="filterReservations()">
          <option value="all">All Roles</option>
          <option value="student">Student</option>
          <option value="staff">Staff</option>
        </select>
        <input type="date" class="filter-select" id="resDateFilter" onchange="filterReservations()" style="padding:8px 12px" />
      </div>

      <div class="card animate-fade-up animate-delay-2">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Role</th>
                <th>Spot</th>
                <th>Zone</th>
                <th>Date</th>
                <th>Start</th>
                <th>End</th>
                <th>Plate</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="resBody"></tbody>
          </table>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-top:1px solid rgba(255,255,255,.05);flex-wrap:wrap;gap:12px">
          <div style="font-size:.82rem;color:var(--gray-500)">Showing <strong style="color:var(--white)" id="resCount">10</strong> of <strong style="color:var(--white)">37</strong> reservations</div>
          <div style="display:flex;gap:6px">
            <button class="btn btn-ghost btn-sm">← Prev</button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-ghost btn-sm">2</button>
            <button class="btn btn-ghost btn-sm">3</button>
            <button class="btn btn-ghost btn-sm">Next →</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Reservation Modal -->
<div class="modal-overlay" id="addResModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Add Reservation</h3><p style="font-size:.85rem;color:var(--gray-400);margin-top:4px">Create a manual reservation</p></div>
      <button class="modal-close" onclick="closeModal('addResModal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">User</label>
      <input type="text" class="form-control" placeholder="Search by name or ID..." />
    </div>
    <div class="form-row" style="margin-bottom:16px">
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Zone</label>
        <select class="form-control"><option>Zone A</option><option>Zone B</option><option>Zone C</option><option>Zone D</option></select>
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Spot Number</label>
        <input type="text" class="form-control" placeholder="e.g. B-14" />
      </div>
    </div>
    <div class="form-row" style="margin-bottom:16px">
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Date</label>
        <input type="date" class="form-control" />
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Vehicle Plate</label>
        <input type="text" class="form-control" placeholder="ABC-1234" />
      </div>
    </div>
    <div class="form-row" style="margin-bottom:0">
      <div class="form-group">
        <label class="form-label">Start Time</label>
        <select class="form-control"><option>8:00 AM</option><option>9:00 AM</option><option>10:00 AM</option><option>11:00 AM</option><option>12:00 PM</option></select>
      </div>
      <div class="form-group">
        <label class="form-label">End Time</label>
        <select class="form-control"><option>10:00 AM</option><option>11:00 AM</option><option>12:00 PM</option><option>1:00 PM</option><option>2:00 PM</option></select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('addResModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Reservation Created','Booking confirmed successfully','success');closeModal('addResModal')">Create Reservation</button>
    </div>
  </div>
</div>

<!-- View Reservation Modal -->
<div class="modal-overlay" id="viewResModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Reservation Details</h3><p id="viewResId" style="font-size:.82rem;color:var(--gray-400);font-family:'DM Mono',monospace;margin-top:4px"></p></div>
      <button class="modal-close" onclick="closeModal('viewResModal')">✕</button>
    </div>
    <div id="viewResContent"></div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('viewResModal')">Close</button>
      <button class="btn btn-danger btn-sm" onclick="showToast('Cancelled','Reservation cancelled','info');closeModal('viewResModal')">Cancel Reservation</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('admin', 'reservations');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

const RESERVATIONS = [
  { id:'RES-2026-0841', user:'Renad Alshammari', role:'student', spot:'B-14', zone:'B', date:'Mar 8, 2026', start:'9:12 AM', end:'11:30 AM', plate:'ABC-1234', status:'active' },
  { id:'RES-2026-0840', user:'Amjad Moubarak',    role:'student', spot:'A-05', zone:'A', date:'Mar 8, 2026', start:'8:00 AM', end:'12:00 PM', plate:'DEF-5678', status:'active' },
  { id:'RES-2026-0839', user:'Dr. Sara Al-Amin',  role:'staff',   spot:'C-03', zone:'C', date:'Mar 8, 2026', start:'8:30 AM', end:'5:00 PM',  plate:'GHI-9012', status:'active' },
  { id:'RES-2026-0838', user:'Raghad Hamad',       role:'student', spot:'B-22', zone:'B', date:'Mar 8, 2026', start:'10:00 AM',end:'2:00 PM',  plate:'JKL-3456', status:'upcoming' },
  { id:'RES-2026-0837', user:'Omar Al-Rashid',     role:'student', spot:'A-11', zone:'A', date:'Mar 8, 2026', start:'11:00 AM',end:'3:00 PM',  plate:'MNO-7890', status:'upcoming' },
  { id:'RES-2026-0836', user:'Fatima Al-Sayed',    role:'staff',   spot:'C-08', zone:'C', date:'Mar 8, 2026', start:'9:00 AM', end:'1:00 PM',  plate:'PQR-1234', status:'upcoming' },
  { id:'RES-2026-0835', user:'Khalid Al-Otaibi',   role:'student', spot:'D-02', zone:'D', date:'Mar 8, 2026', start:'8:00 AM', end:'10:00 AM', plate:'STU-5678', status:'completed' },
  { id:'RES-2026-0834', user:'Nora Al-Dossari',    role:'student', spot:'A-07', zone:'A', date:'Mar 8, 2026', start:'7:30 AM', end:'9:30 AM',  plate:'VWX-9012', status:'completed' },
  { id:'RES-2026-0833', user:'Yasser Al-Ghamdi',   role:'student', spot:'B-08', zone:'B', date:'Mar 8, 2026', start:'9:00 AM', end:'—',        plate:'YZA-3456', status:'cancelled' },
  { id:'RES-2026-0832', user:'Hessa Al-Mutairi',   role:'student', spot:'A-03', zone:'A', date:'Mar 8, 2026', start:'10:30 AM',end:'—',        plate:'BCD-7890', status:'cancelled' },
];

const STATUS_BADGE = {
  active:    '<span class="badge badge-active">● Active</span>',
  upcoming:  '<span class="badge badge-reserved">📅 Upcoming</span>',
  completed: '<span class="badge badge-available">✅ Completed</span>',
  cancelled: '<span class="badge badge-cancelled">✕ Cancelled</span>',
};
const ROLE_BADGE = {
  student: '<span class="badge badge-student">Student</span>',
  staff:   '<span class="badge badge-staff">Staff</span>',
};

function renderReservations(list) {
  document.getElementById('resBody').innerHTML = list.map(r => `
    <tr>
      <td style="font-family:'DM Mono',monospace;font-size:.78rem;color:var(--teal-400)">#${r.id}</td>
      <td style="font-weight:500;white-space:nowrap">${r.user}</td>
      <td>${ROLE_BADGE[r.role]}</td>
      <td><strong style="font-family:'Syne',sans-serif">${r.spot}</strong></td>
      <td><span style="color:var(--amber-400);font-weight:600">Zone ${r.zone}</span></td>
      <td style="font-size:.82rem;white-space:nowrap">${r.date}</td>
      <td style="font-family:'DM Mono',monospace;font-size:.8rem">${r.start}</td>
      <td style="font-family:'DM Mono',monospace;font-size:.8rem">${r.end}</td>
      <td style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--gray-300)">${r.plate}</td>
      <td>${STATUS_BADGE[r.status]}</td>
      <td>
        <div style="display:flex;gap:5px">
          <button class="btn btn-ghost btn-sm" onclick="viewReservation('${r.id}')">👁️</button>
          ${r.status !== 'completed' && r.status !== 'cancelled' ? `<button class="btn btn-danger btn-sm" onclick="showToast('Cancelled','Reservation cancelled','info')">✕</button>` : ''}
        </div>
      </td>
    </tr>`).join('');
  document.getElementById('resCount').textContent = list.length;
}

function filterReservations() {
  const q = document.getElementById('resSearch').value.toLowerCase();
  const st = document.getElementById('resStatusFilter').value;
  const zn = document.getElementById('resZoneFilter').value;
  const tp = document.getElementById('resTypeFilter').value;
  const filtered = RESERVATIONS.filter(r =>
    (q === '' || r.user.toLowerCase().includes(q) || r.spot.toLowerCase().includes(q) || r.id.toLowerCase().includes(q) || r.plate.toLowerCase().includes(q)) &&
    (st === 'all' || r.status === st) &&
    (zn === 'all' || r.zone === zn) &&
    (tp === 'all' || r.role === tp)
  );
  renderReservations(filtered);
}

function viewReservation(id) {
  const r = RESERVATIONS.find(x => x.id === id);
  if (!r) return;
  document.getElementById('viewResId').textContent = '#' + r.id;
  document.getElementById('viewResContent').innerHTML = `
    <div class="info-row"><div class="info-row-label">User</div><div class="info-row-value">${r.user}</div></div>
    <div class="info-row"><div class="info-row-label">Role</div><div class="info-row-value">${ROLE_BADGE[r.role]}</div></div>
    <div class="info-row"><div class="info-row-label">Parking Spot</div><div class="info-row-value"><strong>${r.spot}</strong> — Zone ${r.zone}</div></div>
    <div class="info-row"><div class="info-row-label">Date</div><div class="info-row-value">${r.date}</div></div>
    <div class="info-row"><div class="info-row-label">Time</div><div class="info-row-value">${r.start} – ${r.end}</div></div>
    <div class="info-row"><div class="info-row-label">Vehicle Plate</div><div class="info-row-value" style="font-family:'DM Mono',monospace">${r.plate}</div></div>
    <div class="info-row"><div class="info-row-label">Status</div><div class="info-row-value">${STATUS_BADGE[r.status]}</div></div>
  `;
  openModal('viewResModal');
}

renderReservations(RESERVATIONS);
</script>
</body>
</html>
