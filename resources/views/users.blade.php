<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — User Management</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="sidebarMount"></div>

<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;flex:1">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">☰</button>
      <span class="topbar-title">User Management</span>
      <div class="topbar-search">
        <span style="color:var(--text3)">🔍</span>
        <input type="text" id="userSearch" placeholder="Search by name, ID, or email..." oninput="filterUsers(this.value)" />
      </div>
      <div class="topbar-actions">
        <button class="topbar-btn" onclick="nav('notifications.html')">🔔</button>
        <div class="avatar" style="background:linear-gradient(135deg,var(--gold),#ffa726);cursor:pointer">AD</div>
      </div>
    </div>

    <div class="main-content">
      <div class="page-header">
        <div class="breadcrumb">⚙️ <span>Admin</span> / User Management</div>
        <div class="page-header-row">
          <div><h1>Users</h1><p>Manage all registered students, staff and administrators</p></div>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm" onclick="exportUsers()">📤 Export</button>
            <button class="btn btn-primary btn-sm" onclick="openModal('addUserModal')">+ Add User</button>
          </div>
        </div>
      </div>

      <!-- Stats Row -->
      <div class="stats-grid" style="margin-bottom:20px">
        <div class="stat-card blue"><div class="stat-icon">👥</div><div class="stat-value">156</div><div class="stat-label">Total Users</div></div>
        <div class="stat-card teal"><div class="stat-icon">🎓</div><div class="stat-value">128</div><div class="stat-label">Students</div></div>
        <div class="stat-card gold"><div class="stat-icon">👩‍💼</div><div class="stat-value">24</div><div class="stat-label">Staff</div></div>
        <div class="stat-card green"><div class="stat-icon">✅</div><div class="stat-value">149</div><div class="stat-label">Active Accounts</div></div>
        <div class="stat-card red"><div class="stat-icon">🚫</div><div class="stat-value">7</div><div class="stat-label">Suspended</div></div>
      </div>

      <!-- Filters -->
      <div class="filter-bar">
        <div class="search-box">
          <span>🔍</span>
          <input type="text" placeholder="Search users..." oninput="filterUsers(this.value)" />
        </div>
        <select class="filter-select" onchange="filterRole(this.value)">
          <option value="">All Roles</option>
          <option value="student">Students</option>
          <option value="staff">Staff</option>
          <option value="admin">Admins</option>
        </select>
        <select class="filter-select">
          <option>All Status</option>
          <option>Active</option>
          <option>Suspended</option>
          <option>Pending</option>
        </select>
        <select class="filter-select">
          <option>All Departments</option>
          <option>Computer Science</option>
          <option>Engineering</option>
          <option>Medicine</option>
          <option>Business</option>
        </select>
      </div>

      <!-- Table -->
      <div class="card">
        <div class="table-wrapper">
          <table id="usersTable">
            <thead>
              <tr>
                <th style="width:40px"><input type="checkbox" style="accent-color:var(--accent)" onchange="selectAll(this)"></th>
                <th>User</th>
                <th>ID</th>
                <th>Role</th>
                <th>Department</th>
                <th>Vehicle</th>
                <th>Status</th>
                <th>Last Active</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="usersBody"></tbody>
          </table>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:16px;flex-wrap:wrap;gap:12px">
          <div style="font-size:0.82rem;color:var(--text3)">Showing <strong style="color:var(--text)">1–10</strong> of 156 users</div>
          <div style="display:flex;gap:6px">
            <button class="btn btn-ghost btn-sm">← Prev</button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-ghost btn-sm">2</button>
            <button class="btn btn-ghost btn-sm">3</button>
            <button class="btn btn-ghost btn-sm">Next →</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal-overlay" id="addUserModal">
  <div class="modal">
    <div class="modal-header">
      <div><h3>Add New User</h3><p style="font-size:0.85rem;margin-top:4px">Register a new student or staff member</p></div>
      <button class="modal-close" onclick="closeModal('addUserModal')">✕</button>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">First Name</label>
        <input type="text" class="form-control" placeholder="Renad" />
      </div>
      <div class="form-group">
        <label class="form-label">Last Name</label>
        <input type="text" class="form-control" placeholder="Alshammari" />
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">University Email</label>
      <input type="email" class="form-control" placeholder="s202300000@uoh.edu.sa" />
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">University ID</label>
        <input type="text" class="form-control" placeholder="202300000" />
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-control">
          <option>Student</option><option>Staff</option><option>Admin</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Department</label>
      <select class="form-control">
        <option>Computer Science and Engineering</option>
        <option>Engineering</option><option>Medicine</option>
        <option>Business Administration</option><option>Science</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Vehicle Plate</label>
      <input type="text" class="form-control" placeholder="ABC-1234" />
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('addUserModal')">Cancel</button>
      <button class="btn btn-primary" onclick="addUser()">Create Account</button>
    </div>
  </div>
