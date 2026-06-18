<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — My Profile</title>
<link rel="stylesheet" href="css/style.css">
<style>
.profile-hero{background:linear-gradient(135deg,rgba(0,212,200,.12),rgba(61,127,255,.08));border:1px solid rgba(0,212,200,.15);border-radius:var(--radius-lg);padding:28px;display:flex;align-items:center;gap:24px;margin-bottom:24px;position:relative;overflow:hidden}
.profile-hero::before{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:radial-gradient(circle,rgba(0,212,200,.12),transparent 70%);pointer-events:none}
.profile-avatar-lg{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--teal-500),#00a89e);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;color:#050d1a;flex-shrink:0;border:3px solid rgba(0,212,200,.35);box-shadow:0 0 30px rgba(0,212,200,.2);position:relative;cursor:pointer}
.avatar-edit{position:absolute;bottom:0;right:0;width:24px;height:24px;background:var(--teal-500);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#050d1a;cursor:pointer}
.profile-info h2{font-size:1.4rem;font-family:'Syne',sans-serif;margin-bottom:4px}
.profile-info .meta{font-size:.82rem;color:var(--gray-400);font-family:'DM Mono',monospace;display:flex;gap:14px;flex-wrap:wrap;margin-top:6px}
.profile-info .meta span{display:flex;align-items:center;gap:5px}
.profile-tabs{display:flex;gap:4px;background:rgba(255,255,255,.04);border-radius:var(--radius-sm);padding:4px;margin-bottom:24px;width:fit-content}
.profile-tab{padding:8px 20px;border-radius:6px;font-size:.85rem;font-weight:500;color:var(--gray-400);cursor:pointer;transition:var(--transition)}
.profile-tab.active{background:rgba(0,212,200,.12);color:var(--teal-400)}
.profile-tab:hover:not(.active){color:var(--white)}
.section-title{font-family:'Syne',sans-serif;font-size:.78rem;font-weight:700;color:var(--teal-400);text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,.06)}
.vehicle-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:var(--radius-md);padding:18px;display:flex;align-items:center;gap:16px;transition:var(--transition)}
.vehicle-card:hover{border-color:rgba(0,212,200,.2);background:rgba(0,212,200,.04)}
.vehicle-card .plate{font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:800;color:var(--teal-400);background:rgba(0,212,200,.1);padding:8px 16px;border-radius:8px;border:1px solid rgba(0,212,200,.2);letter-spacing:.06em}
.stat-mini{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:var(--radius-md);padding:16px;text-align:center}
.stat-mini .val{font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;color:var(--white);line-height:1;margin-bottom:4px}
.stat-mini .lbl{font-size:.75rem;color:var(--gray-500);font-family:'DM Mono',monospace}
.panel-section{display:none}
.panel-section.active{display:block}
</style>
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">My Profile</span>
      <div class="topbar-actions">
        <button class="topbar-btn" onclick="nav('notifications.html')" style="position:relative">🔔<div class="notif-dot"></div></button>
        <div class="avatar">RA</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Dashboard</span> / My Profile</div>
        <div class="page-header-row">
          <div><h1>My Profile</h1><p>Manage your account information and preferences</p></div>
          <button class="btn btn-primary btn-sm" onclick="saveProfile()">💾 Save Changes</button>
        </div>
      </div>

      <!-- Profile Hero -->
      <div class="profile-hero animate-fade-up">
        <div class="profile-avatar-lg" onclick="document.getElementById('avatarInput').click()">
          RA
          <div class="avatar-edit">✏️</div>
        </div>
        <input type="file" id="avatarInput" style="display:none" accept="image/*">
        <div class="profile-info">
          <h2>Renad Alshammari</h2>
          <p style="color:var(--gray-300);font-size:.9rem">Computer Science & Engineering · University of Ha'il</p>
          <div class="meta">
            <span>🎓 Student</span>
            <span>🪪 ID: 201808190</span>
            <span>📧 s201808190@uoh.edu.sa</span>
            <span>📅 Joined Sep 2023</span>
          </div>
        </div>
        <div style="margin-left:auto;display:flex;flex-direction:column;gap:8px;text-align:right">
          <span class="badge badge-active" style="font-size:.75rem">● Active Account</span>
          <span style="font-size:.78rem;color:var(--gray-500);font-family:'DM Mono',monospace">Last login: today</span>
        </div>
      </div>

      <!-- Mini Stats -->
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;margin-bottom:24px" class="animate-fade-up animate-delay-1">
        <div class="stat-mini"><div class="val" style="color:var(--teal-400)">24</div><div class="lbl">Total Reservations</div></div>
        <div class="stat-mini"><div class="val" style="color:var(--green-400)">22</div><div class="lbl">Completed</div></div>
        <div class="stat-mini"><div class="val" style="color:var(--amber-400)">2h 48m</div><div class="lbl">Avg Duration</div></div>
        <div class="stat-mini"><div class="val" style="color:var(--red-400)">0</div><div class="lbl">Violations</div></div>
        <div class="stat-mini"><div class="val" style="color:var(--teal-400)">Zone B</div><div class="lbl">Favorite Zone</div></div>
      </div>

      <!-- Tabs -->
      <div class="profile-tabs animate-fade-up animate-delay-2">
        <div class="profile-tab active" onclick="switchProfileTab('info',this)">Personal Info</div>
        <div class="profile-tab" onclick="switchProfileTab('vehicle',this)">Vehicle</div>
        <div class="profile-tab" onclick="switchProfileTab('security',this)">Security</div>
        <div class="profile-tab" onclick="switchProfileTab('prefs',this)">Preferences</div>
      </div>

      <!-- Personal Info -->
      <div class="panel-section active animate-fade-up" id="panel-info">
        <div class="grid-2">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Personal Information</h3></div>
            <div style="padding:22px">
              <div class="section-title">Basic Details</div>
              <div class="form-row" style="margin-bottom:16px">
                <div class="form-group" style="margin-bottom:0">
                  <label class="form-label">First Name</label>
                  <input type="text" class="form-control" value="Renad" />
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" value="Alshammari" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">University Email</label>
                <input type="email" class="form-control" value="s201808190@uoh.edu.sa" readonly style="opacity:.7;cursor:not-allowed" />
                <div class="form-hint">University email cannot be changed. Contact IT for assistance.</div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">University ID</label>
                  <input type="text" class="form-control" value="201808190" readonly style="opacity:.7;cursor:not-allowed" />
                </div>
                <div class="form-group">
                  <label class="form-label">Phone Number</label>
                  <input type="tel" class="form-control" value="+966 51 234 5678" />
                </div>
              </div>
              <div class="section-title">Academic Info</div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Role</label>
                  <select class="form-control">
                    <option selected>Student</option>
                    <option>Staff</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Department</label>
                  <select class="form-control">
                    <option selected>Computer Science & Engineering</option>
                    <option>Engineering</option>
                    <option>Medicine</option>
                    <option>Business Administration</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><h3 class="card-title">Account Activity</h3></div>
            <div style="padding:22px">
              <div class="section-title">Recent Sessions</div>
              <div class="activity-row">
                <div class="activity-icon" style="background:rgba(0,212,200,.12);font-size:.9rem">💻</div>
                <div style="flex:1"><div style="font-size:.85rem;color:var(--white);font-weight:500">Chrome · Windows</div><div style="font-size:.75rem;color:var(--gray-500)">Riyadh, SA · Today</div></div>
                <span class="badge badge-active">Current</span>
              </div>
              <div class="activity-row">
                <div class="activity-icon" style="background:rgba(61,127,255,.12);font-size:.9rem">📱</div>
                <div style="flex:1"><div style="font-size:.85rem;color:var(--white);font-weight:500">Mobile Safari · iPhone</div><div style="font-size:.75rem;color:var(--gray-500)">Ha'il, SA · Yesterday</div></div>
                <button class="btn btn-danger btn-sm" onclick="showToast('Session Revoked','Device has been signed out','info')">Revoke</button>
              </div>
              <div class="activity-row">
                <div class="activity-icon" style="background:rgba(61,127,255,.12);font-size:.9rem">💻</div>
                <div style="flex:1"><div style="font-size:.85rem;color:var(--white);font-weight:500">Firefox · Mac</div><div style="font-size:.75rem;color:var(--gray-500)">Ha'il, SA · 3 days ago</div></div>
                <button class="btn btn-danger btn-sm" onclick="showToast('Session Revoked','Device has been signed out','info')">Revoke</button>
              </div>
              <div class="section-title" style="margin-top:20px">Quick Links</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <button class="btn btn-ghost" style="justify-content:flex-start;gap:10px" onclick="nav('my-reservations.html')">📋 View My Reservations</button>
                <button class="btn btn-ghost" style="justify-content:flex-start;gap:10px" onclick="nav('history.html')">📜 Parking History</button>
                <button class="btn btn-ghost" style="justify-content:flex-start;gap:10px" onclick="nav('violations.html')">⚠️ Violations Record</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Vehicle -->
      <div class="panel-section" id="panel-vehicle">
        <div class="grid-2">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Registered Vehicles</h3>
              <button class="btn btn-primary btn-sm" onclick="openModal('addVehicleModal')">+ Add Vehicle</button>
            </div>
            <div style="padding:22px;display:flex;flex-direction:column;gap:14px">
              <div class="vehicle-card">
                <div class="plate">ABC-1234</div>
                <div style="flex:1">
                  <div style="font-weight:600;font-size:.9rem;color:var(--white);margin-bottom:4px">Toyota Camry 2022</div>
                  <div style="font-size:.78rem;color:var(--gray-500)">Sedan · Ha'il Region</div>
                  <span class="badge badge-active" style="margin-top:6px">Primary Vehicle</span>
                </div>
                <button class="btn btn-ghost btn-sm btn-icon" onclick="showToast('Removed','Vehicle removed','info')" title="Remove">🗑️</button>
              </div>
              <div class="vehicle-card">
                <div class="plate" style="color:var(--amber-400);background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.2)">XYZ-5678</div>
                <div style="flex:1">
                  <div style="font-weight:600;font-size:.9rem;color:var(--white);margin-bottom:4px">Toyota Land Cruiser 2020</div>
                  <div style="font-size:.78rem;color:var(--gray-500)">SUV · Ha'il Region</div>
                  <span class="badge badge-reserved" style="margin-top:6px">Secondary</span>
                </div>
                <button class="btn btn-ghost btn-sm btn-icon" onclick="showToast('Removed','Vehicle removed','info')" title="Remove">🗑️</button>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h3 class="card-title">Plate Recognition History</h3></div>
            <div style="padding:22px">
              <div class="info-row"><div class="info-row-label">Last Entry Recorded</div><div class="info-row-value">Today, 9:12 AM — Gate A</div></div>
              <div class="info-row"><div class="info-row-label">Last Exit Recorded</div><div class="info-row-value">Yesterday, 5:30 PM — Gate B</div></div>
              <div class="info-row"><div class="info-row-label">Total Entries This Month</div><div class="info-row-value">18 times</div></div>
              <div class="info-row"><div class="info-row-label">Most Used Gate</div><div class="info-row-value">Gate A (67%)</div></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Security -->
      <div class="panel-section" id="panel-security">
        <div class="grid-2">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Change Password</h3></div>
            <div style="padding:22px">
              <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" class="form-control" placeholder="••••••••" />
              </div>
              <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" placeholder="Min. 8 characters" id="newPass" oninput="updateStrength(this.value)" />
                <div style="height:4px;background:rgba(255,255,255,.06);border-radius:2px;margin-top:8px;overflow:hidden">
                  <div id="passBar" style="height:100%;width:0;border-radius:2px;transition:all .3s"></div>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" placeholder="Repeat new password" />
              </div>
              <button class="btn btn-primary" onclick="showToast('Password Updated','Your password has been changed','success')">Update Password</button>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h3 class="card-title">Security Settings</h3></div>
            <div style="padding:22px">
              <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:var(--radius-sm);margin-bottom:12px">
                <div><div style="font-size:.88rem;font-weight:500;color:var(--white);margin-bottom:2px">Two-Factor Authentication</div><div style="font-size:.75rem;color:var(--gray-500)">Extra security via SMS code</div></div>
                <div onclick="this.classList.toggle('on');showToast('2FA','Setting updated','success')" style="width:44px;height:24px;background:rgba(255,255,255,.1);border-radius:12px;cursor:pointer;position:relative;transition:var(--transition)" id="toggle2fa">
                  <div style="width:18px;height:18px;background:var(--gray-500);border-radius:50%;position:absolute;top:3px;left:3px;transition:var(--transition)" id="toggle2faThumb"></div>
                </div>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:var(--radius-sm);margin-bottom:12px">
                <div><div style="font-size:.88rem;font-weight:500;color:var(--white);margin-bottom:2px">Login Notifications</div><div style="font-size:.75rem;color:var(--gray-500)">Get email on new logins</div></div>
                <div onclick="showToast('Setting Saved','','success')" style="width:44px;height:24px;background:rgba(0,212,200,.3);border-radius:12px;cursor:pointer;position:relative">
                  <div style="width:18px;height:18px;background:var(--teal-400);border-radius:50%;position:absolute;top:3px;right:3px;"></div>
                </div>
              </div>
              <div style="margin-top:20px;padding:14px;background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:var(--radius-sm)">
                <div style="font-size:.88rem;font-weight:600;color:var(--red-400);margin-bottom:6px">⚠️ Danger Zone</div>
                <p style="font-size:.8rem;color:var(--gray-500);margin-bottom:12px">Permanently delete your account and all data. This cannot be undone.</p>
                <button class="btn btn-danger btn-sm" onclick="showToast('Request Sent','Account deletion request submitted to admin','info')">Delete Account</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Preferences -->
      <div class="panel-section" id="panel-prefs">
        <div class="grid-2">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Notification Preferences</h3></div>
            <div style="padding:22px">
              <div class="section-title">Email Notifications</div>
              <div id="prefs-list"></div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h3 class="card-title">Display Settings</h3></div>
            <div style="padding:22px">
              <div class="form-group">
                <label class="form-label">Default Zone on Map</label>
                <select class="form-control">
                  <option>All Zones</option><option selected>Zone B (my usual)</option><option>Zone A</option><option>Zone C</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Date Format</label>
                <select class="form-control">
                  <option>DD/MM/YYYY</option><option selected>MMM D, YYYY</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Time Format</label>
                <select class="form-control">
                  <option selected>12-hour (AM/PM)</option><option>24-hour</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Language</label>
                <select class="form-control">
                  <option selected>English</option><option>العربية</option>
                </select>
              </div>
              <button class="btn btn-primary" onclick="showToast('Preferences Saved','Your settings have been updated','success')">Save Preferences</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal-overlay" id="addVehicleModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Add Vehicle</h3><p style="font-size:.85rem;color:var(--gray-400);margin-top:4px">Register a new vehicle to your account</p></div>
      <button class="modal-close" onclick="closeModal('addVehicleModal')">✕</button>
    </div>
    <div class="form-row" style="margin-bottom:16px">
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">License Plate</label>
        <input type="text" class="form-control" placeholder="ABC-1234" />
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Region</label>
        <select class="form-control"><option>Ha'il</option><option>Riyadh</option><option>Jeddah</option></select>
      </div>
    </div>
    <div class="form-row" style="margin-bottom:16px">
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Make</label>
        <input type="text" class="form-control" placeholder="Toyota" />
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label class="form-label">Model</label>
        <input type="text" class="form-control" placeholder="Camry 2022" />
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Vehicle Type</label>
      <select class="form-control"><option>Sedan</option><option>SUV</option><option>Pickup</option><option>Motorcycle</option></select>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('addVehicleModal')">Cancel</button>
      <button class="btn btn-primary" onclick="showToast('Vehicle Added','Vehicle registered successfully','success');closeModal('addVehicleModal')">Add Vehicle</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('student', 'profile');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

