<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Notifications</title>
<link rel="stylesheet" href="css/style.css">
<style>
.notif-card{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.05);transition:var(--transition);cursor:pointer;position:relative}
.notif-card:hover{background:rgba(255,255,255,.02)}
.notif-card.unread{background:rgba(0,212,200,.03)}
.notif-card.unread::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--teal-500);border-radius:0 2px 2px 0}
.notif-icon-wrap{width:42px;height:42px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
.notif-body{flex:1}
.notif-title{font-size:.9rem;font-weight:600;color:var(--white);margin-bottom:3px;line-height:1.3}
.notif-card.unread .notif-title{color:var(--white)}
.notif-desc{font-size:.8rem;color:var(--gray-400);line-height:1.5;margin-bottom:6px}
.notif-time{font-size:.72rem;color:var(--gray-600);font-family:'DM Mono',monospace}
.unread-pill{width:8px;height:8px;background:var(--teal-500);border-radius:50%;flex-shrink:0;margin-top:6px}
.notif-group-label{font-size:.72rem;font-weight:700;color:var(--gray-600);text-transform:uppercase;letter-spacing:.1em;padding:14px 20px 8px;font-family:'DM Mono',monospace}
</style>
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">Notifications</span>
      <div class="topbar-actions">
        <button class="btn btn-ghost btn-sm" onclick="markAllRead()">✓ Mark all read</button>
        <div class="avatar">RA</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">🏠 <span>Dashboard</span> / Notifications</div>
        <div class="page-header-row">
          <div>
            <h1>Notifications</h1>
            <p>Stay updated on your reservations and campus parking</p>
          </div>
          <div style="display:flex;gap:8px">
            <select class="filter-select" id="notifFilter" onchange="filterNotifs(this.value)">
              <option value="all">All</option>
              <option value="unread">Unread</option>
              <option value="reservation">Reservations</option>
              <option value="violation">Violations</option>
              <option value="system">System</option>
            </select>
            <button class="btn btn-ghost btn-sm" onclick="clearAll()">🗑️ Clear All</button>
          </div>
        </div>
      </div>

      <!-- Stats row -->
      <div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap" class="animate-fade-up">
        <div style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:rgba(0,212,200,.08);border:1px solid rgba(0,212,200,.2);border-radius:var(--radius-sm)">
          <span style="font-size:1rem">🔔</span>
          <span style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--teal-400)" id="unreadCount">3</span>
          <span style="font-size:.8rem;color:var(--gray-400)">unread</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:var(--radius-sm)">
          <span style="font-size:1rem">📋</span>
          <span style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--white)">12</span>
          <span style="font-size:.8rem;color:var(--gray-400)">total this week</span>
        </div>
      </div>

      <div class="card animate-fade-up animate-delay-1">
        <div id="notifList"></div>
        <div style="display:flex;justify-content:center;padding:16px">
          <button class="btn btn-ghost btn-sm" onclick="showToast('Loaded','All notifications shown','info')">Load More</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('student', 'notifications');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

