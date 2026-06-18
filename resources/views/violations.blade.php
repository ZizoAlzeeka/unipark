<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Violations</title>
<link rel="stylesheet" href="css/style.css">
<style>
.violation-card{background:rgba(239,68,68,.04);border:1px solid rgba(239,68,68,.15);border-radius:var(--radius-md);padding:20px;margin-bottom:14px;transition:var(--transition);position:relative;overflow:hidden}
.violation-card::before{content:'';position:absolute;top:0;left:0;width:3px;height:100%;background:var(--red-500)}
.violation-card:hover{border-color:rgba(239,68,68,.3);background:rgba(239,68,68,.06)}
.violation-card.resolved{background:rgba(16,185,129,.03);border-color:rgba(16,185,129,.12)}
.violation-card.resolved::before{background:var(--green-500)}
.violation-id{font-family:'DM Mono',monospace;font-size:.75rem;color:var(--gray-500);margin-bottom:8px}
.violation-type{font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;color:var(--white);margin-bottom:4px}
.violation-meta{display:flex;gap:16px;flex-wrap:wrap;font-size:.8rem;color:var(--gray-400);margin-bottom:12px}
.violation-meta span{display:flex;align-items:center;gap:5px}
</style>
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Violations</span>
      <div class="topbar-actions">
        <button class="topbar-btn" style="position:relative">🔔<div class="notif-dot"></div></button>
        <div class="avatar">RA</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Dashboard</span> / Violations</div>
        <div class="page-header-row">
          <div><h1>Violations Record</h1><p>Review and appeal your parking violations</p></div>
          <button class="btn btn-ghost btn-sm" onclick="showToast('Exported','Violations report downloaded','info')">📤 Export</button>
        </div>
      </div>

      <!-- Stats -->
      <div class="stats-grid animate-fade-up" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr))">
        <div class="stat-card red"><div class="stat-icon">⚠️</div><div class="stat-value">0</div><div class="stat-label">Active Violations</div><div class="stat-change up" style="color:var(--green-400)">✓ All clear</div></div>
        <div class="stat-card gold"><div class="stat-icon">📋</div><div class="stat-value">2</div><div class="stat-label">Total Violations</div><div class="stat-change up">All time</div></div>
        <div class="stat-card green"><div class="stat-icon">✅</div><div class="stat-value">2</div><div class="stat-label">Resolved</div><div class="stat-change up">100% resolved</div></div>
        <div class="stat-card blue"><div class="stat-icon">📝</div><div class="stat-value">1</div><div class="stat-label">Appeals Filed</div><div class="stat-change up">1 approved</div></div>
      </div>

      <!-- Good standing banner -->
      <div style="background:linear-gradient(135deg,rgba(16,185,129,.12),rgba(0,212,200,.08));border:1px solid rgba(16,185,129,.2);border-radius:var(--radius-md);padding:16px 20px;display:flex;align-items:center;gap:14px;margin-bottom:24px" class="animate-fade-up animate-delay-1">
        <span style="font-size:1.5rem">🏅</span>
        <div>
          <div style="font-family:'Syne',sans-serif;font-weight:700;color:var(--green-400);margin-bottom:2px">Good Standing</div>
          <div style="font-size:.82rem;color:var(--gray-400)">You have no active violations. Keep up the great parking behavior!</div>
        </div>
      </div>

      <!-- Filters -->
      <div class="filter-bar animate-fade-up animate-delay-2">
        <div class="search-box"><span>🔍</span><input type="text" placeholder="Search violations..." id="vSearch" oninput="filterViolations()" /></div>
        <select class="filter-select" id="vStatus" onchange="filterViolations()">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="resolved">Resolved</option>
          <option value="appealed">Under Appeal</option>
        </select>
        <select class="filter-select" id="vType" onchange="filterViolations()">
          <option value="all">All Types</option>
          <option value="overstay">Overstay</option>
          <option value="unauthorized">Unauthorized</option>
          <option value="no-permit">No Permit</option>
        </select>
      </div>

      <!-- Violations List -->
      <div id="violationsList" class="animate-fade-up animate-delay-3"></div>
    </div>
  </div>
</div>

