<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — UniPark</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-card" style="max-width:500px">
    <div class="auth-logo">
      <img src="https://static.wixstatic.com/media/c71a6e_a40141bf9c9e40fdbfa579f1eb5d48a7~mv2.png" class="auth-logo-img" alt="UniPark">
      <div class="auth-logo-title">Uni<span>Park</span></div>
      <div class="auth-logo-sub">Create your account</div>
    </div>

    <div class="step-bar" id="stepBar">
      <div class="step-bar-item active" id="stepItem1">
        <div class="step-circle">1</div>
        <div class="step-name">Account</div>
      </div>
      <div class="step-bar-item" id="stepItem2">
        <div class="step-circle">2</div>
        <div class="step-name">Profile</div>
      </div>
      <div class="step-bar-item" id="stepItem3">
        <div class="step-circle">3</div>
        <div class="step-name">Vehicle</div>
      </div>
    </div>

    <!-- Step 1 -->
    <div class="step-panel active" id="panel1">
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-input" placeholder="e.g. Renad Alshammari">
      </div>
      <div class="form-group">
        <label class="form-label">University Email</label>
        <input type="email" class="form-input" placeholder="your@uoh.edu.sa">
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" class="form-input" id="pwInput" placeholder="Min 8 characters" oninput="updateStrength(this.value)">
        <div class="strength-bar"><div class="strength-fill" id="strengthFill" style="width:0%"></div></div>
      </div>
      <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <input type="password" class="form-input" placeholder="Repeat password">
      </div>
      <button class="btn btn-primary w-full" onclick="goStep(2)">Continue →</button>
    </div>

    <!-- Step 2 -->
    <div class="step-panel" id="panel2">
      <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-select">
          <option>Student</option>
          <option>Staff</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Student / Staff ID</label>
          <input type="text" class="form-input" placeholder="e.g. S-12345">
        </div>
        <div class="form-group">
          <label class="form-label">Department</label>
          <input type="text" class="form-input" placeholder="e.g. Engineering">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Phone Number</label>
        <input type="tel" class="form-input" placeholder="+966 5X XXX XXXX">
      </div>
      <div style="display:flex;gap:10px">
        <button class="btn btn-ghost w-full" onclick="goStep(1)">← Back</button>
        <button class="btn btn-primary w-full" onclick="goStep(3)">Continue →</button>
      </div>
    </div>

    <!-- Step 3 -->
    <div class="step-panel" id="panel3">
      <div class="form-group">
        <label class="form-label">Vehicle Type</label>
        <div class="vehicle-options">
          <div class="vehicle-option selected" onclick="selectVehicle(this)">
            <span class="vehicle-option-icon">🚗</span>
            <span class="vehicle-option-label">Sedan</span>
          </div>
          <div class="vehicle-option" onclick="selectVehicle(this)">
            <span class="vehicle-option-icon">🚙</span>
            <span class="vehicle-option-label">SUV</span>
          </div>
          <div class="vehicle-option" onclick="selectVehicle(this)">
            <span class="vehicle-option-icon">🏍️</span>
            <span class="vehicle-option-label">Motorbike</span>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">License Plate</label>
          <input type="text" class="form-input" placeholder="e.g. ABC 1234">
        </div>
        <div class="form-group">
          <label class="form-label">Vehicle Color</label>
          <input type="text" class="form-input" placeholder="e.g. White">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Vehicle Model</label>
        <input type="text" class="form-input" placeholder="e.g. Toyota Camry 2022">
      </div>
      <div style="display:flex;gap:10px">
        <button class="btn btn-ghost w-full" onclick="goStep(2)">← Back</button>
        <button class="btn btn-success w-full" onclick="finish()">✅ Create Account</button>
      </div>
    </div>

    <div style="text-align:center;margin-top:16px;font-size:13px;color:var(--text-3)">
      Already registered? <a href="index.html" style="color:var(--brand-primary);font-weight:700;text-decoration:none">Sign in</a>
    </div>
  </div>
</div>
<div id="toastContainer"></div>
<script src="app.js"></script>
<script>
let currentStep = 1;

function goStep(n) {
  document.getElementById('panel' + currentStep).classList.remove('active');
  const items = document.querySelectorAll('.step-bar-item');
  items[currentStep - 1].classList.remove('active');
  items[currentStep - 1].classList.add('done');
  if (n < currentStep) {
    items[currentStep - 1].classList.remove('done');
    items[n - 1].classList.remove('done');
  }
  currentStep = n;
  document.getElementById('panel' + currentStep).classList.add('active');
  items[currentStep - 1].classList.remove('done');
  items[currentStep - 1].classList.add('active');
}

function updateStrength(val) {
  const fill = document.getElementById('strengthFill');
  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const colors = ['var(--red)', 'var(--amber)', 'var(--amber)', 'var(--green)'];
  fill.style.width = (score * 25) + '%';
  fill.style.background = colors[score - 1] || 'var(--border)';
}

function selectVehicle(el) {
  document.querySelectorAll('.vehicle-option').forEach(v => v.classList.remove('selected'));
  el.classList.add('selected');
}

function finish() {
  window.location.href = 'dashboard.html';
}
</script>
</body>
</html>
