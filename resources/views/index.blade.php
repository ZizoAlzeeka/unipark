<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — UniPark</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <img src="https://static.wixstatic.com/media/c71a6e_a40141bf9c9e40fdbfa579f1eb5d48a7~mv2.png" class="auth-logo-img" alt="UniPark">
      <div class="auth-logo-title">Uni<span>Park</span></div>
      <div class="auth-logo-sub">Smart Campus Parking · University of Ha'il</div>
    </div>

    <div style="margin-bottom:20px">
      <div class="form-label" style="text-align:center;margin-bottom:10px">Sign in as</div>
      <div class="role-selector">
        <div class="role-btn active" id="roleStudent" onclick="setRole('student')">
          <span class="role-btn-icon">🎓</span>
          <span class="role-btn-label">Student</span>
        </div>
        <div class="role-btn" id="roleStaff" onclick="setRole('staff')">
          <span class="role-btn-icon">👔</span>
          <span class="role-btn-label">Staff</span>
        </div>
        <div class="role-btn" id="roleAdmin" onclick="setRole('admin')">
          <span class="role-btn-icon">🔑</span>
          <span class="role-btn-label">Admin</span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Email Address</label>
      <input type="email" class="form-input" placeholder="your@uoh.edu.sa" value="student@uoh.edu.sa">
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input type="password" class="form-input" placeholder="••••••••" value="password">
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;color:var(--text-3)">
        <input type="checkbox" checked> Remember me
      </label>
      <a href="#" style="font-size:13px;color:var(--brand-primary);font-weight:600;text-decoration:none">Forgot password?</a>
    </div>

    <button class="btn btn-primary w-full btn-lg" onclick="handleLogin()">Sign In →</button>

    <div style="text-align:center;margin-top:16px;font-size:13px;color:var(--text-3)">
      Don't have an account? <a href="register.html" style="color:var(--brand-primary);font-weight:700;text-decoration:none">Register here</a>
    </div>
    <div style="text-align:center;margin-top:10px">
      <a href="unipark-index.html" style="font-size:12.5px;color:var(--text-4);text-decoration:none">← Back to Home</a>
    </div>
  </div>
</div>

<div id="toastContainer"></div>

<script src="app.js"></script>
<script>
let selectedRole = 'student';

function setRole(role) {
  selectedRole = role;
  document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('role' + role.charAt(0).toUpperCase() + role.slice(1)).classList.add('active');
}

function handleLogin() {
  if (selectedRole === 'admin') {
    window.location.href = 'admin-dashboard.html';
  } else {
    window.location.href = 'dashboard.html';
  }
}
</script>
</body>
</html>
