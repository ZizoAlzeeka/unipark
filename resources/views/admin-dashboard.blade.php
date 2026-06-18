<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Admin Dashboard</title>
<link rel="stylesheet" href="css/style.css">
<style>
.live-indicator { display:flex;align-items:center;gap:6px;font-size:0.78rem;color:var(--green); }
.live-dot { width:8px;height:8px;background:var(--green);border-radius:50%;animation:pulse 2s infinite; }
.kpi-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:24px; }
.kpi { background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:20px;position:relative;overflow:hidden;transition:var(--transition); }
.kpi:hover { transform:translateY(-2px);box-shadow:var(--shadow); }
.kpi-icon { font-size:1.5rem;margin-bottom:10px; }
.kpi-val { font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;line-height:1; }
.kpi-lbl { font-size:0.8rem;color:var(--text2);margin-top:4px; }
.kpi-trend { font-size:0.75rem;margin-top:6px; }
.kpi-bg { position:absolute;right:-10px;bottom:-10px;font-size:5rem;opacity:0.06;pointer-events:none; }
.occupancy-bar { height:12px;background:var(--surface2);border-radius:6px;overflow:hidden;margin:8px 0; }
.occupancy-fill { height:100%;border-radius:6px;transition:width 0.8s cubic-bezier(0.4,0,0.2,1); }
.activity-row { display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);font-size:0.85rem; }
.activity-row:last-child { border-bottom:none; }
.activity-icon { width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0; }
.alert-item { display:flex;align-items:flex-start;gap:12px;padding:12px;background:var(--surface);border-radius:var(--radius-sm);margin-bottom:8px; }
.alert-item:last-child { margin-bottom:0; }
</style>
</head>
<body>
<div id="sidebarMount"></div>

