<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniPark — Parking Map</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* map-specific overrides — general classes handled by style.css */
#mapLoading{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(241,245,249,.92);z-index:10;flex-direction:column;gap:14px}
.map-mode-btn{padding:5px 11px;border:1.5px solid rgba(79,70,229,.18);border-radius:20px;background:#fff;font-size:.72rem;font-weight:700;cursor:pointer;color:#4f46e5;transition:all .2s;white-space:nowrap;font-family:inherit;}
.map-mode-btn:hover,.map-mode-btn.active{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border-color:transparent;box-shadow:0 4px 12px rgba(79,70,229,.35);}
.map-search-box{display:flex;align-items:center;gap:6px;background:#f8fafc;border:1.5px solid rgba(79,70,229,.15);border-radius:20px;padding:5px 12px;font-size:.8rem;}
.map-search-box input{border:none;background:transparent;outline:none;font-size:.8rem;font-family:inherit;color:#1e293b;width:120px;}
.map-search-box input::placeholder{color:#94a3b8;}

/* Custom premium map controls and Street View panel */
.custom-map-controls {
  position: absolute;
  right: 16px;
  bottom: 80px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  z-index: 5;
}
.map-ctrl-btn {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background: #ffffff;
  border: 1.5px solid rgba(79, 70, 229, 0.18);
  font-size: 1.25rem;
  font-weight: bold;
  cursor: pointer;
  color: #4f46e5;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  transition: all 0.2s ease;
}
.map-ctrl-btn:hover {
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  color: #ffffff;
  border-color: transparent;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
}
.map-ctrl-btn:active {
  transform: translateY(0);
}
#googleStreetView {
  height: 0;
  width: 100%;
  transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border-bottom: 2px solid rgba(79, 70, 229, 0.15);
  background: #000000;
}
</style>
</head>
<body>
<div id="sidebarMount"></div>
<div class="page-wrapper" id="pageWrapper">
  <div style="display:flex;flex-direction:column;height:100vh">
    <div class="topbar">
      <button class="topbar-btn menu-btn" onclick="toggleSidebar()">&#9776;</button>
      <span class="topbar-title">Parking Map</span>
      <div style="display:flex;gap:4px;margin-left:8px;flex-wrap:wrap" id="zonePills">
        <div class="zone-pill active" style="color:var(--primary)" onclick="filterZone('all',this)">All</div>
        <div class="zone-pill" style="color:#059669" onclick="filterZone('A',this)">Zone A</div>
        <div class="zone-pill" style="color:#0891b2" onclick="filterZone('B',this)">Zone B</div>
        <div class="zone-pill" style="color:#d97706" onclick="filterZone('C',this)">Zone C</div>
        <div class="zone-pill" style="color:#7c3aed" onclick="filterZone('D',this)">Zone D</div>
        <div class="zone-pill" style="color:#64748b" onclick="filterZone('S',this)">Staff</div>
      </div>
      <div class="topbar-actions" style="margin-left:auto;display:flex;gap:6px;align-items:center;flex-wrap:wrap">
        <!-- Map Mode Buttons -->
        <div style="display:flex;gap:4px;flex-wrap:wrap">
          <button class="map-mode-btn active" onclick="changeMapMode('standard',this)" title="Default Map">🗺️ Default</button>
          <button class="map-mode-btn" onclick="changeMapMode('satellite',this)" title="Satellite View">🛰️ Satellite</button>
          <button class="map-mode-btn" onclick="changeMapMode('night',this)" title="Night Mode">🌙 Night</button>
          <button class="map-mode-btn" onclick="changeMapMode('3d',this)" title="3D View">🏢 3D</button>
          <button class="map-mode-btn" onclick="changeMapMode('terrain',this)" title="Terrain View">🌄 Terrain</button>
        </div>
        <!-- Search Spot -->
        <div class="map-search-box">
          <span>🔍</span>
          <input type="text" id="spotSearch" placeholder="Search spot..." oninput="searchSpot(this.value)">
        </div>
        <button class="topbar-btn" onclick="refreshMapData()" title="Refresh">&#x21BB;</button>
      </div>
    </div>

    <div class="map-layout">
      <div class="map-container" style="display:flex;flex-direction:column;position:relative">
        <div id="googleStreetView"></div>
        <div id="googleMap" style="flex:1;position:relative"></div>
        <div class="custom-map-controls">
          <button class="map-ctrl-btn" onclick="zoomMap(1)" title="Zoom In">+</button>
          <button class="map-ctrl-btn" onclick="zoomMap(-1)" title="Zoom Out">-</button>
          <button class="map-ctrl-btn" id="streetViewToggleBtn" onclick="toggleStreetViewSplit()" title="Toggle Virtual Navigation">🚶</button>
        </div>
        <div id="mapLoading">
          <div class="loading-spinner" style="width:36px;height:36px;border-width:3px"></div>
          <div style="font-size:.85rem;color:var(--text-3);font-weight:500">Loading campus map...</div>
        </div>
      </div>

      <div class="map-sidebar">
        <div class="map-sidebar-header">
          <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:.95rem;margin-bottom:12px;color:var(--text)">Live Availability</div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;text-align:center">
            <div style="background:var(--green-light);border:1px solid rgba(5,150,105,.2);border-radius:8px;padding:10px">
              <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--green)" id="statAvail">148</div>
              <div style="font-size:.68rem;color:var(--text-3);font-family:'DM Mono',monospace">Available</div>
            </div>
            <div style="background:var(--amber-light);border:1px solid rgba(217,119,6,.2);border-radius:8px;padding:10px">
              <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--amber)" id="statReserved">37</div>
              <div style="font-size:.68rem;color:var(--text-3);font-family:'DM Mono',monospace">Reserved</div>
            </div>
            <div style="background:var(--red-light);border:1px solid rgba(220,38,38,.2);border-radius:8px;padding:10px">
              <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--red)" id="statOccupied">62</div>
              <div style="font-size:.68rem;color:var(--text-3);font-family:'DM Mono',monospace">Occupied</div>
            </div>
          </div>
          
          <div style="margin-top:16px;">
            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:.95rem;margin-bottom:8px;color:var(--text)">Map Modes</div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="btn btn-ghost" style="flex:1;padding:6px;font-size:0.75rem" onclick="changeMapMode('standard')">🗺️ Default</button>
                <button class="btn btn-ghost" style="flex:1;padding:6px;font-size:0.75rem" onclick="changeMapMode('satellite')">🛰️ Satellite</button>
                <button class="btn btn-ghost" style="flex:1;padding:6px;font-size:0.75rem" onclick="changeMapMode('night')">🌙 Night</button>
                <button class="btn btn-ghost" style="flex:1;padding:6px;font-size:0.75rem" onclick="changeMapMode('3d')">🏢 3D View</button>
            </div>
          </div>
        </div>

        <div class="map-sidebar-body">
          <div id="selectedSpotSection" style="display:none">
            <div class="spot-detail-card">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
                <div>
                  <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--white)" id="detailSpotId">--</div>
                  <div style="font-size:.78rem;color:var(--gray-400)" id="detailSpotInfo">Zone - Type</div>
                </div>
                <span class="badge badge-available">Available</span>
              </div>
              <button class="btn btn-primary w-full" onclick="openModal('reserveModal')">&#128197; Reserve This Spot</button>
              <button class="btn btn-ghost w-full" style="margin-top:8px" onclick="clearSpotSelection()">&#10005; Deselect</button>
            </div>
          </div>

          <div style="margin-bottom:16px">
            <div style="font-size:.72rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;font-family:'DM Mono',monospace">Legend</div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;font-size:.78rem;color:var(--text-2);font-weight:500">
              <div style="display:flex;align-items:center;gap:5px"><div style="width:10px;height:10px;background:var(--green);border-radius:2px"></div>Available</div>
              <div style="display:flex;align-items:center;gap:5px"><div style="width:10px;height:10px;background:var(--amber);border-radius:2px"></div>Reserved</div>
              <div style="display:flex;align-items:center;gap:5px"><div style="width:10px;height:10px;background:var(--red);border-radius:2px"></div>Occupied</div>
            </div>
          </div>

          <div style="font-size:.72rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;font-family:'DM Mono',monospace">Zones Overview</div>
          <div id="zoneCards"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Reserve Modal -->
