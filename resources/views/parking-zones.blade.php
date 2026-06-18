<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Parking Zones</title>
<link rel="stylesheet" href="css/style.css">
<style>
.zone-card{background:var(--bg2, rgba(15,32,64,.7));border:1px solid rgba(255,255,255,.07);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition)}
.zone-card:hover{border-color:rgba(0,212,200,.2);box-shadow:var(--shadow)}
.zone-card-header{padding:18px 20px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;justify-content:space-between}
.zone-card-body{padding:20px}
.zone-accent{width:44px;height:44px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.spot-mini-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(28px,1fr));gap:4px;margin:14px 0}
.spot-mini{height:20px;border-radius:3px;transition:var(--transition)}
.spot-mini.available{background:rgba(16,185,129,.35)}
.spot-mini.reserved{background:rgba(245,158,11,.4)}
.spot-mini.occupied{background:rgba(239,68,68,.4)}
.spot-mini.disabled{background:rgba(255,255,255,.06)}
.zone-stat{text-align:center;padding:10px;background:rgba(255,255,255,.03);border-radius:var(--radius-sm)}
.zone-stat .val{font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;line-height:1}
.zone-stat .lbl{font-size:.7rem;color:var(--gray-500);font-family:'DM Mono',monospace;margin-top:2px}
</style>
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Parking Zones</span>
      <div class="topbar-search"><span style="color:var(--gray-500)">🔍</span><input type="text" placeholder="Search zones..." id="zoneSearch" oninput="filterZones()" /></div>
      <div class="topbar-actions">
        <button class="topbar-btn" style="position:relative">🔔<div class="notif-dot"></div></button>
        <div class="avatar" style="background:linear-gradient(135deg,var(--amber-500),#ffa726);color:#050d1a">AD</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Admin Dashboard</span> / Parking Zones</div>
        <div class="page-header-row">
          <div><h1>Parking Zones</h1><p>Manage all campus parking zones and spot configurations</p></div>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm" onclick="showToast('Exported','Zones report downloaded','info')">📤 Export</button>
            <button class="btn btn-primary btn-sm" onclick="openModal('addZoneModal')">+ Add Zone</button>
          </div>
        </div>
      </div>

      <!-- Summary Stats -->
      <div class="stats-grid animate-fade-up">
        <div class="stat-card teal"><div class="stat-icon">🏗️</div><div class="stat-value">247</div><div class="stat-label">Total Spots</div><div class="stat-change up">Across 5 zones</div></div>
        <div class="stat-card green"><div class="stat-icon">✅</div><div class="stat-value">148</div><div class="stat-label">Available</div><div class="stat-change up">60% availability</div></div>
        <div class="stat-card red"><div class="stat-icon">🔴</div><div class="stat-value">62</div><div class="stat-label">Occupied</div><div class="stat-change">25% occupancy</div></div>
        <div class="stat-card gold"><div class="stat-icon">📋</div><div class="stat-value">37</div><div class="stat-label">Reserved</div><div class="stat-change up">15% reserved</div></div>
      </div>

      <!-- View Toggle -->
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px" class="animate-fade-up animate-delay-1">
        <div class="tab-list">
          <div class="tab-btn active" onclick="setView('grid',this)">⊞ Grid</div>
          <div class="tab-btn" onclick="setView('table',this)">☰ Table</div>
        </div>
        <select class="filter-select" id="zoneStatusFilter" onchange="filterZones()">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="maintenance">Maintenance</option>
        </select>
      </div>

      <!-- Grid View -->
      <div id="gridView" class="animate-fade-up animate-delay-2">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:20px" id="zonesGrid"></div>
      </div>

      <!-- Table View -->
      <div id="tableView" style="display:none" class="animate-fade-up animate-delay-2">
        <div class="card">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Zone</th><th>Type</th><th>Total Spots</th><th>Available</th><th>Reserved</th><th>Occupied</th><th>Occupancy</th><th>Status</th><th>Actions</th>
                </tr>
              </thead>
              <tbody id="zonesTableBody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Zone Modal -->
