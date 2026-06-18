<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Dashboard</title>
<link rel="stylesheet" href="css/style.css">
<style>
.active-reservation {
  background: linear-gradient(135deg, rgba(61,127,255,0.15), rgba(0,212,180,0.1));
  border: 1px solid rgba(61,127,255,0.3);
  border-radius: var(--radius);
  padding: 24px;
  position: relative; overflow: hidden;
}
.active-reservation::before {
  content: '';
  position: absolute; top: 0; right: 0;
  width: 120px; height: 120px;
  background: radial-gradient(circle, rgba(61,127,255,0.2), transparent);
}
.countdown-ring { display: flex; align-items: center; gap: 20px; }
.countdown-block { text-align: center; min-width: 52px; }
.countdown-num { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; color: var(--text); line-height: 1; }
.countdown-lbl { font-size: 0.68rem; color: var(--text3); text-transform: uppercase; letter-spacing: 0.06em; margin-top: 2px; }
.countdown-sep { font-family: 'Syne', sans-serif; font-size: 1.5rem; color: var(--accent2); margin-bottom: 12px; }
.quick-actions { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px,1fr)); gap: 12px; }
.quick-action {
  background: var(--bg2); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 20px 16px; text-align: center;
  cursor: pointer; transition: var(--transition);
}
.quick-action:hover { border-color: var(--border2); transform: translateY(-2px); background: var(--surface); }
.quick-action .qa-icon { font-size: 1.8rem; margin-bottom: 8px; }
.quick-action .qa-label { font-size: 0.82rem; font-weight: 500; color: var(--text2); }
.zone-mini { display: flex; align-items: center; gap: 12px; padding: 14px; border-bottom: 1px solid var(--border); }
.zone-mini:last-child { border-bottom: none; }
.zone-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.zone-name { font-size: 0.88rem; font-weight: 500; flex: 1; }
.zone-avail { font-size: 0.82rem; color: var(--text2); }
.weather-card { background: linear-gradient(135deg, rgba(0,212,180,0.12), rgba(61,127,255,0.08)); border: 1px solid rgba(0,212,180,0.2); border-radius: var(--radius); padding: 20px; }
.notif-item { display: flex; gap: 12px; padding: 14px 0; border-bottom: 1px solid var(--border); }
.notif-item:last-child { border-bottom: none; }
.notif-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.notif-text .title { font-size: 0.88rem; font-weight: 500; color: var(--text); }
.notif-text .sub { font-size: 0.78rem; color: var(--text3); margin-top: 2px; }
.unread-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; margin-top: 6px; flex-shrink: 0; }
</style>
</head>
<body>
<div id="sidebarMount"></div>

