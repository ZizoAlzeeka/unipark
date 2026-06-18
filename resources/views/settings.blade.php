<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Settings</title>
<link rel="stylesheet" href="css/style.css">
<style>
.settings-nav{display:flex;flex-direction:column;gap:2px;background:rgba(15,32,64,.7);border:1px solid rgba(255,255,255,.07);border-radius:var(--radius-lg);padding:12px;min-width:200px;position:sticky;top:90px}
.settings-nav-item{padding:10px 14px;border-radius:var(--radius-sm);font-size:.87rem;font-weight:500;color:var(--gray-400);cursor:pointer;transition:var(--transition);display:flex;align-items:center;gap:10px}
.settings-nav-item:hover{color:var(--white);background:rgba(255,255,255,.05)}
.settings-nav-item.active{color:var(--teal-400);background:rgba(0,212,200,.1)}
.settings-panel{display:none}
.settings-panel.active{display:block}
.settings-section{margin-bottom:28px}
.settings-section-title{font-family:'Syne',sans-serif;font-size:.78rem;font-weight:700;color:var(--teal-400);text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,.06)}
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:var(--radius-sm);margin-bottom:8px}
.toggle-row:last-child{margin-bottom:0}
.toggle-info .t-label{font-size:.88rem;font-weight:500;color:var(--white);margin-bottom:2px}
.toggle-info .t-desc{font-size:.76rem;color:var(--gray-500)}
.toggle{width:44px;height:24px;border-radius:12px;cursor:pointer;position:relative;transition:var(--transition);flex-shrink:0}
.toggle.on{background:rgba(0,212,200,.3)}
.toggle.off{background:rgba(255,255,255,.1)}
.toggle-thumb{width:18px;height:18px;border-radius:50%;position:absolute;top:3px;transition:left .2s ease,background .2s ease}
.toggle.on .toggle-thumb{left:calc(100% - 21px);background:var(--teal-400)}
.toggle.off .toggle-thumb{left:3px;background:var(--gray-500)}
</style>
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Settings</span>
      <div class="topbar-actions">
        <button class="topbar-btn" style="position:relative">🔔<div class="notif-dot"></div></button>
        <div class="avatar" style="background:linear-gradient(135deg,var(--amber-500),#ffa726);color:#050d1a">AD</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Admin Dashboard</span> / Settings</div>
        <div class="page-header-row">
          <div><h1>System Settings</h1><p>Configure UniPark system-wide settings and preferences</p></div>
          <button class="btn btn-primary btn-sm" onclick="saveAllSettings()">💾 Save All Changes</button>
        </div>
      </div>

      <div style="display:flex;gap:24px;align-items:flex-start" class="animate-fade-up">
        <!-- Settings Nav -->
        <div class="settings-nav">
          <div class="settings-nav-item active" onclick="switchPanel('general',this)">⚙️ General</div>
          <div class="settings-nav-item" onclick="switchPanel('parking',this)">🅿️ Parking Rules</div>
          <div class="settings-nav-item" onclick="switchPanel('notifications',this)">🔔 Notifications</div>
          <div class="settings-nav-item" onclick="switchPanel('gates',this)">🚧 Gates & ANPR</div>
          <div class="settings-nav-item" onclick="switchPanel('users',this)">👥 User Policy</div>
          <div class="settings-nav-item" onclick="switchPanel('system',this)">🖥️ System</div>
        </div>

        <!-- Settings Content -->
        <div style="flex:1">

          <!-- General -->
          <div class="settings-panel active" id="panel-general">
            <div class="card">
              <div class="card-header"><h3 class="card-title">General Settings</h3></div>
              <div style="padding:22px">
                <div class="settings-section">
                  <div class="settings-section-title">System Identity</div>
                  <div class="form-group">
                    <label class="form-label">System Name</label>
                    <input type="text" class="form-control" value="UniPark — University of Ha'il" />
                  </div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">University Name</label>
                      <input type="text" class="form-control" value="University of Ha'il" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">System Timezone</label>
                      <select class="form-control"><option selected>Asia/Riyadh (AST, UTC+3)</option><option>UTC</option></select>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Default Language</label>
                      <select class="form-control"><option selected>English</option><option>العربية</option></select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Academic Calendar</label>
                      <select class="form-control"><option selected>Gregorian + Hijri</option><option>Gregorian only</option></select>
                    </div>
                  </div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">Contact & Support</div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Support Email</label>
                      <input type="email" class="form-control" value="parking@uoh.edu.sa" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Support Phone</label>
                      <input type="text" class="form-control" value="+966 16 335 0000 ext. 2500" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Parking Rules -->
          <div class="settings-panel" id="panel-parking">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Parking Rules</h3></div>
              <div style="padding:22px">
                <div class="settings-section">
                  <div class="settings-section-title">Reservation Limits</div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Max Reservations per User (Daily)</label>
                      <input type="number" class="form-control" value="2" min="1" max="5" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Max Advance Booking Days</label>
                      <input type="number" class="form-control" value="7" min="1" />
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Min Reservation Duration</label>
                      <select class="form-control"><option>30 minutes</option><option selected>1 hour</option><option>2 hours</option></select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Max Reservation Duration</label>
                      <select class="form-control"><option>4 hours</option><option selected>8 hours</option><option>12 hours</option><option>Full day</option></select>
                    </div>
                  </div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">Overstay & Violations</div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Grace Period After Expiry</label>
                      <select class="form-control"><option>5 minutes</option><option selected>10 minutes</option><option>15 minutes</option></select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Overstay Fine (SAR)</label>
                      <input type="number" class="form-control" value="50" min="0" />
                    </div>
                  </div>
                  <div class="toggle-row">
                    <div class="toggle-info"><div class="t-label">Auto-flag Overstay Violations</div><div class="t-desc">Automatically create violation record after grace period</div></div>
                    <div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div>
                  </div>
                  <div class="toggle-row" style="margin-top:8px">
                    <div class="toggle-info"><div class="t-label">Block User on 3 Violations</div><div class="t-desc">Temporarily suspend user after 3 active violations</div></div>
                    <div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div>
                  </div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">Cancellation Policy</div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Free Cancellation Window</label>
                      <select class="form-control"><option>30 minutes before</option><option selected>1 hour before</option><option>2 hours before</option></select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Late Cancel Penalty</label>
                      <select class="form-control"><option selected>Warning only</option><option>SAR 10 fine</option><option>SAR 25 fine</option></select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Notifications -->
          <div class="settings-panel" id="panel-notifications">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Notification Settings</h3></div>
              <div style="padding:22px">
                <div class="settings-section">
                  <div class="settings-section-title">Email Notifications (System)</div>
                  <div class="toggle-row"><div class="toggle-info"><div class="t-label">Reservation Confirmation Emails</div><div class="t-desc">Send email when reservation is created</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                  <div class="toggle-row" style="margin-top:8px"><div class="toggle-info"><div class="t-label">Pre-Reservation Reminders</div><div class="t-desc">Remind users 30 minutes before reservation</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                  <div class="toggle-row" style="margin-top:8px"><div class="toggle-info"><div class="t-label">Violation Notifications</div><div class="t-desc">Notify users and admin when violation is issued</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                  <div class="toggle-row" style="margin-top:8px"><div class="toggle-info"><div class="t-label">System Maintenance Announcements</div><div class="t-desc">Notify all users before scheduled maintenance</div></div><div class="toggle off" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">Admin Alerts</div>
                  <div class="toggle-row"><div class="toggle-info"><div class="t-label">Occupancy Threshold Alert</div><div class="t-desc">Alert when a zone exceeds 80% occupancy</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                  <div class="toggle-row" style="margin-top:8px"><div class="toggle-info"><div class="t-label">Unauthorized Parking Alert</div><div class="t-desc">Instant alert when ANPR detects unauthorized entry</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">Notification Timing</div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Pre-Reservation Reminder Timing</label>
                      <select class="form-control"><option>15 minutes</option><option selected>30 minutes</option><option>1 hour</option></select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Expiry Warning Timing</label>
                      <select class="form-control"><option>5 minutes</option><option selected>15 minutes</option><option>30 minutes</option></select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Gates & ANPR -->
          <div class="settings-panel" id="panel-gates">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Gates & ANPR Configuration</h3></div>
              <div style="padding:22px">
                <div class="settings-section">
                  <div class="settings-section-title">Campus Gates</div>
                  <div class="table-wrapper">
                    <table>
                      <thead><tr><th>Gate ID</th><th>Name</th><th>Direction</th><th>ANPR Status</th><th>Barrier</th><th>Actions</th></tr></thead>
                      <tbody>
                        <tr>
                          <td style="font-family:'DM Mono',monospace;font-size:.8rem">GATE-A</td>
                          <td><strong>Gate A — Main Entrance</strong></td>
                          <td><span class="badge badge-active">Entry</span></td>
                          <td><span class="badge badge-available">✓ Online</span></td>
                          <td><span class="badge badge-available">✓ Operational</span></td>
                          <td><button class="btn btn-ghost btn-sm" onclick="showToast('Gate Config','Opening gate configuration','info')">⚙️ Config</button></td>
                        </tr>
                        <tr>
                          <td style="font-family:'DM Mono',monospace;font-size:.8rem">GATE-B</td>
                          <td><strong>Gate B — East Exit</strong></td>
                          <td><span class="badge badge-reserved">Exit</span></td>
                          <td><span class="badge badge-available">✓ Online</span></td>
                          <td><span class="badge badge-available">✓ Operational</span></td>
                          <td><button class="btn btn-ghost btn-sm" onclick="showToast('Gate Config','Opening gate configuration','info')">⚙️ Config</button></td>
                        </tr>
                        <tr>
                          <td style="font-family:'DM Mono',monospace;font-size:.8rem">GATE-C</td>
                          <td><strong>Gate C — Staff Entrance</strong></td>
                          <td><span class="badge badge-student">Both</span></td>
                          <td><span class="badge badge-available">✓ Online</span></td>
                          <td><span class="badge badge-occupied">⚠ Fault</span></td>
                          <td><button class="btn btn-ghost btn-sm" onclick="showToast('Maintenance Request','Work order created','success')">🔧 Repair</button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">ANPR Settings</div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Plate Recognition Confidence</label>
                      <select class="form-control"><option>80%</option><option selected>90%</option><option>95%</option></select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Manual Review Threshold</label>
                      <select class="form-control"><option>Below 70%</option><option selected>Below 80%</option><option>All entries</option></select>
                    </div>
                  </div>
                  <div class="toggle-row"><div class="toggle-info"><div class="t-label">Auto-Open for Recognized Plates</div><div class="t-desc">Raise barrier automatically when valid reservation detected</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                </div>
              </div>
            </div>
          </div>

          <!-- User Policy -->
          <div class="settings-panel" id="panel-users">
            <div class="card">
              <div class="card-header"><h3 class="card-title">User & Account Policy</h3></div>
              <div style="padding:22px">
                <div class="settings-section">
                  <div class="settings-section-title">Registration</div>
                  <div class="toggle-row"><div class="toggle-info"><div class="t-label">Allow Self-Registration</div><div class="t-desc">Users can register without admin approval</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                  <div class="toggle-row" style="margin-top:8px"><div class="toggle-info"><div class="t-label">Require Email Verification</div><div class="t-desc">Verify university email before account activation</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                  <div class="toggle-row" style="margin-top:8px"><div class="toggle-info"><div class="t-label">Require Vehicle Registration</div><div class="t-desc">Users must register vehicle before making reservations</div></div><div class="toggle on" onclick="toggleSwitch(this)"><div class="toggle-thumb"></div></div></div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">Vehicle Limits</div>
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label">Max Vehicles per Student</label>
                      <input type="number" class="form-control" value="2" min="1" max="5" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Max Vehicles per Staff</label>
                      <input type="number" class="form-control" value="3" min="1" max="5" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- System -->
          <div class="settings-panel" id="panel-system">
            <div class="card">
              <div class="card-header"><h3 class="card-title">System Configuration</h3></div>
              <div style="padding:22px">
                <div class="settings-section">
                  <div class="settings-section-title">System Status</div>
                  <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                    <div style="padding:14px;background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.15);border-radius:var(--radius-sm)">
                      <div style="font-size:.75rem;color:var(--gray-500);margin-bottom:4px">System Version</div>
                      <div style="font-family:'DM Mono',monospace;font-size:.9rem;color:var(--teal-400)">UniPark v2.4.1</div>
                    </div>
                    <div style="padding:14px;background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.15);border-radius:var(--radius-sm)">
                      <div style="font-size:.75rem;color:var(--gray-500);margin-bottom:4px">Database Status</div>
                      <div style="font-size:.9rem;color:var(--green-400);display:flex;align-items:center;gap:6px"><span style="width:8px;height:8px;background:var(--green-500);border-radius:50%;animation:pulse 2s infinite"></span> Healthy</div>
                    </div>
                    <div style="padding:14px;background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.15);border-radius:var(--radius-sm)">
                      <div style="font-size:.75rem;color:var(--gray-500);margin-bottom:4px">Last Backup</div>
                      <div style="font-family:'DM Mono',monospace;font-size:.85rem;color:var(--white)">Today, 3:00 AM</div>
                    </div>
                    <div style="padding:14px;background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.15);border-radius:var(--radius-sm)">
                      <div style="font-size:.75rem;color:var(--gray-500);margin-bottom:4px">Uptime</div>
                      <div style="font-family:'DM Mono',monospace;font-size:.85rem;color:var(--white)">99.98% (30d)</div>
                    </div>
                  </div>
                </div>
                <div class="settings-section">
                  <div class="settings-section-title">Maintenance</div>
                  <div style="display:flex;flex-direction:column;gap:10px">
                    <button class="btn btn-ghost" onclick="showToast('Backup Started','Database backup initiated','info')" style="justify-content:flex-start;gap:10px">💾 Manual Database Backup</button>
                    <button class="btn btn-ghost" onclick="showToast('Cache Cleared','System cache cleared','success')" style="justify-content:flex-start;gap:10px">🗑️ Clear System Cache</button>
                    <button class="btn btn-ghost" onclick="showToast('Logs Exported','Audit logs downloaded','info')" style="justify-content:flex-start;gap:10px">📋 Export Audit Logs</button>
                  </div>
                </div>
                <div style="padding:16px;background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:var(--radius-sm)">
                  <div style="font-size:.88rem;font-weight:700;color:var(--red-400);margin-bottom:8px">⚠️ Danger Zone</div>
                  <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <button class="btn btn-danger btn-sm" onclick="showToast('Scheduled','Maintenance mode scheduled for tonight','warning')">Enable Maintenance Mode</button>
                    <button class="btn btn-danger btn-sm" onclick="showToast('Reset','This action requires secondary confirmation','error')">Reset All Reservations</button>
                  </div>
                </div>
              </div>
            </div>
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
document.getElementById('sidebarMount').innerHTML = renderSidebar('admin', 'settings');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

function switchPanel(id, el) {
  document.querySelectorAll('.settings-nav-item').forEach(i => i.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('panel-' + id).classList.add('active');
}

function toggleSwitch(el) {
  const isOn = el.classList.contains('on');
  el.classList.toggle('on', !isOn);
  el.classList.toggle('off', isOn);
  showToast('Setting Saved', 'Preference updated', 'success');
}

function saveAllSettings() {
  showToast('Settings Saved', 'All changes have been applied successfully', 'success');
}

// Responsive for settings nav on mobile
if (window.innerWidth < 768) {
  document.querySelector('.settings-nav').style.display = 'none';
}
</script>
</body>
</html>