const NOTIFS = [
  { id:1, type:'reservation', unread:true,  icon:'📅', iconBg:'rgba(61,127,255,.15)',  title:'Reservation Confirmed', desc:'Your spot B-14 reservation for today (9:12 AM – 11:30 AM) has been confirmed. QR code is ready for gate entry.', time:'5 minutes ago', group:'Today' },
  { id:2, type:'reservation', unread:true,  icon:'⏰', iconBg:'rgba(245,158,11,.15)', title:'Reservation Expiring Soon', desc:'Your reservation for Spot B-14 expires in 30 minutes. Please proceed to the gate or extend your time.', time:'25 minutes ago', group:'Today' },
  { id:3, type:'system',      unread:true,  icon:'🔔', iconBg:'rgba(0,212,200,.15)',   title:'Reminder: Upcoming Reservation', desc:'You have a reservation for Spot A-07 tomorrow at 9:00 AM. Make sure your vehicle is ready.', time:'1 hour ago', group:'Today' },
  { id:4, type:'reservation', unread:false, icon:'✅', iconBg:'rgba(16,185,129,.15)',  title:'Entry Recorded Successfully', desc:'Your vehicle (ABC-1234) was recorded entering through Gate A at 9:12 AM. Your reservation is now active.', time:'2 hours ago', group:'Today' },
  { id:5, type:'system',      unread:false, icon:'🗺️', iconBg:'rgba(99,102,241,.15)', title:'New Parking Zone Available', desc:'Zone E has been opened on the north campus extension. 45 new spots are now available for reservation.', time:'3 hours ago', group:'Today' },
  { id:6, type:'reservation', unread:false, icon:'📋', iconBg:'rgba(61,127,255,.15)',  title:'Reservation Modified', desc:'Your booking for Spot C-22 on March 10 has been updated. New time: 8:00 AM – 12:00 PM.', time:'Yesterday', group:'Yesterday' },
  { id:7, type:'system',      unread:false, icon:'⚙️', iconBg:'rgba(100,116,139,.2)', title:'System Maintenance', desc:'UniPark will undergo scheduled maintenance on March 12, 2026 from 2:00–4:00 AM. Reservations remain unaffected.', time:'2 days ago', group:'This Week' },
  { id:8, type:'reservation', unread:false, icon:'✕',  iconBg:'rgba(239,68,68,.15)',   title:'Reservation Cancelled', desc:'Your reservation for Spot B-08 on March 4 has been cancelled as requested. The spot has been released.', time:'4 days ago', group:'This Week' },
  { id:9, type:'violation',   unread:false, icon:'⚠️', iconBg:'rgba(245,158,11,.12)', title:'Parking Policy Reminder', desc:'Please ensure you exit your spot within 10 minutes of your reservation end time to avoid overstay fees.', time:'5 days ago', group:'This Week' },
];

let currentFilter = 'all';

function renderNotifs() {
  let filtered = NOTIFS;
  if (currentFilter === 'unread') filtered = NOTIFS.filter(n => n.unread);
  else if (currentFilter !== 'all') filtered = NOTIFS.filter(n => n.type === currentFilter);

  if (filtered.length === 0) {
    document.getElementById('notifList').innerHTML = `<div class="empty-state" style="padding:50px 20px"><div class="icon">🔔</div><p>No notifications found</p></div>`;
    return;
  }

  let html = '';
  let lastGroup = '';
  filtered.forEach(n => {
    if (n.group !== lastGroup) {
      html += `<div class="notif-group-label">${n.group}</div>`;
      lastGroup = n.group;
    }
    html += `
      <div class="notif-card ${n.unread?'unread':''}" onclick="markRead(${n.id})">
        <div class="notif-icon-wrap" style="background:${n.iconBg}">${n.icon}</div>
        <div class="notif-body">
          <div class="notif-title">${n.title}</div>
          <div class="notif-desc">${n.desc}</div>
          <div class="notif-time">${n.time}</div>
        </div>
        ${n.unread ? '<div class="unread-pill"></div>' : ''}
      </div>`;
  });
  document.getElementById('notifList').innerHTML = html;
}

function markRead(id) {
  const n = NOTIFS.find(x => x.id === id);
  if (n && n.unread) {
    n.unread = false;
    const unread = NOTIFS.filter(x => x.unread).length;
    document.getElementById('unreadCount').textContent = unread;
    renderNotifs();
  }
}

function markAllRead() {
  NOTIFS.forEach(n => n.unread = false);
  document.getElementById('unreadCount').textContent = '0';
  renderNotifs();
  showToast('All Read', 'All notifications marked as read', 'success');
}

function filterNotifs(val) {
  currentFilter = val;
  renderNotifs();
}

function clearAll() {
  if (confirm('Clear all notifications?')) {
    NOTIFS.length = 0;
    renderNotifs();
    document.getElementById('unreadCount').textContent = '0';
    showToast('Cleared', 'All notifications removed', 'info');
  }
}

renderNotifs();
</script>
</body>
</html>