</div>

<!-- View User Modal -->
<div class="modal-overlay" id="viewUserModal">
  <div class="modal">
    <div class="modal-header">
      <h3>User Details</h3>
      <button class="modal-close" onclick="closeModal('viewUserModal')">✕</button>
    </div>
    <div id="viewUserContent"></div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('admin', 'users');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', () => {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

const users = [
  { name:'Renad Alshammari', id:'201808190', email:'s201808190@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'ABC-1234', status:'active', lastActive:'Just now', initials:'RA', color:'linear-gradient(135deg,var(--accent),var(--teal))' },
  { name:'Raghad Hamad', id:'202003565', email:'s202003565@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'XYZ-5678', status:'active', lastActive:'5 min ago', initials:'RH', color:'linear-gradient(135deg,#9c27b0,#673ab7)' },
  { name:'Hala Alshammari', id:'201907684', email:'s201907684@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'DEF-3456', status:'active', lastActive:'20 min ago', initials:'HA', color:'linear-gradient(135deg,var(--red),#ff8a65)' },
  { name:'Amjad Alshammri', id:'202006088', email:'s202006088@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'GHI-7890', status:'active', lastActive:'1 hour ago', initials:'AA', color:'linear-gradient(135deg,var(--gold),#ffa726)' },
  { name:'Amjad Moubarak', id:'201902111', email:'s201902111@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'JKL-2345', status:'active', lastActive:'2 hours ago', initials:'AM', color:'linear-gradient(135deg,var(--teal),#00b4f5)' },
  { name:'Kholood Alshammri', id:'202005028', email:'s202005028@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'MNO-6789', status:'suspended', lastActive:'3 days ago', initials:'KA', color:'linear-gradient(135deg,#607d8b,#455a64)' },
  { name:'Ghazal Alsharif', id:'201907494', email:'s201907494@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'PQR-0123', status:'active', lastActive:'Yesterday', initials:'GA', color:'linear-gradient(135deg,#e91e63,#c2185b)' },
  { name:'Lamia Almufdhali', id:'202003122', email:'s202003122@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'STU-4567', status:'active', lastActive:'Yesterday', initials:'LA', color:'linear-gradient(135deg,#4caf50,#2e7d32)' },
  { name:'Sara Alanzi', id:'202003738', email:'s202003738@uoh.edu.sa', role:'student', dept:'Computer Science', plate:'VWX-8901', status:'active', lastActive:'2 days ago', initials:'SA', color:'linear-gradient(135deg,#ff9800,#e65100)' },
  { name:'Dr. Sara Al-Amin', id:'STF-00142', email:'s.alamin@uoh.edu.sa', role:'staff', dept:'Engineering', plate:'YZA-2345', status:'active', lastActive:'30 min ago', initials:'SA', color:'linear-gradient(135deg,var(--gold),#ffa726)' },
];

function renderUsers(list) {
  const tbody = document.getElementById('usersBody');
  tbody.innerHTML = list.map((u, i) => `
    <tr>
      <td><input type="checkbox" style="accent-color:var(--accent)"></td>
      <td>
        <div style="display:flex;align-items:center;gap:10px">
          <div class="avatar" style="background:${u.color};width:32px;height:32px;font-size:0.75rem;flex-shrink:0">${u.initials}</div>
          <div>
            <div style="font-weight:600;font-size:0.88rem">${u.name}</div>
            <div style="font-size:0.75rem;color:var(--text3)">${u.email}</div>
          </div>
        </div>
      </td>
      <td style="font-family:'Syne',sans-serif;font-size:0.85rem">${u.id}</td>
      <td><span class="badge badge-${u.role}">${u.role.charAt(0).toUpperCase()+u.role.slice(1)}</span></td>
      <td style="font-size:0.85rem;color:var(--text2)">${u.dept}</td>
      <td style="font-family:'Syne',sans-serif;font-size:0.82rem;color:var(--accent2)">${u.plate}</td>
      <td><span class="badge badge-${u.status==='active'?'active':'inactive'}">${u.status==='active'?'● Active':'⊘ Suspended'}</span></td>
      <td style="font-size:0.8rem;color:var(--text3)">${u.lastActive}</td>
      <td>
        <div style="display:flex;gap:6px">
          <button class="btn btn-ghost btn-sm btn-icon" title="View" onclick="viewUser(${i})">👁️</button>
          <button class="btn btn-ghost btn-sm btn-icon" title="Edit" onclick="showToast('Edit User','Opening edit form','info')">✏️</button>
          <button class="btn ${u.status==='active'?'btn-danger':'btn-success'} btn-sm btn-icon" title="${u.status==='active'?'Suspend':'Activate'}"
            onclick="toggleStatus(this,'${u.name}','${u.status}')">${u.status==='active'?'🚫':'✅'}</button>
        </div>
      </td>
    </tr>
  `).join('');
}

renderUsers(users);

function filterUsers(q) {
  const filtered = users.filter(u =>
    u.name.toLowerCase().includes(q.toLowerCase()) ||
    u.id.includes(q) || u.email.includes(q)
  );
  renderUsers(filtered);
}

function filterRole(role) {
  const filtered = role ? users.filter(u => u.role === role) : users;
  renderUsers(filtered);
}

function viewUser(i) {
  const u = users[i];
  document.getElementById('viewUserContent').innerHTML = `
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding:16px;background:var(--surface);border-radius:var(--radius-sm)">
      <div class="avatar" style="background:${u.color};width:56px;height:56px;font-size:1.1rem">${u.initials}</div>
      <div>
        <div style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:700">${u.name}</div>
        <div style="font-size:0.82rem;color:var(--text3)">${u.email}</div>
        <span class="badge badge-${u.role}" style="margin-top:4px">${u.role}</span>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:0.85rem">
      <div style="background:var(--surface);padding:12px;border-radius:8px"><div style="color:var(--text3);margin-bottom:2px">University ID</div><div style="font-weight:600">${u.id}</div></div>
      <div style="background:var(--surface);padding:12px;border-radius:8px"><div style="color:var(--text3);margin-bottom:2px">Department</div><div style="font-weight:600">${u.dept}</div></div>
      <div style="background:var(--surface);padding:12px;border-radius:8px"><div style="color:var(--text3);margin-bottom:2px">Vehicle Plate</div><div style="font-weight:600;color:var(--accent2)">${u.plate}</div></div>
      <div style="background:var(--surface);padding:12px;border-radius:8px"><div style="color:var(--text3);margin-bottom:2px">Status</div><span class="badge badge-${u.status==='active'?'active':'inactive'}">${u.status}</span></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('viewUserModal')">Close</button>
      <button class="btn btn-primary" onclick="showToast('Edit Mode','Opening editor','info');closeModal('viewUserModal')">Edit User</button>
    </div>
  `;
  openModal('viewUserModal');
}

function toggleStatus(btn, name, currentStatus) {
  const newStatus = currentStatus === 'active' ? 'suspended' : 'active';
  showToast(name, `Account ${newStatus}`, newStatus === 'active' ? 'success' : 'info');
}

function addUser() {
  showToast('User Added!', 'New account created and credentials sent', 'success');
  closeModal('addUserModal');
}

function selectAll(cb) {
  document.querySelectorAll('#usersBody input[type="checkbox"]').forEach(c => c.checked = cb.checked);
}

function exportUsers() {
  showToast('Exporting...', 'User list will download shortly', 'info');
}
</script>
</body>
</html>