function switchProfileTab(id, el) {
  document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.panel-section').forEach(p => p.classList.remove('active'));
  document.getElementById('panel-' + id).classList.add('active');
}

function saveProfile() {
  showToast('Profile Updated', 'Your changes have been saved successfully', 'success');
}

function updateStrength(v) {
  let s = 0;
  if (v.length >= 8) s += 25;
  if (/[A-Z]/.test(v)) s += 25;
  if (/[0-9]/.test(v)) s += 25;
  if (/[^A-Za-z0-9]/.test(v)) s += 25;
  const bar = document.getElementById('passBar');
  bar.style.width = s + '%';
  bar.style.background = s <= 25 ? '#ef4444' : s <= 50 ? '#f59e0b' : s <= 75 ? '#fbbf24' : '#10b981';
}

// Build notification prefs
const prefs = [
  {label:'Reservation Confirmed', desc:'When a booking is confirmed', on:true},
  {label:'Reservation Reminder', desc:'30 min before reservation starts', on:true},
  {label:'Reservation Expiring', desc:'15 min before end time', on:true},
  {label:'Reservation Cancelled', desc:'When a reservation is cancelled', on:false},
  {label:'Violation Issued', desc:'If a violation is recorded', on:true},
];
document.getElementById('prefs-list').innerHTML = prefs.map((p,i) => `
  <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.05)">
    <div><div style="font-size:.87rem;font-weight:500;color:var(--white)">${p.label}</div><div style="font-size:.75rem;color:var(--gray-500)">${p.desc}</div></div>
    <div onclick="this.dataset.on=this.dataset.on==='1'?'0':'1';this.style.background=this.dataset.on==='1'?'rgba(0,212,200,.3)':'rgba(255,255,255,.1)';this.querySelector('div').style.right=this.dataset.on==='1'?'3px':'auto';this.querySelector('div').style.left=this.dataset.on==='1'?'auto':'3px';this.querySelector('div').style.background=this.dataset.on==='1'?'var(--teal-400)':'var(--gray-500)'"
         data-on="${p.on?'1':'0'}"
         style="width:44px;height:24px;background:${p.on?'rgba(0,212,200,.3)':'rgba(255,255,255,.1)'};border-radius:12px;cursor:pointer;position:relative;flex-shrink:0;transition:var(--transition)">
      <div style="width:18px;height:18px;background:${p.on?'var(--teal-400)':'var(--gray-500)'};border-radius:50%;position:absolute;top:3px;${p.on?'right:3px':'left:3px'};transition:var(--transition)"></div>
    </div>
  </div>
`).join('');
</script>
</body>
</html>