<div class="modal-overlay" id="reserveModal">
  <div class="modal" style="max-width:440px">
    <div class="modal-header">
      <div><h3>Reserve Parking Spot</h3><p style="font-size:.85rem;color:var(--gray-400);margin-top:4px" id="modalSpotLabel">--</p></div>
      <button class="modal-close" onclick="closeModal('reserveModal')">&#10005;</button>
    </div>
    <div class="form-group">
      <label class="form-label">Date</label>
      <input type="date" class="form-control" id="resDate" />
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Start Time</label>
        <select class="form-control" id="resStart">
          <option>8:00 AM</option><option>9:00 AM</option><option selected>10:00 AM</option><option>11:00 AM</option><option>12:00 PM</option><option>1:00 PM</option><option>2:00 PM</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">End Time</label>
        <select class="form-control" id="resEnd">
          <option>10:00 AM</option><option>11:00 AM</option><option>12:00 PM</option><option selected>1:00 PM</option><option>2:00 PM</option><option>3:00 PM</option><option>4:00 PM</option>
        </select>
      </div>
    </div>
    <div style="background:var(--primary-light);border:1px solid rgba(99,102,241,.18);border-radius:var(--radius-sm);padding:12px;font-size:.82rem;color:var(--text-3)">
      <div style="color:var(--text);font-weight:700;margin-bottom:4px">Booking Summary</div>
      <div id="bookingSummary">Select date and time to see summary</div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('reserveModal')">Cancel</button>
      <button class="btn btn-primary" onclick="confirmMapReservation()">Confirm Reservation</button>
    </div>
  </div>