<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Admin Dashboard</span>
      <div class="live-indicator" style="margin-left:8px">
        <div class="live-dot"></div> Live
      </div>
      <div class="topbar-search">
        <span style="color:var(--text3)">🔍</span>
        <input type="text" placeholder="Search users, spots, plates..." />
      </div>
      <div class="topbar-actions">
        <button class="topbar-btn" onclick="nav('notifications.html')" style="position:relative">🔔<div class="notif-dot"></div></button>
        <div class="avatar" style="background:linear-gradient(135deg,var(--gold),#ffa726);cursor:pointer" onclick="nav('profile.html')">AD</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <p class="text-muted" style="font-size:0.85rem;margin-bottom:4px">Sunday, March 8, 2026 · 9:30 AM</p>
        <div class="page-header-row">
          <div>
            <h1>System Overview</h1>
            <p>Real-time campus parking management console</p>
          </div>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm" onclick="exportReport()">📊 Export Report</button>
            <button class="btn btn-primary btn-sm" onclick="openModal('addZoneModal')">+ Add Zone</button>
          </div>
        </div>
      </div>

      <!-- KPIs -->
      <div class="kpi-grid">
        <div class="kpi animate-fade-up">
          <div class="kpi-icon">🏗️</div>
          <div class="kpi-val text-accent">247</div>
          <div class="kpi-lbl">Total Parking Spots</div>
          <div class="kpi-trend text-muted">4 zones active</div>
          <div class="kpi-bg">🏗️</div>
        </div>
        <div class="kpi animate-fade-up animate-delay-1">
          <div class="kpi-icon">✅</div>
          <div class="kpi-val text-success">148</div>
          <div class="kpi-lbl">Available Now</div>
          <div class="kpi-trend text-success">↑ 60% occupancy</div>
          <div class="kpi-bg">✅</div>
        </div>
        <div class="kpi animate-fade-up animate-delay-2">
          <div class="kpi-icon">🔴</div>
          <div class="kpi-val text-danger">62</div>
          <div class="kpi-lbl">Currently Occupied</div>
          <div class="kpi-trend text-danger">↑ 8 vs yesterday</div>
          <div class="kpi-bg">🔴</div>
        </div>
        <div class="kpi animate-fade-up animate-delay-3">
          <div class="kpi-icon">📋</div>
          <div class="kpi-val" style="color:var(--accent2)">37</div>
          <div class="kpi-lbl">Reserved Today</div>
          <div class="kpi-trend" style="color:var(--accent2)">↑ 5 vs yesterday</div>
          <div class="kpi-bg">📋</div>
        </div>
        <div class="kpi animate-fade-up animate-delay-4">
          <div class="kpi-icon">👥</div>
          <div class="kpi-val text-teal">156</div>
          <div class="kpi-lbl">Registered Users</div>
          <div class="kpi-trend text-teal">+3 this week</div>
          <div class="kpi-bg">👥</div>
        </div>
        <div class="kpi animate-fade-up" style="animation-delay:0.5s">
          <div class="kpi-icon">⚠️</div>
          <div class="kpi-val text-gold">4</div>
          <div class="kpi-lbl">Active Violations</div>
          <div class="kpi-trend" style="color:var(--gold)">Needs review</div>
          <div class="kpi-bg">⚠️</div>
        </div>
      </div>

      <div class="grid-2">
        <!-- Zone Occupancy -->
        <div class="card animate-fade-up animate-delay-1">
          <div class="card-header">
            <h3 class="card-title">Zone Occupancy</h3>
            <a href="parking-zones.html" style="font-size:0.82rem;color:var(--accent2)">Manage →</a>
          </div>
          <div style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:6px">
              <span>Zone A — General</span>
              <span style="color:var(--green);font-weight:600">18/60 <span style="color:var(--text3);font-weight:400">occupied</span></span>
            </div>
            <div class="occupancy-bar"><div class="occupancy-fill" style="width:30%;background:linear-gradient(90deg,var(--green),#69f0ae)"></div></div>
          </div>
          <div style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:6px">
              <span>Zone B — General</span>
              <span style="color:var(--accent2);font-weight:600">27/55 <span style="color:var(--text3);font-weight:400">occupied</span></span>
            </div>
            <div class="occupancy-bar"><div class="occupancy-fill" style="width:49%;background:linear-gradient(90deg,var(--accent),var(--accent2))"></div></div>
          </div>
          <div style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:6px">
              <span>Zone C — Staff</span>
              <span style="color:var(--gold);font-weight:600">22/40 <span style="color:var(--text3);font-weight:400">occupied</span></span>
            </div>
            <div class="occupancy-bar"><div class="occupancy-fill" style="width:55%;background:linear-gradient(90deg,var(--gold),#ffa726)"></div></div>
          </div>
          <div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:6px">
              <span>Zone D — Visitor</span>
              <span style="color:var(--red);font-weight:600">12/20 <span style="color:var(--text3);font-weight:400">occupied</span></span>
            </div>
            <div class="occupancy-bar"><div class="occupancy-fill" style="width:60%;background:linear-gradient(90deg,var(--red),#ff8a65)"></div></div>
          </div>
          <div class="divider-line"></div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:0.82rem">
            <div style="text-align:center;background:var(--surface);padding:12px;border-radius:10px">
              <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text)">79</div>
              <div style="color:var(--text3)">Total Occupied</div>
            </div>
            <div style="text-align:center;background:var(--surface);padding:12px;border-radius:10px">
              <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--green)">32%</div>
              <div style="color:var(--text3)">Overall Occupied</div>
            </div>
          </div>
        </div>

        <!-- Usage Chart -->
        <div class="card animate-fade-up animate-delay-2">
          <div class="card-header">
            <h3 class="card-title">Daily Usage — This Week</h3>
            <span style="font-size:0.78rem;color:var(--text3)">Reservations / day</span>
          </div>
          <canvas id="usageChart" width="400" height="200"></canvas>
          <div style="display:flex;gap:16px;margin-top:12px;font-size:0.78rem">
            <div style="display:flex;align-items:center;gap:6px"><div style="width:12px;height:12px;background:var(--accent);border-radius:3px"></div>Reservations</div>
            <div style="display:flex;align-items:center;gap:6px"><div style="width:12px;height:12px;background:var(--teal);border-radius:3px"></div>Walk-ins</div>
          </div>
        </div>
      </div>

      <div class="grid-2 mt-24">
        <!-- Live Activity -->
        <div class="card animate-fade-up animate-delay-3">
          <div class="card-header">
            <h3 class="card-title">Live Activity Feed</h3>
            <a href="entry-exit.html" style="font-size:0.82rem;color:var(--accent2)">Full log →</a>
          </div>
          <div>
            <div class="activity-row">
              <div class="activity-icon" style="background:rgba(0,200,150,0.15)">🚗</div>
              <div style="flex:1"><div style="font-weight:500">Vehicle Entry — Plate: ABC-1234</div><div style="font-size:0.75rem;color:var(--text3)">Zone A · Gate A · Spot A-12</div></div>
              <div style="font-size:0.75rem;color:var(--text3);white-space:nowrap">Just now</div>
            </div>
            <div class="activity-row">
              <div class="activity-icon" style="background:rgba(255,77,109,0.15)">🚙</div>
              <div style="flex:1"><div style="font-weight:500">Vehicle Exit — Plate: XYZ-5678</div><div style="font-size:0.75rem;color:var(--text3)">Zone B · Gate B · Duration: 2h 14m</div></div>
              <div style="font-size:0.75rem;color:var(--text3);white-space:nowrap">2 min ago</div>
            </div>
            <div class="activity-row">
              <div class="activity-icon" style="background:rgba(61,127,255,0.15)">📅</div>
              <div style="flex:1"><div style="font-weight:500">New Reservation — Renad A.</div><div style="font-size:0.75rem;color:var(--text3)">Spot B-14 · 9:00 AM – 11:30 AM</div></div>
              <div style="font-size:0.75rem;color:var(--text3);white-space:nowrap">5 min ago</div>
            </div>
            <div class="activity-row">
              <div class="activity-icon" style="background:rgba(245,200,66,0.15)">⚠️</div>
              <div style="flex:1"><div style="font-weight:500;color:var(--gold)">Violation — No reservation</div><div style="font-size:0.75rem;color:var(--text3)">Plate: MNO-9012 · Zone C Staff area</div></div>
              <div style="font-size:0.75rem;color:var(--text3);white-space:nowrap">12 min ago</div>
            </div>
            <div class="activity-row">
              <div class="activity-icon" style="background:rgba(0,200,150,0.15)">🚗</div>
              <div style="flex:1"><div style="font-weight:500">Vehicle Entry — Plate: DEF-3456</div><div style="font-size:0.75rem;color:var(--text3)">Zone C · Gate C · Spot C-05</div></div>
              <div style="font-size:0.75rem;color:var(--text3);white-space:nowrap">18 min ago</div>
            </div>
            <div class="activity-row">
              <div class="activity-icon" style="background:rgba(255,77,109,0.15)">✕</div>
              <div style="flex:1"><div style="font-weight:500">Reservation Cancelled — Sara F.</div><div style="font-size:0.75rem;color:var(--text3)">Spot B-08 · Reason: Change of plans</div></div>
              <div style="font-size:0.75rem;color:var(--text3);white-space:nowrap">25 min ago</div>
            </div>
          </div>
        </div>

        <!-- Alerts & Recent Users -->
        <div style="display:flex;flex-direction:column;gap:16px">
          <!-- Alerts -->
          <div class="card animate-fade-up animate-delay-4">
            <div class="card-header">
              <h3 class="card-title">⚠️ Active Alerts</h3>
              <a href="violations.html" style="font-size:0.82rem;color:var(--accent2)">View all →</a>
            </div>
            <div class="alert-item">
              <span style="font-size:1.1rem">🚫</span>
              <div style="flex:1">
                <div style="font-size:0.85rem;font-weight:500;color:var(--red)">Unauthorized parking — Zone C</div>
                <div style="font-size:0.75rem;color:var(--text3)">Plate MNO-9012 · No valid pass · 12 min ago</div>
              </div>
              <button class="btn btn-sm btn-danger" onclick="showToast('Violation Flagged','Security notified','success')">Flag</button>
            </div>
            <div class="alert-item">
              <span style="font-size:1.1rem">⏰</span>
              <div style="flex:1">
                <div style="font-size:0.85rem;font-weight:500;color:var(--gold)">Overstay — Zone B Spot B-03</div>
                <div style="font-size:0.75rem;color:var(--text3)">Reservation expired 45 min ago</div>
              </div>
              <button class="btn btn-sm btn-ghost" onclick="showToast('Notified','Driver has been notified','info')">Notify</button>
            </div>
            <div class="alert-item">
              <span style="font-size:1.1rem">♿</span>
              <div style="flex:1">
                <div style="font-size:0.85rem;font-weight:500;color:var(--gold)">Accessible spot occupied</div>
                <div style="font-size:0.75rem;color:var(--text3)">Spot D-01 · No accessibility permit</div>
              </div>
              <button class="btn btn-sm btn-ghost" onclick="showToast('Alert Sent','Security dispatched','success')">Dispatch</button>
            </div>
          </div>

          <!-- Recent Users -->
          <div class="card animate-fade-up" style="animation-delay:0.5s">
            <div class="card-header">
              <h3 class="card-title">Recent Users</h3>
              <a href="users.html" style="font-size:0.82rem;color:var(--accent2)">All users →</a>
            </div>
            <div>
              <div class="activity-row">
                <div class="avatar" style="background:linear-gradient(135deg,var(--accent),var(--teal))">RA</div>
                <div style="flex:1"><div style="font-size:0.85rem;font-weight:500">Renad Alshammari</div><div style="font-size:0.75rem;color:var(--text3)">Student · Active now</div></div>
                <span class="badge badge-student">Student</span>
              </div>
              <div class="activity-row">
                <div class="avatar" style="background:linear-gradient(135deg,#9c27b0,#673ab7)">RH</div>
                <div style="flex:1"><div style="font-size:0.85rem;font-weight:500">Raghad Hamad</div><div style="font-size:0.75rem;color:var(--text3)">Student · 5 min ago</div></div>
                <span class="badge badge-student">Student</span>
              </div>
              <div class="activity-row">
                <div class="avatar" style="background:linear-gradient(135deg,var(--gold),#ffa726)">SA</div>
                <div style="flex:1"><div style="font-size:0.85rem;font-weight:500">Dr. Sara Al-Amin</div><div style="font-size:0.75rem;color:var(--text3)">Staff · 20 min ago</div></div>
                <span class="badge badge-staff">Staff</span>
              </div>
              <div class="activity-row">
                <div class="avatar" style="background:linear-gradient(135deg,var(--teal),#00b4f5)">AM</div>
                <div style="flex:1"><div style="font-size:0.85rem;font-weight:500">Amjad Moubarak</div><div style="font-size:0.75rem;color:var(--text3)">Student · 1 hour ago</div></div>
                <span class="badge badge-student">Student</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Weekly Chart -->
      <div class="card animate-fade-up mt-24" style="animation-delay:0.6s">
        <div class="card-header">
          <h3 class="card-title">Monthly Reservation Trend</h3>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm active" style="border-color:var(--accent)">Week</button>
            <button class="btn btn-ghost btn-sm">Month</button>
            <button class="btn btn-ghost btn-sm">Year</button>
          </div>
        </div>
        <canvas id="trendChart" width="900" height="200"></canvas>
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
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Zone Name</label>
        <input type="text" class="form-control" placeholder="Zone E" />
      </div>
      <div class="form-group">
        <label class="form-label">Zone Type</label>
        <select class="form-control">
          <option>General</option><option>Staff Only</option><option>Visitor</option><option>Accessible</option>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Total Spots</label>
        <input type="number" class="form-control" placeholder="50" />
      </div>
      <div class="form-group">
        <label class="form-label">Floor / Level</label>
        <select class="form-control">
          <option>Ground Floor</option><option>Level 1</option><option>Level 2</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Description</label>
      <input type="text" class="form-control" placeholder="Near main building entrance" />
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('addZoneModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Zone Created!','Zone E has been added','success');closeModal('addZoneModal')">Create Zone</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('admin', 'admin-dashboard');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

function exportReport() { showToast('Exporting...','Report will be ready in a moment','info'); }

window.addEventListener('load', () => {
  drawBarChart('usageChart',
    ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
    [32,45,38,52,47,28,37], '#3d7fff');

  drawLineChart('trendChart',
    ['Mar 1','Mar 2','Mar 3','Mar 4','Mar 5','Mar 6','Mar 7','Mar 8'],
    [
      { data:[28,35,42,30,48,38,52,47], color:'#3d7fff' },
      { data:[12,18,15,22,20,8,16,14], color:'#00d4b4' }
    ]
  );
});
</script>
</body>
</html>
