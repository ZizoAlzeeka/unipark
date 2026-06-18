// =====================================================
// UniPark — Smart Campus Parking System
// Vibrant Light Theme Redesign
// =====================================================

// ── State Management ──
const state = {
  currentUser: null,
  currentView: 'landing',
  currentRole: 'Student',
  selectedSpot: null,
  spots: generateSpots()
};

// ── Mock Data Generation ──
function generateSpots() {
  const zones = ['A', 'B', 'C'];
  const data = {};
  zones.forEach(zone => {
    for (let i = 1; i <= 15; i++) {
      const num = i.toString().padStart(2, '0');
      const id = zone + num;
      let status = 'available';
      if (Math.random() > 0.6) status = 'occupied';
      else if (Math.random() > 0.8) status = 'reserved';
      data[id] = { id: id, zone: zone, status: status, type: i % 5 === 0 ? 'disabled' : 'standard' };
    }
  });
  return data;
}

// ── Initialization ──
document.addEventListener('DOMContentLoaded', function () {
  showView('landing');

  // Mobile Nav Interactions
  var navBtns = document.querySelectorAll('.nav-btn');
  navBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      navBtns.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      var view = btn.dataset.view;
      if (view) switchView(view);
    });
  });

  // Role Selection Logic
  document.querySelectorAll('.role-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var parent = e.target.closest('.role-selector');
      if (parent) {
        parent.querySelectorAll('.role-btn').forEach(function (b) { b.classList.remove('active'); });
        e.target.classList.add('active');
        state.currentRole = e.target.textContent.trim();
      }
    });
  });
});

// ── Page-Level Navigation ──
// Shows top-level pages (landing, login, register, dashboard)
function showView(viewId) {
  state.currentView = viewId;

  // Hide all pages
  document.querySelectorAll('.page').forEach(function (el) { el.classList.remove('active'); });

  // Show the target page
  var page = document.getElementById('page-' + viewId);
  if (page) {
    page.classList.add('active');

    // Animate content
    var elements = page.querySelectorAll('.animate-on-load');
    elements.forEach(function (el, index) {
      el.style.opacity = '0';
      el.style.animation = 'none';
      setTimeout(function () {
        el.style.animation = 'fadeUp 0.6s cubic-bezier(0.25, 0.8, 0.25, 1) forwards ' + (index * 0.1) + 's';
      }, 50);
    });
  }
}

// ── Dashboard Inner Navigation ──
// Switches between view panels inside the dashboard page
function switchView(viewId) {
  // Hide all internal panels
  document.querySelectorAll('.view-panel').forEach(function (el) { el.style.display = 'none'; });

  // Show the target panel
  var panel = document.getElementById('view-' + viewId);
  if (panel) {
    panel.style.display = 'block';

    // Animate content inside the panel
    var elements = panel.querySelectorAll('.animate-fade-up');
    elements.forEach(function (el, index) {
      el.style.opacity = '0';
      el.style.animation = 'none';
      setTimeout(function () {
        el.style.animation = 'fadeUp 0.6s cubic-bezier(0.25, 0.8, 0.25, 1) forwards ' + (index * 0.1) + 's';
      }, 50);
    });
  }

  // Update active states on sidebar
  document.querySelectorAll('.nav-item').forEach(function (el) { el.classList.remove('active'); });
  var activeNav = document.querySelector('.nav-item[onclick="switchView(\'' + viewId + '\')"]');
  if (activeNav) activeNav.classList.add('active');

  // Trigger specific renders
  if (viewId === 'map') renderParkingMap();
  if (viewId === 'dashboard') updateDashboardStats();
}

// ── Login / Logout ──
function handleLogin() {
  state.currentUser = { name: "Renad Alshammari", role: state.currentRole, id: "201808190" };

  // Show the dashboard page
  showView('dashboard');

  // Show sidebar
  var sidebar = document.getElementById('mainSidebar');
  if (sidebar) sidebar.style.display = '';

  // Admin-specific logic
  var adminNav = document.getElementById('adminNav');
  if (state.currentRole === 'Admin') {
    // Show admin nav section + route to admin-users view
    if (adminNav) adminNav.style.display = 'block';
    switchView('admin-users');
    // Update sidebar role display
    var roleEl = document.getElementById('sidebarRole');
    if (roleEl) roleEl.textContent = 'Administrator';
  } else if (state.currentRole === 'Staff') {
    if (adminNav) adminNav.style.display = 'block';
    switchView('dashboard');
    var roleEl2 = document.getElementById('sidebarRole');
    if (roleEl2) roleEl2.textContent = 'Staff';
  } else {
    // Hide admin nav for students
    if (adminNav) adminNav.style.display = 'none';
    switchView('dashboard');
    var roleEl3 = document.getElementById('sidebarRole');
    if (roleEl3) roleEl3.textContent = 'Student';
  }

  showToast('Welcome back, Renad! Logged in as ' + state.currentRole, 'success');
}