</div>

<div id="toastContainer" class="toast-container"></div>
<script src="js/app.js"></script>
<script src="js/sidebar.js"></script>
<script>
document.getElementById('sidebarMount').innerHTML = renderSidebar('student', 'parking-map');
document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
window.addEventListener('resize', function() {
  document.querySelector('.page-wrapper').style.marginLeft = window.innerWidth > 1024 ? '260px' : '0';
});

document.getElementById('resDate').value = new Date().toISOString().split('T')[0];

var ZONES_DATA = [
  { id:'A', name:'Zone A', type:'General Student', color:'#10b981', available:42, reserved:8,  occupied:10, total:60, lat:27.52290, lng:41.68950 },
  { id:'B', name:'Zone B', type:'General Student', color:'#60a5fa', available:28, reserved:12, occupied:15, total:55, lat:27.52150, lng:41.69180 },
  { id:'C', name:'Zone C', type:'Staff Only',      color:'#f59e0b', available:18, reserved:7,  occupied:15, total:40, lat:27.52380, lng:41.69100 },
  { id:'D', name:'Zone D', type:'Accessible',      color:'#a78bfa', available:10, reserved:4,  occupied:6,  total:20, lat:27.52200, lng:41.68820 },
  { id:'S', name:'Zone S', type:'Staff Reserved',  color:'#64748b', available:30, reserved:0,  occupied:2,  total:32, lat:27.52450, lng:41.68780 }
];

