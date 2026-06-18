<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Entry/Exit Log</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Entry / Exit Log</span>
      <div class="topbar-actions">
        <button class="topbar-btn">🔔</button>
        <div class="avatar" style="background:linear-gradient(135deg,var(--gold),#ffa726)">AD</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">⚙️ <span>Admin</span> / Entry/Exit Log</div>
        <div class="page-header-row">
          <div><h1>Vehicle Log</h1><p>Complete entry and exit movement history</p></div>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm" onclick="showToast('Exporting','Log downloaded','info')">📤 Export CSV</button>
            <button class="btn btn-primary btn-sm" onclick="openModal('manualEntryModal')">+ Manual Entry</button>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="stats-grid" style="margin-bottom:20px">
        <div class="stat-card green"><div class="stat-icon">🚗</div><div class="stat-value">47</div><div class="stat-label">Entries Today</div></div>
        <div class="stat-card red"><div class="stat-icon">🚙</div><div class="stat-value">31</div><div class="stat-label">Exits Today</div></div>
        <div class="stat-card blue"><div class="stat-icon">🏠</div><div class="stat-value">16</div><div class="stat-label">Currently Inside</div></div>
        <div class="stat-card gold"><div class="stat-icon">⏱️</div><div class="stat-value">2h 18m</div><div class="stat-label">Avg Stay Duration</div></div>
      </div>

      <div class="filter-bar">
        <div class="search-box">
          <span>🔍</span>
          <input type="text" placeholder="Search by plate number or user..." />
        </div>
        <select class="filter-select">
          <option>All Events</option><option>Entry</option><option>Exit</option>
        </select>
        <select class="filter-select">
          <option>All Zones</option><option>Zone A</option><option>Zone B</option><option>Zone C</option><option>Zone D</option>
        </select>
        <input type="date" class="filter-select" style="padding:8px 14px" />
      </div>

      <div class="card">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Event</th>
                <th>Vehicle Plate</th>
                <th>User</th>
                <th>Zone / Spot</th>
                <th>Gate</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><span style="background:rgba(0,200,150,0.15);color:var(--green);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">⬆ ENTRY</span></td>
                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent2)">ABC-1234</td>
                <td>Renad Alshammari</td>
                <td>Zone B · B-14</td>
                <td>Gate A</td>
                <td>9:12 AM</td>
                <td>—</td>
                <td><span class="pulse" style="color:var(--green)">● Active</span></td>
                <td><span class="badge badge-active">Inside</span></td>
                <td><button class="btn btn-ghost btn-sm" onclick="showToast('Logged Exit','Vehicle exit recorded','success')">Log Exit</button></td>
              </tr>
              <tr>
                <td><span style="background:rgba(255,77,109,0.15);color:var(--red);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">⬇ EXIT</span></td>
                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent2)">XYZ-5678</td>
                <td>Raghad Hamad</td>
                <td>Zone A · A-07</td>
                <td>Gate B</td>
                <td>8:30 AM</td>
                <td>11:44 AM</td>
                <td>3h 14m</td>
                <td><span class="badge badge-available">Completed</span></td>
                <td><button class="btn btn-ghost btn-sm" onclick="showToast('Report','Details copied','info')">Details</button></td>
              </tr>
              <tr>
                <td><span style="background:rgba(0,200,150,0.15);color:var(--green);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">⬆ ENTRY</span></td>
                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent2)">DEF-3456</td>
                <td>Dr. Sara Al-Amin</td>
                <td>Zone C · C-05</td>
                <td>Gate C</td>
                <td>8:00 AM</td>
                <td>—</td>
                <td><span class="pulse" style="color:var(--green)">● Active</span></td>
                <td><span class="badge badge-active">Inside</span></td>
                <td><button class="btn btn-ghost btn-sm" onclick="showToast('Logged Exit','Vehicle exit recorded','success')">Log Exit</button></td>
              </tr>
              <tr>
                <td><span style="background:rgba(245,200,66,0.15);color:var(--gold);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">⚠ VIOLATION</span></td>
                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--red)">MNO-9012</td>
                <td><span style="color:var(--text3)">Unknown</span></td>
                <td>Zone C · Staff area</td>
                <td>Gate C</td>
                <td>9:18 AM</td>
                <td>—</td>
                <td>—</td>
                <td><span class="badge badge-occupied">Violation</span></td>
                <td><button class="btn btn-danger btn-sm" onclick="openModal('violationModal')">Report</button></td>
              </tr>
              <tr>
                <td><span style="background:rgba(255,77,109,0.15);color:var(--red);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">⬇ EXIT</span></td>
                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent2)">GHI-7890</td>
                <td>Amjad Alshammri</td>
                <td>Zone B · B-09</td>
                <td>Gate B</td>
                <td>7:55 AM</td>
                <td>10:30 AM</td>
                <td>2h 35m</td>
                <td><span class="badge badge-available">Completed</span></td>
                <td><button class="btn btn-ghost btn-sm">Details</button></td>
              </tr>
              <tr>
                <td><span style="background:rgba(0,200,150,0.15);color:var(--green);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">⬆ ENTRY</span></td>
                <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent2)">JKL-2345</td>
                <td>Amjad Moubarak</td>
                <td>Zone A · A-12</td>
                <td>Gate A</td>
                <td>8:45 AM</td>
                <td>—</td>
                <td><span class="pulse" style="color:var(--green)">● Active</span></td>
                <td><span class="badge badge-active">Inside</span></td>
                <td><button class="btn btn-ghost btn-sm" onclick="showToast('Exit Logged','','success')">Log Exit</button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;flex-wrap:wrap;gap:12px">
          <div style="font-size:0.82rem;color:var(--text3)">Showing 6 of 247 records today</div>
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