function handleLogout() {
  state.currentUser = null;
  state.currentRole = 'Student';
  showView('landing');
  var sidebar = document.getElementById('mainSidebar');
  if (sidebar) sidebar.style.display = 'none';
  showToast('Signed out successfully', 'primary');
}

function handleRegister() {
  showToast('Account created successfully!', 'success');
  showView('login');
}

// ── Sidebar Toggle (Mobile) ──
function toggleSidebar() {
  var sidebar = document.getElementById('mainSidebar');
  var overlay = document.getElementById('sidebarOverlay');
  if (sidebar) sidebar.classList.toggle('open');
  if (overlay) overlay.classList.toggle('active');
}

function closeSidebar() {
  var sidebar = document.getElementById('mainSidebar');
  var overlay = document.getElementById('sidebarOverlay');
  if (sidebar) sidebar.classList.remove('open');
  if (overlay) overlay.classList.remove('active');
}

// ── Parking Map functionality ──
function renderParkingMap() {
  var grid = document.getElementById('mapGrid');
  if (!grid) return;
  grid.innerHTML = '';
  Object.values(state.spots).forEach(function (spot) {
    var el = document.createElement('div');
    el.className = 'spot ' + spot.status;
    el.innerHTML = spot.id;
    if (spot.status === 'available') {
      el.onclick = function () { selectSpot(spot); };
    }
    grid.appendChild(el);
  });
}

function selectSpot(spot) {
  state.selectedSpot = spot;
  var spotIdEl = document.getElementById('modalSpotId');
  var spotInfoEl = document.getElementById('modalSpotInfo');
  if (spotIdEl) spotIdEl.textContent = spot.id;
  if (spotInfoEl) spotInfoEl.textContent = 'Zone ' + spot.zone + ' · ' + spot.type;
  openModal('reserveModal');
}

function confirmReservation() {
  if (!state.selectedSpot) return;
  var spotId = state.selectedSpot.id;
  state.spots[spotId].status = 'reserved';
  closeModal('reserveModal');
  showToast('Spot ' + spotId + ' reserved successfully!', 'success');
  if (state.currentView === 'map') renderParkingMap();
  updateDashboardStats();
}

function cancelReservation(spotId) {
  if (state.spots[spotId]) {
    state.spots[spotId].status = 'available';
  }
  showToast('Reservation for ' + spotId + ' cancelled', 'primary');
  updateDashboardStats();
}

function updateDashboardStats() {
  var avail = Object.values(state.spots).filter(function (s) { return s.status === 'available'; }).length;
  var occ = Object.values(state.spots).filter(function (s) { return s.status === 'occupied'; }).length;
  var elAvail = document.getElementById('statAvail');
  var elOcc = document.getElementById('statOcc');
  var elAvailCount = document.getElementById('availCount');
  if (elAvail) elAvail.textContent = avail;
  if (elOcc) elOcc.textContent = occ;
  if (elAvailCount) elAvailCount.textContent = avail;
}

function refreshMap() {
  state.spots = generateSpots();
  renderParkingMap();
  showToast('Parking map refreshed', 'success');
}

// ── Modals ──
function openModal(id) {
  var modal = document.getElementById(id);
  if (modal) modal.classList.add('active');
}

function closeModal(id) {
  var modal = document.getElementById(id);
  if (modal) modal.classList.remove('active');
}

// ── Toasts ──
function showToast(message, type) {
  type = type || 'success';
  var container = document.getElementById('toastContainer');
  if (!container) return;
  var toast = document.createElement('div');
  toast.style.cssText = 'background:#fff;color:#2d3436;padding:15px 25px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.12);margin-bottom:10px;font-weight:600;display:flex;align-items:center;gap:10px;animation:fadeUp 0.3s ease forwards;';
  var icon = 'check-circle';
  var color = '#00C9A7';
  if (type === 'primary') { icon = 'info-circle'; color = '#FF6B6B'; }
  toast.innerHTML = '<i class="fa fa-' + icon + '" style="color:' + color + ';font-size:1.2rem"></i> ' + message;
  container.appendChild(toast);
  setTimeout(function () {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(20px)';
    setTimeout(function () { toast.remove(); }, 300);
  }, 3000);
}

// ── Google Maps Init ──
function initMap() {
  var mapEl = document.getElementById('googleMap');
  if (!mapEl) return;
  var uohLoc = { lat: 27.5385, lng: 41.6963 };
  var map = new google.maps.Map(mapEl, {
    zoom: 15,
    center: uohLoc,
    styles: [{ "featureType": "all", "stylers": [{ "saturation": -20 }, { "lightness": 20 }] }]
  });
  new google.maps.Marker({ position: uohLoc, map: map, title: 'University of Hail' });
}