var SPOT_DATA = {
  'A-01':{zone:'A',status:'available',type:'Standard',lat:27.52295,lng:41.68945},
  'A-02':{zone:'A',status:'occupied', type:'Standard',lat:27.52295,lng:41.68958},
  'A-03':{zone:'A',status:'available',type:'Standard',lat:27.52295,lng:41.68971},
  'A-04':{zone:'A',status:'reserved', type:'Standard',lat:27.52283,lng:41.68945},
  'A-05':{zone:'A',status:'available',type:'Standard',lat:27.52283,lng:41.68958},
  'A-06':{zone:'A',status:'available',type:'Standard',lat:27.52283,lng:41.68971},
  'A-07':{zone:'A',status:'available',type:'Accessible',lat:27.52271,lng:41.68945},
  'A-08':{zone:'A',status:'occupied', type:'Standard',lat:27.52271,lng:41.68958},
  'B-01':{zone:'B',status:'available',type:'Standard',lat:27.52155,lng:41.69173},
  'B-02':{zone:'B',status:'occupied', type:'Standard',lat:27.52155,lng:41.69186},
  'B-03':{zone:'B',status:'available',type:'Standard',lat:27.52155,lng:41.69199},
  'B-04':{zone:'B',status:'reserved', type:'Standard',lat:27.52143,lng:41.69173},
  'B-05':{zone:'B',status:'available',type:'Standard',lat:27.52143,lng:41.69186},
  'B-06':{zone:'B',status:'occupied', type:'Standard',lat:27.52143,lng:41.69199},
  'B-07':{zone:'B',status:'available',type:'Standard',lat:27.52131,lng:41.69173},
  'B-08':{zone:'B',status:'reserved', type:'Standard',lat:27.52131,lng:41.69186},
  'C-01':{zone:'C',status:'occupied', type:'Staff',lat:27.52383,lng:41.69093},
  'C-02':{zone:'C',status:'available',type:'Staff',lat:27.52383,lng:41.69106},
  'C-03':{zone:'C',status:'reserved', type:'Staff',lat:27.52371,lng:41.69093},
  'C-04':{zone:'C',status:'occupied', type:'Staff',lat:27.52371,lng:41.69106},
  'C-05':{zone:'C',status:'available',type:'Staff',lat:27.52359,lng:41.69093},
  'C-06':{zone:'C',status:'available',type:'Staff',lat:27.52359,lng:41.69106},
  'D-01':{zone:'D',status:'available',type:'Accessible',lat:27.52203,lng:41.68813},
  'D-02':{zone:'D',status:'occupied', type:'Accessible',lat:27.52203,lng:41.68826},
  'D-03':{zone:'D',status:'available',type:'Accessible',lat:27.52193,lng:41.68813},
  'D-04':{zone:'D',status:'reserved', type:'Accessible',lat:27.52193,lng:41.68826},
  'S-01':{zone:'S',status:'available',type:'Staff',lat:27.52453,lng:41.68773},
  'S-02':{zone:'S',status:'available',type:'Staff',lat:27.52453,lng:41.68786},
  'S-03':{zone:'S',status:'occupied', type:'Staff',lat:27.52443,lng:41.68773},
  'S-04':{zone:'S',status:'available',type:'Staff',lat:27.52443,lng:41.68786}
};

var map, markers = {}, infoWindow, selectedSpotId = null, panorama, streetViewActive = false;

function renderZoneCards() {
  document.getElementById('zoneCards').innerHTML = ZONES_DATA.map(function(z) {
    var occ = Math.round((z.occupied / z.total) * 100);
    return '<div class="zone-stat-card" onclick="filterZone(\''+z.id+'\',null)" style="border-left:3px solid '+z.color+'33">' +
      '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">' +
        '<div style="font-weight:700;font-size:.88rem;color:'+z.color+'">'+z.name+'</div>' +
        '<div style="font-size:.7rem;color:var(--gray-600);font-family:\'DM Mono\',monospace">'+z.type+'</div>' +
      '</div>' +
      '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:4px;margin-bottom:8px;text-align:center;font-size:.7rem;font-family:\'DM Mono\',monospace">' +
        '<div><div style="color:#34d399;font-weight:700">'+z.available+'</div><div style="color:var(--gray-600)">free</div></div>' +
        '<div><div style="color:#fbbf24;font-weight:700">'+z.reserved+'</div><div style="color:var(--gray-600)">rsvd</div></div>' +
        '<div><div style="color:#f87171;font-weight:700">'+z.occupied+'</div><div style="color:var(--gray-600)">occ</div></div>' +
      '</div>' +
      '<div class="progress-bar"><div class="progress-fill" style="width:'+occ+'%;background:'+z.color+'"></div></div>' +
    '</div>';
  }).join('');
}