<!-- Appeal Modal -->
<div class="modal-overlay" id="appealModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>File an Appeal</h3><p style="font-size:.85rem;color:var(--gray-400);margin-top:4px">Violation #VIO-2026-0012</p></div>
      <button class="modal-close" onclick="closeModal('appealModal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">Reason for Appeal</label>
      <select class="form-control">
        <option>Technical issue with gate system</option>
        <option>Incorrect vehicle plate scan</option>
        <option>Medical emergency</option>
        <option>Reserved spot was occupied</option>
        <option>Other</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Description</label>
      <textarea class="form-control" rows="4" placeholder="Provide details to support your appeal..."></textarea>
    </div>
    <div class="form-group">
      <label class="form-label">Supporting Document (optional)</label>
      <div style="border:1px dashed rgba(255,255,255,.12);border-radius:var(--radius-sm);padding:20px;text-align:center;cursor:pointer;color:var(--gray-500);font-size:.85rem" onclick="showToast('Upload','File upload coming soon','info')">
        📎 Click to attach evidence (photo, document)
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('appealModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Appeal Submitted','Your appeal is under review','success');closeModal('appealModal')">Submit Appeal</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('student', 'violations');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

const VIOLATIONS = [
  {
    id:'VIO-2026-0018', type:'overstay', status:'resolved',
    title:'Overstay Violation — Zone B',
    spot:'B-08', zone:'Zone B', date:'Mar 4, 2026', time:'12:45 PM',
    plate:'ABC-1234', fine:'SAR 50', paid:true,
    desc:'Vehicle remained in spot 45 minutes past reservation end time (11:30 AM).',
    resolution:'Fine paid on Mar 5, 2026. Case closed.'
  },
  {
    id:'VIO-2025-0183', type:'no-permit', status:'resolved',
    title:'No Valid Parking Permit',
    spot:'C-05', zone:'Zone C (Staff)', date:'Nov 12, 2025', time:'10:20 AM',
    plate:'ABC-1234', fine:'SAR 75', paid:true,
    desc:'Student vehicle parked in staff-only zone without valid permit.',
    resolution:'Appeal approved — fine waived due to zone signage issue. Case closed.'
  }
];

let filtered = [...VIOLATIONS];

function renderViolations(list) {
  const container = document.getElementById('violationsList');
  if (list.length === 0) {
    container.innerHTML = `<div class="empty-state" style="padding:60px 20px"><div style="font-size:2.5rem;margin-bottom:12px">🎉</div><p style="color:var(--gray-400)">No violations found</p></div>`;
    return;
  }
  container.innerHTML = list.map(v => `
    <div class="violation-card ${v.status}">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">
        <div>
          <div class="violation-id">#${v.id}</div>
          <div class="violation-type">${v.title}</div>
          <div class="violation-meta">
            <span>🅿️ ${v.spot}</span>
            <span>📍 ${v.zone}</span>
            <span>📅 ${v.date} · ${v.time}</span>
            <span>🚗 ${v.plate}</span>
            <span>💰 ${v.fine}</span>
          </div>
          <p style="font-size:.82rem;color:var(--gray-400);margin-bottom:8px">${v.desc}</p>
          ${v.resolution ? `<div style="font-size:.8rem;padding:8px 12px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.15);border-radius:6px;color:var(--green-400)">✅ ${v.resolution}</div>` : ''}
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0">
          ${v.status === 'resolved'
            ? `<span class="badge badge-available">✓ Resolved</span>`
            : `<span class="badge badge-occupied">⚠ Active</span>`}
          ${v.paid ? `<span class="badge badge-active" style="font-size:.7rem">💳 Paid</span>` : ''}
        </div>
      </div>
      <div style="display:flex;gap:8px;margin-top:14px;padding-top:12px;border-top:1px solid rgba(255,255,255,.06)">
        ${v.status !== 'resolved' ? `<button class="btn btn-primary btn-sm" onclick="openModal('appealModal')">📝 File Appeal</button>` : ''}
        ${!v.paid && v.status !== 'resolved' ? `<button class="btn btn-danger btn-sm" onclick="showToast('Redirecting','Payment portal opening','info')">💳 Pay Fine</button>` : ''}
        <button class="btn btn-ghost btn-sm" onclick="showToast('Copied','Violation ID copied','info')">📋 Copy ID</button>
      </div>
    </div>
  `).join('');
}

function filterViolations() {
  const q = document.getElementById('vSearch').value.toLowerCase();
  const st = document.getElementById('vStatus').value;
  const tp = document.getElementById('vType').value;
  filtered = VIOLATIONS.filter(v =>
    (q === '' || v.title.toLowerCase().includes(q) || v.id.toLowerCase().includes(q) || v.spot.toLowerCase().includes(q)) &&
    (st === 'all' || v.status === st) &&
    (tp === 'all' || v.type === tp)
  );
  renderViolations(filtered);
}

renderViolations(VIOLATIONS);
</script>
</body>
</html>