<div class="modal-overlay" id="addZoneModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Add New Parking Zone</h3></div>
      <button class="modal-close" onclick="closeModal('addZoneModal')">✕</button>
    </div>
    <div class="form-row" style="margin-bottom:16px">
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Zone Name</label>
        <input type="text" class="form-control" placeholder="e.g. Zone E" />
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Zone Type</label>
        <select class="form-control">
          <option>General Student</option><option>Staff Only</option><option>Visitor</option><option>Accessible</option><option>Reserved VIP</option>
        </select>
      </div>
    </div>
    <div class="form-row" style="margin-bottom:16px">
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Total Spots</label>
        <input type="number" class="form-control" placeholder="50" min="1" />
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Floor / Level</label>
        <select class="form-control">
          <option>Ground Floor</option><option>Level 1</option><option>Level 2</option><option>Rooftop</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Description</label>
      <input type="text" class="form-control" placeholder="Near main building entrance..." />
    </div>
    <div class="form-row" style="margin-bottom:0">
      <div class="form-group">
        <label class="form-label">Accessible Spots</label>
        <input type="number" class="form-control" placeholder="0" min="0" />
      </div>
      <div class="form-group">
        <label class="form-label">EV Charging Spots</label>
        <input type="number" class="form-control" placeholder="0" min="0" />
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('addZoneModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Zone Created','New zone added successfully','success');closeModal('addZoneModal')">Create Zone</button>
    </div>
  </div>
</div>

<!-- Edit Zone Modal -->
<div class="modal-overlay" id="editZoneModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Edit Zone</h3><p id="editZoneSubtitle" style="font-size:.85rem;color:var(--gray-400);margin-top:4px"></p></div>
      <button class="modal-close" onclick="closeModal('editZoneModal')">✕</button>
    </div>
    <div class="form-row" style="margin-bottom:16px">
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Zone Name</label>
        <input type="text" class="form-control" id="editZoneName" />
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Status</label>
        <select class="form-control" id="editZoneStatus">
          <option value="active">Active</option>
          <option value="maintenance">Maintenance</option>
          <option value="closed">Closed</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Max Hours Per Reservation</label>
      <select class="form-control">
        <option>4 hours</option><option selected>8 hours</option><option>12 hours</option><option>No limit</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Notes</label>
      <textarea class="form-control" rows="3" placeholder="Zone-specific instructions..."></textarea>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('editZoneModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Zone Updated','Changes saved successfully','success');closeModal('editZoneModal')">Save Changes</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('admin', 'parking-zones');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

const ZONES = [
  { id:'A', name:'Zone A', type:'General Student', floor:'Ground', total:60, available:42, reserved:8, occupied:10, status:'active', color:'var(--teal-500)', icon:'🎓', desc:'North campus, near main gate' },
  { id:'B', name:'Zone B', type:'General Student', floor:'Ground', total:55, available:28, reserved:12, occupied:15, status:'active', color:'#3d7fff', icon:'🎓', desc:'East campus, near Engineering building' },
  { id:'C', name:'Zone C', type:'Staff Only',      floor:'Ground', total:40, available:18, reserved:7,  occupied:15, status:'active', color:'var(--amber-500)', icon:'👔', desc:'Faculty parking — valid staff permit required' },
  { id:'D', name:'Zone D', type:'Visitor / Accessible', floor:'Ground', total:20, available:10, reserved:4, occupied:6, status:'active', color:'#a78bfa', icon:'♿', desc:'Accessible & visitor spots near main building' },
  { id:'S', name:'Zone S', type:'Staff Reserved',  floor:'Ground', total:32, available:30, reserved:0, occupied:2,  status:'maintenance', color:'var(--gray-500)', icon:'🔒', desc:'Senior staff reserved — currently under maintenance' },
];

let currentView = 'grid';
let zonesFiltered = [...ZONES];

function setView(view, el) {
  currentView = view;
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('gridView').style.display = view === 'grid' ? 'block' : 'none';
  document.getElementById('tableView').style.display = view === 'table' ? 'block' : 'none';
  renderZones(zonesFiltered);
}

function generateSpotMinis(zone) {
  const spots = [];
  const statuses = ['available','available','available','reserved','occupied','disabled'];
  for (let i = 0; i < Math.min(zone.total, 40); i++) {
    if (i < zone.occupied) spots.push('occupied');
    else if (i < zone.occupied + zone.reserved) spots.push('reserved');
    else if (zone.status === 'maintenance' && i > zone.total * 0.3) spots.push('disabled');
    else spots.push('available');
  }
  return spots.map(s => `<div class="spot-mini ${s}" title="${s}"></div>`).join('');
}