function initMap() {
  document.getElementById('mapLoading').style.display = 'none';
  var UOH = { lat: 27.5225, lng: 41.6895 };

  map = new google.maps.Map(document.getElementById('googleMap'), {
    center: UOH,
    zoom: 17,
    mapTypeId: 'roadmap',
    styles: [
      {elementType:'geometry',stylers:[{color:'#f1f5f9'}]},
      {elementType:'labels.text.fill',stylers:[{color:'#334155'}]},
      {elementType:'labels.text.stroke',stylers:[{color:'#ffffff'}]},
      {featureType:'road',elementType:'geometry',stylers:[{color:'#ffffff'}]},
      {featureType:'road',elementType:'geometry.stroke',stylers:[{color:'#e2e8f0'}]},
      {featureType:'road.highway',elementType:'geometry',stylers:[{color:'#c7d2fe'}]},
      {featureType:'road.highway',elementType:'geometry.stroke',stylers:[{color:'#a5b4fc'}]},
      {featureType:'water',elementType:'geometry',stylers:[{color:'#bae6fd'}]},
      {featureType:'water',elementType:'labels.text.fill',stylers:[{color:'#0369a1'}]},
      {featureType:'poi',elementType:'geometry',stylers:[{color:'#dcfce7'}]},
      {featureType:'poi.park',elementType:'geometry',stylers:[{color:'#bbf7d0'}]},
      {featureType:'landscape',elementType:'geometry',stylers:[{color:'#f8fafc'}]},
      {featureType:'transit',elementType:'geometry',stylers:[{color:'#e0f2fe'}]}
    ],
    mapTypeControl: true,
    streetViewControl: true,
    fullscreenControl: true,
    zoomControl: true,
    scaleControl: true,
    rotateControl: true
  });

  panorama = new google.maps.StreetViewPanorama(
    document.getElementById('googleStreetView'), {
      position: UOH,
      pov: { heading: 165, pitch: 0 },
      visible: false
    }
  );

  map.setStreetView(panorama);

  panorama.addListener('visible_changed', function() {
    var isVisible = panorama.getVisible();
    var svDiv = document.getElementById('googleStreetView');
    var btn = document.getElementById('streetViewToggleBtn');
    if (isVisible) {
      svDiv.style.height = '45%';
      btn.style.background = 'linear-gradient(135deg, #4f46e5, #7c3aed)';
      btn.style.color = '#ffffff';
      btn.style.borderColor = 'transparent';
      streetViewActive = true;
    } else {
      svDiv.style.height = '0';
      btn.style.background = '#ffffff';
      btn.style.color = '#4f46e5';
      btn.style.borderColor = 'rgba(79, 70, 229, 0.18)';
      streetViewActive = false;
    }
    // Resize map when streetview is toggled
    setTimeout(function() {
      google.maps.event.trigger(map, 'resize');
    }, 300);
  });

  infoWindow = new google.maps.InfoWindow();

  var ZONE_COLORS = {A:'#10b981',B:'#60a5fa',C:'#f59e0b',D:'#a78bfa',S:'#64748b'};
  var ZONE_POLYGONS = {
    A:[{lat:27.52310,lng:41.68928},{lat:27.52310,lng:41.68992},{lat:27.52258,lng:41.68992},{lat:27.52258,lng:41.68928}],
    B:[{lat:27.52170,lng:41.69158},{lat:27.52170,lng:41.69222},{lat:27.52118,lng:41.69222},{lat:27.52118,lng:41.69158}],
    C:[{lat:27.52400,lng:41.69078},{lat:27.52400,lng:41.69132},{lat:27.52348,lng:41.69132},{lat:27.52348,lng:41.69078}],
    D:[{lat:27.52218,lng:41.68798},{lat:27.52218,lng:41.68842},{lat:27.52178,lng:41.68842},{lat:27.52178,lng:41.68798}],
    S:[{lat:27.52465,lng:41.68758},{lat:27.52465,lng:41.68802},{lat:27.52428,lng:41.68802},{lat:27.52428,lng:41.68758}]
  };

  Object.keys(ZONE_POLYGONS).forEach(function(id) {
    var poly = new google.maps.Polygon({
      paths: ZONE_POLYGONS[id],
      strokeColor: ZONE_COLORS[id],
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: ZONE_COLORS[id],
      fillOpacity: 0.12,
      map: map
    });
    poly.addListener('click', function() { filterZone(id, null); });
  });

  Object.keys(SPOT_DATA).forEach(function(id) {
    var spot = SPOT_DATA[id];
    var statusColors = {available:'#10b981',reserved:'#f59e0b',occupied:'#ef4444'};
    var color = statusColors[spot.status] || '#64748b';
    var marker = new google.maps.Marker({
      position: {lat: spot.lat, lng: spot.lng},
      map: map,
      icon: makeMarkerIcon(id, color),
      title: id + ' - ' + spot.status
    });
    marker.spotId = id;
    marker.addListener('click', function() { selectSpotOnMap(id, spot, marker); });
    markers[id] = marker;
  });

  renderZoneCards();
}

