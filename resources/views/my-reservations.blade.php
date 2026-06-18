<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — My Reservations</title>
<link rel="stylesheet" href="css/style.css">
<style>
.res-card {
  background:var(--bg2); border:1px solid var(--border);
  border-radius:var(--radius); padding:20px;
  transition:var(--transition); position:relative; overflow:hidden;
  display:flex; flex-direction:column; gap:14px;
}
.res-card:hover { border-color:var(--border2); box-shadow:var(--shadow); }
.res-card.active-card { border-color:rgba(61,127,255,0.4); }
.res-card.active-card::before { content:''; position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--accent),var(--teal)); }
.res-top { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
.spot-badge { display:inline-flex;align-items:center;gap:6px;background:var(--surface);padding:6px 12px;border-radius:8px; }
.spot-badge .spot-id { font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:var(--text); }
.res-meta { display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px;font-size:0.82rem; }
.res-meta-item { background:var(--surface);padding:10px;border-radius:8px; }
.res-meta-item .lbl { color:var(--text3);font-size:0.72rem;margin-bottom:2px; }
.res-meta-item .val { font-weight:600;color:var(--text); }
.res-actions { display:flex;gap:8px;flex-wrap:wrap;padding-top:4px;border-top:1px solid var(--border); }
.qr-placeholder { width:64px;height:64px;background:var(--surface);border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;font-size:1.5rem;cursor:pointer;flex-shrink:0; }
.qr-placeholder .qr-lbl { font-size:0.55rem;color:var(--text3); }
</style>
</head>
<body>
<div id="sidebarMount"></div>

<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">My Reservations</span>
      <div class="topbar-actions">
        <button class="topbar-btn" onclick="nav('notifications.html')">🔔<div class="notif-dot"></div></button>
        <div class="avatar" style="cursor:pointer" onclick="nav('profile.html')">RA</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Dashboard</span> / My Reservations</div>
        <div class="page-header-row">
          <div><h1>My Reservations</h1><p>View and manage all your parking bookings</p></div>
          <button class="btn btn-primary" onclick="nav('reserve.html')">+ New Reservation</button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="tabs">
        <div class="tab active" onclick="switchTab(this,'all')">All <span style="font-size:0.75rem;background:var(--surface2);padding:2px 8px;border-radius:20px;margin-left:4px">5</span></div>
        <div class="tab" onclick="switchTab(this,'active')">Active</div>
        <div class="tab" onclick="switchTab(this,'upcoming')">Upcoming</div>
        <div class="tab" onclick="switchTab(this,'past')">Past</div>
        <div class="tab" onclick="switchTab(this,'cancelled')">Cancelled</div>
      </div>

      <!-- Active Reservation -->
      <div id="tab-all" class="tab-content">
        <!-- Active -->
        <div class="res-card active-card animate-fade-up">
          <div class="res-top">
            <div>
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                <span class="badge badge-active"><span class="pulse">●</span> Active Now</span>
              </div>
              <div class="spot-badge"><span style="font-size:1rem">🅿️</span><span class="spot-id">B-14</span></div>
            </div>
            <div class="qr-placeholder" onclick="openModal('qrModal')">
              <span>📱</span><span class="qr-lbl">View QR</span>
            </div>
          </div>
          <div class="res-meta">
            <div class="res-meta-item"><div class="lbl">Zone</div><div class="val">Zone B</div></div>
            <div class="res-meta-item"><div class="lbl">Date</div><div class="val">Mar 8, 2026</div></div>
            <div class="res-meta-item"><div class="lbl">Start</div><div class="val">9:12 AM</div></div>
            <div class="res-meta-item"><div class="lbl">Ends</div><div class="val">11:30 AM</div></div>
            <div class="res-meta-item"><div class="lbl">Type</div><div class="val">Standard</div></div>
            <div class="res-meta-item"><div class="lbl">Booking ID</div><div class="val text-accent">#RES-2026-0841</div></div>
          </div>
          <div class="res-actions">
            <button class="btn btn-ghost btn-sm" onclick="openModal('extendModal')">⏰ Extend Time</button>
            <button class="btn btn-ghost btn-sm" onclick="nav('parking-map.html')">🗺️ Navigate</button>
            <button class="btn btn-danger btn-sm" onclick="openModal('cancelModal')">✕ End Early</button>
          </div>
        </div>

        <!-- Upcoming 1 -->
        <div class="res-card animate-fade-up animate-delay-1" style="margin-top:16px">
          <div class="res-top">
            <div>
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                <span class="badge badge-reserved">📅 Upcoming</span>
              </div>
              <div class="spot-badge"><span style="font-size:1rem">🅿️</span><span class="spot-id">A-07</span></div>
            </div>
            <div class="qr-placeholder" onclick="openModal('qrModal')">
              <span>📱</span><span class="qr-lbl">QR Code</span>
            </div>
          </div>
          <div class="res-meta">
            <div class="res-meta-item"><div class="lbl">Zone</div><div class="val">Zone A</div></div>
            <div class="res-meta-item"><div class="lbl">Date</div><div class="val">Mar 9, 2026</div></div>
            <div class="res-meta-item"><div class="lbl">Start</div><div class="val">9:00 AM</div></div>
            <div class="res-meta-item"><div class="lbl">Ends</div><div class="val">1:00 PM</div></div>
            <div class="res-meta-item"><div class="lbl">Duration</div><div class="val">4 Hours</div></div>
            <div class="res-meta-item"><div class="lbl">Booking ID</div><div class="val text-accent">#RES-2026-0842</div></div>
          </div>
          <div class="res-actions">
            <button class="btn btn-ghost btn-sm" onclick="openModal('editModal')">✏️ Modify</button>
            <button class="btn btn-danger btn-sm" onclick="openModal('cancelModal')">✕ Cancel</button>
          </div>
        </div>

        <!-- Upcoming 2 -->
        <div class="res-card animate-fade-up animate-delay-2" style="margin-top:16px">
          <div class="res-top">
            <div>
              <span class="badge badge-reserved" style="margin-bottom:8px;display:inline-flex">📅 Upcoming</span>
              <div class="spot-badge"><span style="font-size:1rem">🅿️</span><span class="spot-id">C-22</span></div>
            </div>
            <div class="qr-placeholder" onclick="openModal('qrModal')">
              <span>📱</span><span class="qr-lbl">QR Code</span>
            </div>
          </div>
          <div class="res-meta">
            <div class="res-meta-item"><div class="lbl">Zone</div><div class="val">Zone C</div></div>
            <div class="res-meta-item"><div class="lbl">Date</div><div class="val">Mar 10, 2026</div></div>
            <div class="res-meta-item"><div class="lbl">Start</div><div class="val">8:00 AM</div></div>
            <div class="res-meta-item"><div class="lbl">Ends</div><div class="val">12:00 PM</div></div>
            <div class="res-meta-item"><div class="lbl">Duration</div><div class="val">4 Hours</div></div>
            <div class="res-meta-item"><div class="lbl">Booking ID</div><div class="val text-accent">#RES-2026-0843</div></div>
          </div>
          <div class="res-actions">
            <button class="btn btn-ghost btn-sm" onclick="openModal('editModal')">✏️ Modify</button>
            <button class="btn btn-danger btn-sm" onclick="openModal('cancelModal')">✕ Cancel</button>
          </div>
        </div>

        <!-- Past 1 -->
        <div class="res-card animate-fade-up animate-delay-3" style="margin-top:16px;opacity:0.8">
          <div class="res-top">
            <div>
              <span class="badge badge-available" style="margin-bottom:8px;display:inline-flex">✅ Completed</span>
              <div class="spot-badge"><span style="font-size:1rem">🅿️</span><span class="spot-id">A-03</span></div>
            </div>
          </div>
          <div class="res-meta">
            <div class="res-meta-item"><div class="lbl">Zone</div><div class="val">Zone A</div></div>
            <div class="res-meta-item"><div class="lbl">Date</div><div class="val">Mar 7, 2026</div></div>
            <div class="res-meta-item"><div class="lbl">Entry</div><div class="val">8:45 AM</div></div>
            <div class="res-meta-item"><div class="lbl">Exit</div><div class="val">12:00 PM</div></div>
            <div class="res-meta-item"><div class="lbl">Duration</div><div class="val">3h 15m</div></div>
            <div class="res-meta-item"><div class="lbl">Booking ID</div><div class="val text-accent">#RES-2026-0838</div></div>
          </div>
          <div class="res-actions">
            <button class="btn btn-ghost btn-sm" onclick="showToast('Report Filed','Your issue has been reported','success')">⚠️ Report Issue</button>
            <button class="btn btn-ghost btn-sm" onclick="nav('reserve.html')">🔄 Book Again</button>
          </div>
        </div>

        <!-- Cancelled -->
        <div class="res-card animate-fade-up animate-delay-4" style="margin-top:16px;opacity:0.6">
          <div class="res-top">
            <div>
              <span class="badge badge-inactive" style="margin-bottom:8px;display:inline-flex">✕ Cancelled</span>
              <div class="spot-badge"><span style="font-size:1rem">🅿️</span><span class="spot-id">B-08</span></div>
            </div>
          </div>
          <div class="res-meta">
            <div class="res-meta-item"><div class="lbl">Zone</div><div class="val">Zone B</div></div>
            <div class="res-meta-item"><div class="lbl">Date</div><div class="val">Mar 4, 2026</div></div>
            <div class="res-meta-item"><div class="lbl">Cancelled</div><div class="val">Mar 4</div></div>
            <div class="res-meta-item"><div class="lbl">Reason</div><div class="val">User</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Extend Modal -->
<div class="modal-overlay" id="extendModal">
  <div class="modal" style="max-width:400px">
    <div class="modal-header">
      <div><h3>Extend Reservation</h3><p style="font-size:0.85rem;margin-top:4px">Spot B-14 expires at 11:30 AM</p></div>
      <button class="modal-close" onclick="closeModal('extendModal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">Extend by</label>
      <select class="form-control">
        <option>30 minutes</option><option>1 hour</option><option>2 hours</option>
      </select>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('extendModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Extended!','Reservation extended successfully','success');closeModal('extendModal')">Confirm</button>
    </div>
  </div>
</div>

<!-- Cancel Modal -->
<div class="modal-overlay" id="cancelModal">
  <div class="modal" style="max-width:400px">
    <div class="modal-header">
      <h3 style="color:var(--red)">Cancel Reservation?</h3>
      <button class="modal-close" onclick="closeModal('cancelModal')">✕</button>
    </div>
    <p style="color:var(--text2);margin-bottom:16px">Are you sure you want to cancel this reservation? This action cannot be undone.</p>
    <div class="form-group">
      <label class="form-label">Reason (optional)</label>
      <select class="form-control">
        <option>Change of plans</option><option>Found alternative parking</option>
        <option>Not coming to campus</option><option>Other</option>
      </select>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('cancelModal')">Keep</button>
      <button class="btn btn-danger" onclick="showToast('Cancelled','Reservation has been cancelled','info');closeModal('cancelModal')">Cancel Reservation</button>
    </div>
  </div>
</div>

<!-- Modify Modal -->
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Modify Reservation</h3><p style="font-size:0.85rem;margin-top:4px">Spot A-07</p></div>
      <button class="modal-close" onclick="closeModal('editModal')">✕</button>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">New Start Time</label>
        <select class="form-control"><option>9:00 AM</option><option>9:30 AM</option><option>10:00 AM</option></select>
      </div>
      <div class="form-group">
        <label class="form-label">New End Time</label>
        <select class="form-control"><option>1:00 PM</option><option>2:00 PM</option><option>3:00 PM</option></select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('editModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Updated!','Reservation modified successfully','success');closeModal('editModal')">Save Changes</button>
    </div>
  </div>
</div>

<!-- QR Modal -->
<div class="modal-overlay" id="qrModal">
  <div class="modal" style="max-width:340px;text-align:center">
    <div class="modal-header" style="justify-content:flex-end"><button class="modal-close" onclick="closeModal('qrModal')">✕</button></div>
    <div style="font-size:1rem;font-weight:700;margin-bottom:4px">Entry QR Code</div>
    <p style="font-size:0.82rem;color:var(--text2);margin-bottom:20px">Show this at the gate scanner</p>
    <div style="background:white;border-radius:var(--radius);padding:20px;display:inline-block;margin-bottom:16px">
      <canvas id="qrCanvas" width="160" height="160"></canvas>
    </div>
    <p style="font-size:0.8rem;color:var(--text3)">Booking #RES-2026-0841 · Spot B-14</p>
    <div class="modal-footer" style="justify-content:center;margin-top:16px">
      <button class="btn btn-primary" onclick="showToast('Saved!','QR code downloaded','success')">💾 Save to Phone</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('student', 'my-reservations');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

function switchTab(el, tab) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
}

// Draw simple QR placeholder
document.getElementById('qrModal').addEventListener('transitionend', function() {
  const c = document.getElementById('qrCanvas');
  if (!c) return;
  const ctx = c.getContext('2d');
  ctx.fillStyle = '#000';
  ctx.fillRect(0,0,160,160);
  ctx.fillStyle = '#fff';
  for (let i = 0; i < 12; i++) for (let j = 0; j < 12; j++) {
    if (Math.random() > 0.5) ctx.fillRect(i*13+2, j*13+2, 11, 11);
  }
  // Corner squares
  [[0,0],[0,10],[10,0]].forEach(([x,y]) => {
    ctx.fillStyle = '#000';
    ctx.fillRect(x*13+2, y*13+2, 22, 22);
    ctx.fillStyle = '#fff';
    ctx.fillRect(x*13+5, y*13+5, 16, 16);
    ctx.fillStyle = '#000';
    ctx.fillRect(x*13+8, y*13+8, 10, 10);
  });
}, { once: false });
</script>
</body>
</html>