<div class="page-wrapper" style="margin-left:260px">
  <!-- Topbar -->
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Dashboard</span>
      <div class="topbar-search">
        <span style="color:var(--text3)">🔍</span>
        <input type="text" placeholder="Search spots, zones..." />
      </div>
      <div class="topbar-actions">
        <button class="topbar-btn" onclick="nav('notifications.html')" style="position:relative">
          🔔<div class="notif-dot"></div>
        </button>
        <div class="avatar" style="cursor:pointer" onclick="nav('profile.html')">RA</div>
      </div>
    </div>

    <!-- Content -->
    <div class="main-content">
      <!-- Welcome -->
      <div class="page-header">
        <p class="text-muted" style="font-size:0.85rem;margin-bottom:4px">Sunday, March 8, 2026</p>
        <h1>Good morning, Renad 👋</h1>
        <p>You have <span class="text-accent fw-700">1 active reservation</span> and <span class="text-success fw-700">2 upcoming</span> this week.</p>
      </div>

      <!-- Stats -->
      <div class="stats-grid animate-fade-up">
        <div class="stat-card blue">
          <div class="stat-icon">🅿️</div>
          <div class="stat-value">247</div>
          <div class="stat-label">Total Campus Spots</div>
          <div class="stat-change up">↑ 12 spots added</div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon">✅</div>
          <div class="stat-value">148</div>
          <div class="stat-label">Available Right Now</div>
          <div class="stat-change up">60% availability</div>
        </div>
        <div class="stat-card teal">
          <div class="stat-icon">📋</div>
          <div class="stat-value">3</div>
          <div class="stat-label">Your Reservations</div>
          <div class="stat-change up">1 active today</div>
        </div>
        <div class="stat-card gold">
          <div class="stat-icon">⏱️</div>
          <div class="stat-value">02:34</div>
          <div class="stat-label">Avg. Search Time Saved</div>
          <div class="stat-change up">↓ 15% vs last week</div>
        </div>
      </div>

      <div class="grid-2 animate-fade-up animate-delay-1">
        <!-- Active Reservation -->
        <div>
          <div class="active-reservation">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
              <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                  <span class="badge badge-active"><span class="pulse">●</span> Active Now</span>
                </div>
                <h3 style="font-family:'Syne',sans-serif;font-size:1.2rem">Spot B-14 · Zone B</h3>
                <p style="font-size:0.85rem;color:var(--text2);margin-top:4px">Ground Floor · Standard · Near Entrance C</p>
              </div>
              <div style="font-size:2.5rem">🅿️</div>
            </div>
            <div class="countdown-ring">
              <div class="countdown-block"><div class="countdown-num" id="cdH">02</div><div class="countdown-lbl">Hours</div></div>
              <div class="countdown-sep">:</div>
              <div class="countdown-block"><div class="countdown-num" id="cdM">18</div><div class="countdown-lbl">Mins</div></div>
              <div class="countdown-sep">:</div>
              <div class="countdown-block"><div class="countdown-num" id="cdS">45</div><div class="countdown-lbl">Secs</div></div>
              <div style="margin-left:auto;text-align:right">
                <div style="font-size:0.78rem;color:var(--text3)">Expires at</div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;color:var(--text)">11:30 AM</div>
              </div>
            </div>
            <div style="display:flex;gap:8px;margin-top:16px">
              <button class="btn btn-ghost btn-sm" onclick="openModal('extendModal')">⏰ Extend</button>
              <button class="btn btn-danger btn-sm" onclick="openModal('cancelModal')">✕ End Early</button>
              <a href="parking-map.html" class="btn btn-ghost btn-sm">🗺️ Navigate</a>
            </div>
          </div>

          <!-- Upcoming -->
          <div class="card mt-16">
            <div class="card-header">
              <h3 class="card-title">Upcoming Reservations</h3>
              <a href="my-reservations.html" style="font-size:0.82rem;color:var(--accent2)">View all →</a>
            </div>
            <div class="timeline">
              <div class="timeline-item">
                <div class="timeline-dot reserve">📅</div>
                <div class="timeline-content">
                  <div class="title">Spot A-07 · Zone A</div>
                  <div class="sub">Tomorrow • 9:00 AM – 1:00 PM</div>
                </div>
                <span class="badge badge-reserved" style="margin-left:auto;white-space:nowrap">Reserved</span>
              </div>
              <div class="timeline-item">
                <div class="timeline-dot reserve">📅</div>
                <div class="timeline-content">
                  <div class="title">Spot C-22 · Zone C</div>
                  <div class="sub">Mar 10 • 8:00 AM – 12:00 PM</div>
                </div>
                <span class="badge badge-reserved" style="margin-left:auto;white-space:nowrap">Reserved</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div style="display:flex;flex-direction:column;gap:16px">
          <!-- Quick Actions -->
          <div class="card">
            <h3 class="card-title" style="margin-bottom:16px">Quick Actions</h3>
            <div class="quick-actions">
              <div class="quick-action" onclick="nav('reserve.html')">
                <div class="qa-icon">🆕</div>
                <div class="qa-label">New Reservation</div>
              </div>
              <div class="quick-action" onclick="nav('parking-map.html')">
                <div class="qa-icon">🗺️</div>
                <div class="qa-label">Live Map</div>
              </div>
              <div class="quick-action" onclick="nav('history.html')">
                <div class="qa-icon">📜</div>
                <div class="qa-label">View History</div>
              </div>
              <div class="quick-action" onclick="nav('profile.html')">
                <div class="qa-icon">🚗</div>
                <div class="qa-label">My Vehicle</div>
              </div>
            </div>
          </div>

          <!-- Zone Availability -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Zone Availability</h3>
              <a href="parking-map.html" style="font-size:0.82rem;color:var(--accent2)">Map →</a>
            </div>
            <div class="zone-mini">
              <div class="zone-dot" style="background:var(--green)"></div>
              <div class="zone-name">Zone A — General</div>
              <div class="zone-avail"><span style="color:var(--green);font-weight:600">42</span> / 60</div>
              <div style="width:60px"><div class="progress-bar"><div class="progress-fill teal" style="width:30%"></div></div></div>
            </div>
            <div class="zone-mini">
              <div class="zone-dot" style="background:var(--accent)"></div>
              <div class="zone-name">Zone B — General</div>
              <div class="zone-avail"><span style="color:var(--accent2);font-weight:600">28</span> / 55</div>
              <div style="width:60px"><div class="progress-bar"><div class="progress-fill blue" style="width:49%"></div></div></div>
            </div>
            <div class="zone-mini">
              <div class="zone-dot" style="background:var(--gold)"></div>
              <div class="zone-name">Zone C — Staff</div>
              <div class="zone-avail"><span style="color:var(--gold);font-weight:600">18</span> / 40</div>
              <div style="width:60px"><div class="progress-bar"><div class="progress-fill" style="width:55%;background:var(--gold)"></div></div></div>
            </div>
            <div class="zone-mini">
              <div class="zone-dot" style="background:var(--red)"></div>
              <div class="zone-name">Zone D — Visitor</div>
              <div class="zone-avail"><span style="color:var(--red);font-weight:600">8</span> / 20</div>
              <div style="width:60px"><div class="progress-bar"><div class="progress-fill red" style="width:60%"></div></div></div>
            </div>
          </div>

          <!-- Notifications -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Notifications</h3>
              <a href="notifications.html" style="font-size:0.82rem;color:var(--accent2)">All →</a>
            </div>
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(61,127,255,0.15)">📅</div>
              <div class="notif-text">
                <div class="title">Reservation Confirmed</div>
                <div class="sub">Spot A-07 reserved for tomorrow 9 AM</div>
              </div>
              <div class="unread-dot"></div>
            </div>
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(255,167,38,0.15)">⏰</div>
              <div class="notif-text">
                <div class="title">Reservation Expiring Soon</div>
                <div class="sub">Spot B-14 expires in 2 hours</div>
              </div>
              <div class="unread-dot"></div>
            </div>
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(0,200,150,0.15)">✅</div>
              <div class="notif-text">
                <div class="title">Entry Recorded</div>
                <div class="sub">Vehicle entered at Gate A • 9:12 AM</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="card animate-fade-up animate-delay-2" style="margin-top:20px">
        <div class="card-header">
          <h3 class="card-title">Recent Activity</h3>
          <a href="history.html" style="font-size:0.82rem;color:var(--accent2)">Full history →</a>
        </div>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Date & Time</th>
                <th>Spot</th>
                <th>Zone</th>
                <th>Duration</th>
                <th>Type</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Mar 8, 2026 · 9:12 AM</td>
                <td><strong>B-14</strong></td>
                <td>Zone B</td>
                <td>Active</td>
                <td>Reserved</td>
                <td><span class="badge badge-active">Active</span></td>
              </tr>
              <tr>
                <td>Mar 7, 2026 · 8:45 AM</td>
                <td><strong>A-03</strong></td>
                <td>Zone A</td>
                <td>3h 15m</td>
                <td>Reserved</td>
                <td><span class="badge badge-available">Completed</span></td>
              </tr>
              <tr>
                <td>Mar 6, 2026 · 9:00 AM</td>
                <td><strong>C-11</strong></td>
                <td>Zone C</td>
                <td>4h 00m</td>
                <td>Walk-in</td>
                <td><span class="badge badge-available">Completed</span></td>
              </tr>
              <tr>
                <td>Mar 4, 2026 · 10:30 AM</td>
                <td><strong>B-08</strong></td>
                <td>Zone B</td>
                <td>—</td>
                <td>Reserved</td>
                <td><span class="badge badge-inactive">Cancelled</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Extend Modal -->
