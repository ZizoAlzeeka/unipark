<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Reports & Analytics</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Reports & Analytics</span>
      <div class="topbar-actions">
        <button class="topbar-btn">🔔</button>
        <div class="avatar" style="background:linear-gradient(135deg,var(--gold),#ffa726)">AD</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">⚙️ <span>Admin</span> / Reports</div>
        <div class="page-header-row">
          <div><h1>Reports & Analytics</h1><p>Usage statistics, trends, and performance insights</p></div>
          <div style="display:flex;gap:8px">
            <select class="filter-select">
              <option>Last 7 Days</option><option>Last 30 Days</option><option>This Month</option><option>Custom Range</option>
            </select>
            <button class="btn btn-primary btn-sm" onclick="showToast('Downloading...','Report ready in PDF format','info')">📄 Export PDF</button>
          </div>
        </div>
      </div>

      <!-- Summary KPIs -->
      <div class="stats-grid animate-fade-up">
        <div class="stat-card blue"><div class="stat-icon">📋</div><div class="stat-value">312</div><div class="stat-label">Total Reservations</div><div class="stat-change up">↑ 18% vs last week</div></div>
        <div class="stat-card green"><div class="stat-icon">✅</div><div class="stat-value">284</div><div class="stat-label">Completed</div><div class="stat-change up">91% completion rate</div></div>
        <div class="stat-card red"><div class="stat-icon">✕</div><div class="stat-value">19</div><div class="stat-label">Cancellations</div><div class="stat-change down">↑ 2% vs last week</div></div>
        <div class="stat-card gold"><div class="stat-icon">⏱️</div><div class="stat-value">2h 22m</div><div class="stat-label">Avg Duration</div><div class="stat-change up">↑ 8 min</div></div>
        <div class="stat-card teal"><div class="stat-icon">👥</div><div class="stat-value">89</div><div class="stat-label">Unique Users</div><div class="stat-change up">+12 new users</div></div>
      </div>

      <div class="grid-2 animate-fade-up animate-delay-1">
        <!-- Reservations Over Time -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Reservations Over Time</h3>
            <div style="display:flex;gap:6px">
              <button class="btn btn-ghost btn-sm" style="border-color:var(--accent)">Daily</button>
              <button class="btn btn-ghost btn-sm">Weekly</button>
            </div>
          </div>
          <canvas id="resChart" width="500" height="200"></canvas>
        </div>

        <!-- Occupancy by Zone -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Occupancy by Zone</h3>
            <span style="font-size:0.78rem;color:var(--text3)">Average this week</span>
          </div>
          <canvas id="zoneChart" width="500" height="200"></canvas>
        </div>
      </div>

      <div class="grid-2 animate-fade-up animate-delay-2 mt-24">
        <!-- Peak Hours -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Peak Usage Hours</h3>
            <span style="font-size:0.78rem;color:var(--text3)">Average entries per hour</span>
          </div>
          <canvas id="peakChart" width="500" height="180"></canvas>
        </div>

        <!-- Donut: Spot Types -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Usage by Spot Type</h3>
            <span style="font-size:0.78rem;color:var(--text3)">This month</span>
          </div>
          <div style="display:flex;align-items:center;gap:32px">
            <div class="donut-chart" style="position:relative;flex-shrink:0">
              <canvas id="typeDonut" width="160" height="160"></canvas>
              <div class="donut-center">
                <div class="val">312</div>
                <div class="lbl">Total</div>
              </div>
            </div>
            <div style="flex:1">
              <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                <div style="width:12px;height:12px;background:var(--accent);border-radius:3px"></div>
                <div style="flex:1;font-size:0.85rem">Standard</div>
                <div style="font-weight:700;font-size:0.9rem">218</div>
                <div style="font-size:0.8rem;color:var(--text3)">70%</div>
              </div>
              <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                <div style="width:12px;height:12px;background:var(--gold);border-radius:3px"></div>
                <div style="flex:1;font-size:0.85rem">Staff</div>
                <div style="font-weight:700;font-size:0.9rem">72</div>
                <div style="font-size:0.8rem;color:var(--text3)">23%</div>
              </div>
              <div style="display:flex;align-items:center;gap:10px">
                <div style="width:12px;height:12px;background:var(--teal);border-radius:3px"></div>
                <div style="flex:1;font-size:0.85rem">Accessible</div>
                <div style="font-weight:700;font-size:0.9rem">22</div>
                <div style="font-size:0.8rem;color:var(--text3)">7%</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Users Table -->
      <div class="card animate-fade-up animate-delay-3 mt-24">
        <div class="card-header">
          <h3 class="card-title">Top 10 Most Active Users</h3>
          <button class="btn btn-ghost btn-sm" onclick="showToast('Exporting','Table exported','info')">📤 Export</button>
        </div>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr><th>Rank</th><th>User</th><th>Role</th><th>Reservations</th><th>Completed</th><th>Avg Duration</th><th>Favorite Zone</th></tr>
            </thead>
            <tbody>
              <tr><td><span style="font-family:'Syne',sans-serif;font-weight:800;color:var(--gold)">🥇</span></td><td><div style="display:flex;align-items:center;gap:8px"><div class="avatar" style="width:28px;height:28px;font-size:0.7rem;background:linear-gradient(135deg,var(--accent),var(--teal))">RA</div>Renad Alshammari</div></td><td><span class="badge badge-student">Student</span></td><td>28</td><td>26 (93%)</td><td>3h 10m</td><td>Zone B</td></tr>
              <tr><td><span style="font-family:'Syne',sans-serif;font-weight:800;color:var(--text2)">🥈</span></td><td><div style="display:flex;align-items:center;gap:8px"><div class="avatar" style="width:28px;height:28px;font-size:0.7rem;background:linear-gradient(135deg,var(--gold),#ffa726)">SA</div>Dr. Sara Al-Amin</div></td><td><span class="badge badge-staff">Staff</span></td><td>24</td><td>24 (100%)</td><td>6h 00m</td><td>Zone C</td></tr>
              <tr><td><span style="font-family:'Syne',sans-serif;font-weight:800;color:var(--red)">🥉</span></td><td><div style="display:flex;align-items:center;gap:8px"><div class="avatar" style="width:28px;height:28px;font-size:0.7rem;background:linear-gradient(135deg,#9c27b0,#673ab7)">RH</div>Raghad Hamad</div></td><td><span class="badge badge-student">Student</span></td><td>21</td><td>19 (90%)</td><td>2h 45m</td><td>Zone A</td></tr>
              <tr><td style="color:var(--text3)">4</td><td><div style="display:flex;align-items:center;gap:8px"><div class="avatar" style="width:28px;height:28px;font-size:0.7rem;background:linear-gradient(135deg,var(--teal),#00b4f5)">AM</div>Amjad Moubarak</div></td><td><span class="badge badge-student">Student</span></td><td>18</td><td>17 (94%)</td><td>2h 20m</td><td>Zone A</td></tr>
              <tr><td style="color:var(--text3)">5</td><td><div style="display:flex;align-items:center;gap:8px"><div class="avatar" style="width:28px;height:28px;font-size:0.7rem;background:linear-gradient(135deg,#e91e63,#c2185b)">GA</div>Ghazal Alsharif</div></td><td><span class="badge badge-student">Student</span></td><td>15</td><td>14 (93%)</td><td>3h 30m</td><td>Zone B</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('admin', 'reports');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

window.addEventListener('load', () => {
  drawLineChart('resChart',
    ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
    [
      { data:[38,52,45,60,55,30,48], color:'#3d7fff' },
      { data:[22,28,20,32,28,14,24], color:'#00d4b4' }
    ]
  );

  drawBarChart('zoneChart',
    ['Zone A','Zone B','Zone C','Zone D'],
    [72, 88, 62, 40], '#6c9fff');

  drawBarChart('peakChart',
    ['7AM','8AM','9AM','10AM','11AM','12PM','1PM','2PM','3PM','4PM'],
    [5, 22, 38, 30, 25, 18, 14, 20, 16, 8], '#00d4b4');

  drawDonut('typeDonut',
    [218, 72, 22],
    ['#3d7fff', '#f5c842', '#00d4b4']
  );
});
</script>
</body>
</html>