function renderZones(list) {
  // Grid view
  document.getElementById('zonesGrid').innerHTML = list.map(z => {
    const occupancy = Math.round((z.occupied / z.total) * 100);
    const occupancyColor = occupancy < 40 ? 'var(--green-500)' : occupancy < 70 ? 'var(--amber-500)' : 'var(--red-500)';
    return `
    <div class="zone-card">
      <div class="zone-card-header">
        <div style="display:flex;align-items:center;gap:14px">
          <div class="zone-accent" style="background:${z.color}22;border:1px solid ${z.color}44;color:${z.color};font-size:1.4rem">${z.icon}</div>
          <div>
            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:1rem;color:var(--white)">${z.name}</div>
            <div style="font-size:.75rem;color:var(--gray-500);font-family:'DM Mono',monospace">${z.type}</div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
          ${z.status === 'maintenance'
            ? '<span class="badge badge-reserved">🔧 Maintenance</span>'
            : '<span class="badge badge-active">● Active</span>'}
          <button class="btn btn-ghost btn-sm btn-icon" onclick="openEditModal('${z.id}')" title="Edit zone">✏️</button>
        </div>
      </div>
      <div class="zone-card-body">
        <p style="font-size:.8rem;color:var(--gray-500);margin-bottom:14px">${z.desc}</p>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:16px">
          <div class="zone-stat"><div class="val" style="color:var(--white)">${z.total}</div><div class="lbl">Total</div></div>
          <div class="zone-stat"><div class="val" style="color:var(--green-400)">${z.available}</div><div class="lbl">Free</div></div>
          <div class="zone-stat"><div class="val" style="color:var(--amber-400)">${z.reserved}</div><div class="lbl">Reserved</div></div>
          <div class="zone-stat"><div class="val" style="color:var(--red-400)">${z.occupied}</div><div class="lbl">Occupied</div></div>
        </div>
        <div style="margin-bottom:10px">
          <div style="display:flex;justify-content:space-between;font-size:.78rem;color:var(--gray-400);margin-bottom:6px">
            <span>Occupancy</span>
            <span style="color:${occupancyColor};font-weight:700">${occupancy}%</span>
          </div>
          <div class="progress-bar"><div class="progress-fill" style="width:${occupancy}%;background:${occupancyColor}"></div></div>
        </div>
        <div class="spot-mini-grid">${generateSpotMinis(z)}</div>
        <div style="display:flex;gap:8px;margin-top:4px;padding-top:12px;border-top:1px solid rgba(255,255,255,.05)">
          <button class="btn btn-ghost btn-sm" style="flex:1" onclick="nav('parking-map.html')">🗺️ View on Map</button>
          <button class="btn btn-ghost btn-sm" style="flex:1" onclick="openModal('editZoneModal')">⚙️ Manage</button>
        </div>
      </div>
    </div>`;
  }).join('');

  // Table view
  document.getElementById('zonesTableBody').innerHTML = list.map(z => {
    const occ = Math.round((z.occupied / z.total) * 100);
    const color = occ < 40 ? 'var(--green-400)' : occ < 70 ? 'var(--amber-400)' : 'var(--red-400)';
    return `<tr>
      <td><strong style="font-family:'Syne',sans-serif;color:${z.color}">${z.name}</strong></td>
      <td style="font-size:.82rem">${z.type}</td>
      <td style="font-family:'DM Mono',monospace">${z.total}</td>
      <td style="color:var(--green-400);font-family:'DM Mono',monospace">${z.available}</td>
      <td style="color:var(--amber-400);font-family:'DM Mono',monospace">${z.reserved}</td>
      <td style="color:var(--red-400);font-family:'DM Mono',monospace">${z.occupied}</td>
      <td>
        <div style="display:flex;align-items:center;gap:8px">
          <div class="progress-bar" style="width:80px"><div class="progress-fill" style="width:${occ}%;background:${color}"></div></div>
          <span style="font-size:.78rem;color:${color}">${occ}%</span>
        </div>
      </td>
      <td>${z.status === 'maintenance' ? '<span class="badge badge-reserved">🔧 Maintenance</span>' : '<span class="badge badge-active">● Active</span>'}</td>
      <td>
        <div style="display:flex;gap:6px">
          <button class="btn btn-ghost btn-sm" onclick="openEditModal('${z.id}')">✏️ Edit</button>
          <button class="btn btn-danger btn-sm" onclick="showToast('Zone Disabled','Zone temporarily disabled','info')">⏸️</button>
        </div>
      </td>
    </tr>`;
  }).join('');
}

function openEditModal(zoneId) {
  const z = ZONES.find(x => x.id === zoneId);
  if (!z) return;
  document.getElementById('editZoneSubtitle').textContent = z.name + ' — ' + z.type;
  document.getElementById('editZoneName').value = z.name;
  document.getElementById('editZoneStatus').value = z.status;
  openModal('editZoneModal');
}

function filterZones() {
  const q = document.getElementById('zoneSearch').value.toLowerCase();
  const st = document.getElementById('zoneStatusFilter').value;
  zonesFiltered = ZONES.filter(z =>
    (q === '' || z.name.toLowerCase().includes(q) || z.type.toLowerCase().includes(q)) &&
    (st === 'all' || z.status === st)
  );
  renderZones(zonesFiltered);
}

renderZones(ZONES);
</script>
</body>
</html>