<div class="modal-overlay" id="extendModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Extend Reservation</h3><p style="font-size:0.85rem;margin-top:4px">Spot B-14 · Currently expires at 11:30 AM</p></div>
      <button class="modal-close" onclick="closeModal('extendModal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">Extend by</label>
      <select class="form-control">
        <option>30 minutes</option><option>1 hour</option><option>2 hours</option><option>Until end of day</option>
      </select>
    </div>
    <div style="background:rgba(61,127,255,0.08);border:1px solid rgba(61,127,255,0.2);border-radius:var(--radius-sm);padding:14px;font-size:0.85rem;color:var(--text2)">
      ℹ️ Extension is subject to spot availability after your current booking ends.
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('extendModal')">Cancel</button>
      <button class="btn btn-primary" onclick="extendRes()">Confirm Extension</button>
    </div>
  </div>
</div>

<!-- Cancel Modal -->
<div class="modal-overlay" id="cancelModal">
  <div class="modal" style="max-width:400px">
    <div class="modal-header">
      <div><h3 style="color:var(--red)">End Reservation Early?</h3></div>
      <button class="modal-close" onclick="closeModal('cancelModal')">✕</button>
    </div>
    <p style="color:var(--text2);font-size:0.9rem">Are you sure you want to end your reservation for <strong style="color:var(--text)">Spot B-14</strong> early? This action cannot be undone.</p>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('cancelModal')">Keep Reservation</button>
      <button class="btn btn-danger" onclick="cancelRes()">Yes, End Now</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('student', 'dashboard');

// Countdown timer
let secs = 2 * 3600 + 18 * 60 + 45;
function updateCountdown() {
  if (secs <= 0) { document.getElementById('cdH').textContent='00'; document.getElementById('cdM').textContent='00'; document.getElementById('cdS').textContent='00'; return; }
  secs--;
  const h = Math.floor(secs/3600), m = Math.floor((secs%3600)/60), s = secs%60;
  document.getElementById('cdH').textContent = String(h).padStart(2,'0');
  document.getElementById('cdM').textContent = String(m).padStart(2,'0');
  document.getElementById('cdS').textContent = String(s).padStart(2,'0');
}
setInterval(updateCountdown, 1000);

function extendRes() { showToast('Extended!', 'Reservation extended by 1 hour', 'success'); closeModal('extendModal'); }
function cancelRes() { showToast('Reservation Ended', 'Spot B-14 is now available', 'info'); closeModal('cancelModal'); }

// Responsive sidebar
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});
</script>
</body>
</html>