function makeMarkerIcon(id, color) {
  var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="34" height="42" viewBox="0 0 34 42">' +
    '<rect x="2" y="2" width="30" height="30" rx="6" fill="' + color + '" fill-opacity="0.95" stroke="rgba(255,255,255,0.9)" stroke-width="2"/>' +
    '<text x="17" y="23" font-family="sans-serif" font-size="8" font-weight="bold" fill="#ffffff" text-anchor="middle">' + id + '</text>' +
    '<polygon points="17,38 10,30 24,30" fill="' + color + '" fill-opacity="0.95"/>' +
    '</svg>';
  return {
    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
    scaledSize: new google.maps.Size(34, 42),
    anchor: new google.maps.Point(17, 42)
  };
}

function selectSpotOnMap(id, spot, marker) {
  selectedSpotId = id;
  if (spot.status === 'occupied') { showToast('warning','Spot Occupied','This spot is currently in use'); return; }
  if (spot.status === 'reserved') { showToast('warning','Spot Reserved','This spot has been reserved'); return; }

  var content = '<div style="padding:14px;background:#ffffff;border-radius:12px;min-width:190px;border:1px solid #e2e8f0;box-shadow:0 4px 20px rgba(0,0,0,.10)">' +
    '<div style="font-family:Syne,sans-serif;font-size:.95rem;font-weight:800;color:#0f172a;margin-bottom:3px">Spot ' + id + '</div>' +
    '<div style="font-size:.76rem;color:#64748b;margin-bottom:12px">Zone ' + spot.zone + ' &bull; ' + spot.type + '</div>' +
    '<button onclick="openModal(\'reserveModal\')" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#ffffff;font-size:.82rem;font-weight:700;border:none;border-radius:8px;padding:8px 14px;cursor:pointer;font-family:Syne,sans-serif;width:100%;box-shadow:0 4px 12px rgba(99,102,241,.35)">&#128197; Reserve This Spot</button>' +
    '</div>';
  infoWindow.setContent(content);
  infoWindow.open(map, marker);

  document.getElementById('selectedSpotSection').style.display = 'block';
  document.getElementById('detailSpotId').textContent = 'Spot ' + id;
  document.getElementById('detailSpotInfo').textContent = 'Zone ' + spot.zone + ' \u00b7 ' + spot.type;
  document.getElementById('modalSpotLabel').textContent = 'Spot ' + id + ' \u00b7 Zone ' + spot.zone + ' \u00b7 ' + spot.type;
  updateBookingSummary();
}