<!-- Manual Entry Modal -->
<div class="modal-overlay" id="manualEntryModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Manual Entry/Exit Log</h3><p style="font-size:0.85rem;margin-top:4px">Log a vehicle event manually</p></div>
      <button class="modal-close" onclick="closeModal('manualEntryModal')">✕</button>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Event Type</label>
        <select class="form-control"><option>Entry</option><option>Exit</option></select>
      </div>
      <div class="form-group">
        <label class="form-label">Vehicle Plate</label>
        <input type="text" class="form-control" placeholder="ABC-1234" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Zone</label>
        <select class="form-control"><option>Zone A</option><option>Zone B</option><option>Zone C</option><option>Zone D</option></select>
      </div>
      <div class="form-group">
        <label class="form-label">Gate</label>
        <select class="form-control"><option>Gate A</option><option>Gate B</option><option>Gate C</option></select>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Notes</label>
      <input type="text" class="form-control" placeholder="Reason for manual entry..." />
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('manualEntryModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Logged!','Entry recorded manually','success');closeModal('manualEntryModal')">Save Log</button>
    </div>
  </div>
</div>

<!-- Violation Modal -->
<div class="modal-overlay" id="violationModal">
  <div class="modal">
    <div class="modal-header">
      <h3 style="color:var(--red)">Report Violation</h3>
      <button class="modal-close" onclick="closeModal('violationModal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">Violation Type</label>
      <select class="form-control">
        <option>No valid reservation</option><option>Wrong zone</option>
        <option>Overstay</option><option>Accessible spot misuse</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Description</label>
      <textarea class="form-control" rows="3" placeholder="Describe the violation..." style="resize:vertical"></textarea>
    </div>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
      <input type="checkbox" id="notifySecurity" style="accent-color:var(--accent)" checked>
      <label for="notifySecurity" style="font-size:0.85rem;color:var(--text2)">Notify security personnel</label>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('violationModal')">Cancel</button>
      <button class="btn btn-danger" onclick="showToast('Violation Reported','Security has been notified','success');closeModal('violationModal')">Submit Report</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('admin', 'entry-exit');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});
</script>
</body>
</html>