function updateBookingSummary() {
  if (!selectedSpotId) return;
  var date = document.getElementById('resDate').value;
  var start = document.getElementById('resStart').value;
  var end = document.getElementById('resEnd').value;
  var dateStr = date ? new Date(date + 'T12:00:00').toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : '--';
  document.getElementById('bookingSummary').innerHTML =
    '<strong style="color:var(--text)">Spot ' + selectedSpotId + '</strong><br>' +
    '&#128197; ' + dateStr + '<br>' +
    '&#9200; ' + start + ' &ndash; ' + end;
}

document.addEventListener('change', function(e) {
  if (e.target.id === 'resDate' || e.target.id === 'resStart' || e.target.id === 'resEnd') updateBookingSummary();
});

function confirmMapReservation() {
  if (!selectedSpotId) { showToast('warning','No Spot','Click a green spot first'); return; }
  SPOT_DATA[selectedSpotId].status = 'reserved';
  var m = markers[selectedSpotId];
  if (m) m.setIcon(makeMarkerIcon(selectedSpotId, '#f59e0b'));
  var avail = parseInt(document.getElementById('statAvail').textContent, 10) - 1;
  var rsvd  = parseInt(document.getElementById('statReserved').textContent, 10) + 1;
  document.getElementById('statAvail').textContent    = avail;
  document.getElementById('statReserved').textContent = rsvd;
  closeModal('reserveModal');
  if (infoWindow) infoWindow.close();
  clearSpotSelection();
  showToast('success', 'Reserved! Spot ' + selectedSpotId + ' is now reserved', '');
  selectedSpotId = null;
}

function clearSpotSelection() {
  document.getElementById('selectedSpotSection').style.display = 'none';
  if (infoWindow) infoWindow.close();
}

function filterZone(zoneId, el) {
  document.querySelectorAll('.zone-pill').forEach(function(p){ p.classList.remove('active'); });
  if (el) el.classList.add('active');
  Object.keys(markers).forEach(function(id) {
    var spot = SPOT_DATA[id];
    markers[id].setVisible(zoneId === 'all' || spot.zone === zoneId);
  });
  if (zoneId !== 'all') {
    var zone = ZONES_DATA.find(function(z){ return z.id === zoneId; });
    if (zone && map) { map.panTo({lat:zone.lat,lng:zone.lng}); map.setZoom(18); }
  } else if (map) {
    map.panTo({lat:27.5225,lng:41.6895}); map.setZoom(17);
  }
}

function refreshMapData() {
  showToast('info','Map Refreshed','Parking availability updated', '');
  renderZoneCards();
}

function zoomMap(delta) {
  if (map) {
    var currentZoom = map.getZoom();
    map.setZoom(currentZoom + delta);
  }
}

function toggleStreetViewSplit() {
  if (!map || !panorama) return;
  var isVisible = panorama.getVisible();
  if (!isVisible) {
    panorama.setPosition(map.getCenter());
    panorama.setVisible(true);
  } else {
    panorama.setVisible(false);
  }
}

function changeMapMode(mode, el) {
  var lightStyles = [
    {elementType:'geometry',stylers:[{color:'#f1f5f9'}]},
    {elementType:'labels.text.fill',stylers:[{color:'#334155'}]},
    {elementType:'labels.text.stroke',stylers:[{color:'#ffffff'}]},
    {featureType:'road',elementType:'geometry',stylers:[{color:'#ffffff'}]},
    {featureType:'road',elementType:'geometry.stroke',stylers:[{color:'#e2e8f0'}]},
    {featureType:'road.highway',elementType:'geometry',stylers:[{color:'#c7d2fe'}]},
    {featureType:'road.highway',elementType:'geometry.stroke',stylers:[{color:'#a5b4fc'}]},
    {featureType:'water',elementType:'geometry',stylers:[{color:'#bae6fd'}]},
    {featureType:'water',elementType:'labels.text.fill',stylers:[{color:'#0369a1'}]},
    {featureType:'poi',elementType:'geometry',stylers:[{color:'#dcfce7'}]},
    {featureType:'poi.park',elementType:'geometry',stylers:[{color:'#bbf7d0'}]},
    {featureType:'landscape',elementType:'geometry',stylers:[{color:'#f8fafc'}]},
    {featureType:'transit',elementType:'geometry',stylers:[{color:'#e0f2fe'}]}
  ];

  var nightStyles = [
    {elementType:'geometry',stylers:[{color:'#242f3e'}]},
    {elementType:'labels.text.stroke',stylers:[{color:'#242f3e'}]},
    {elementType:'labels.text.fill',stylers:[{color:'#746855'}]},
    {featureType:'administrative.locality',elementType:'labels.text.fill',stylers:[{color:'#d59563'}]},
    {featureType:'poi',elementType:'labels.text.fill',stylers:[{color:'#d59563'}]},
    {featureType:'poi.park',elementType:'geometry',stylers:[{color:'#263c3f'}]},
    {featureType:'poi.park',elementType:'labels.text.fill',stylers:[{color:'#6b9a76'}]},
    {featureType:'road',elementType:'geometry',stylers:[{color:'#38414e'}]},
    {featureType:'road',elementType:'geometry.stroke',stylers:[{color:'#212a37'}]},
    {featureType:'road',elementType:'labels.text.fill',stylers:[{color:'#9ca5b3'}]},
    {featureType:'road.highway',elementType:'geometry',stylers:[{color:'#746855'}]},
    {featureType:'road.highway',elementType:'geometry.stroke',stylers:[{color:'#1f2835'}]},
    {featureType:'road.highway',elementType:'labels.text.fill',stylers:[{color:'#f3d19c'}]},
    {featureType:'water',elementType:'geometry',stylers:[{color:'#17263c'}]},
    {featureType:'water',elementType:'labels.text.fill',stylers:[{color:'#515c6d'}]},
    {featureType:'water',elementType:'labels.text.stroke',stylers:[{color:'#17263c'}]}
  ];

  if (!map) return;

  // Update active button state
  document.querySelectorAll('.map-mode-btn').forEach(function(btn) { btn.classList.remove('active'); });
  if (el) el.classList.add('active');

  if (mode === 'night') {
    map.setOptions({ styles: nightStyles, mapTypeId: 'roadmap', tilt: 0 });
    showToast('info', 'Night Mode', 'Switched to dark night view');
  } else if (mode === 'satellite') {
    map.setOptions({ styles: [], mapTypeId: 'satellite', tilt: 0 });
    showToast('info', 'Satellite View', 'Switched to satellite imagery');
  } else if (mode === '3d') {
    map.setOptions({ styles: [], mapTypeId: 'satellite', tilt: 45, heading: 90 });
    showToast('info', '3D View', 'Switched to 3D aerial perspective');
  } else if (mode === 'terrain') {
    map.setOptions({ styles: [], mapTypeId: 'terrain', tilt: 0 });
    showToast('info', 'Terrain View', 'Switched to terrain map');
  } else {
    map.setOptions({ styles: lightStyles, mapTypeId: 'roadmap', tilt: 0 });
    showToast('info', 'Default Map', 'Switched to standard map view');
  }
}

function searchSpot(query) {
  query = query.trim().toUpperCase();
  if (!query) {
    Object.keys(markers).forEach(function(id) {
      markers[id].setVisible(true);
    });
    return;
  }
  Object.keys(markers).forEach(function(id) {
    var visible = id.toUpperCase().includes(query);
    markers[id].setVisible(visible);
    if (visible && map) {
      var spot = SPOT_DATA[id];
      map.panTo({ lat: spot.lat, lng: spot.lng });
      map.setZoom(19);
    }
  });
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2fDACj9xAUG6cqWMJN-In643kRvCoKms&callback=initMap&language=en"></script>
</body>
</html>
