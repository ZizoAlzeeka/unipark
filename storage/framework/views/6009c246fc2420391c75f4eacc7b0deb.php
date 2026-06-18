<?php $__env->startSection('title', 'Parking Map'); ?>
<?php $__env->startSection('page-title', 'Parking Map'); ?>
<?php $__env->startSection('page-subtitle', 'View real-time availability and reserve your spot'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .map-type-btn { padding:8px 18px; border:2px solid var(--border-color); border-radius:var(--radius-full); font-size:13px; font-weight:600; cursor:pointer; transition:var(--transition); background:#fff; color:var(--text-secondary); display:inline-flex; align-items:center; gap:6px; }
    .map-type-btn.active { border-color:var(--primary); background:rgba(108,99,255,0.08); color:var(--primary); }
    .spot-tooltip { background:#fff; border-radius:var(--radius-md); box-shadow:var(--shadow-lg); padding:16px; min-width:220px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">

    <!-- Zone Summary Cards -->
    <div class="grid grid-4 mb-24">
        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $available = $zone->spots->where('status','available')->count();
            $total = $zone->spots->count();
            $rate = $total > 0 ? round(($available/$total)*100) : 0;
        ?>
        <div class="zone-card">
            <div class="zone-card-header" style="<?php echo e(['ZA'=>'background:var(--grad-primary)','ZB'=>'background:var(--grad-secondary)','ZC'=>'background:var(--grad-success)','ZD'=>'background:var(--grad-accent)'][$zone->code] ?? 'background:var(--grad-primary)'); ?>">
                <span class="zone-code"><?php echo e($zone->code); ?></span>
                <div style="color:#fff;">
                    <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.5px;">Zone</div>
                    <div style="font-size:18px;font-weight:800;"><?php echo e($zone->name); ?></div>
                    <div style="font-size:12px;opacity:0.8;margin-top:2px;"><i class="fas fa-map-marker-alt"></i> <?php echo e($zone->location ?? 'Campus'); ?></div>
                </div>
            </div>
            <div class="zone-card-body">
                <div class="zone-availability">
                    <div class="zone-stat">
                        <div class="zone-stat-value" style="color:var(--success);"><?php echo e($available); ?></div>
                        <div class="zone-stat-label">Free</div>
                    </div>
                    <div class="zone-stat">
                        <div class="zone-stat-value" style="color:var(--warning);"><?php echo e($zone->spots->where('status','reserved')->count()); ?></div>
                        <div class="zone-stat-label">Reserved</div>
                    </div>
                    <div class="zone-stat">
                        <div class="zone-stat-value" style="color:var(--danger);"><?php echo e($zone->spots->where('status','occupied')->count()); ?></div>
                        <div class="zone-stat-label">Occupied</div>
                    </div>
                </div>
                <div class="progress mt-12">
                    <div class="progress-bar <?php echo e($rate > 50 ? 'success' : 'warning'); ?>" style="width:<?php echo e($rate); ?>%;"></div>
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:6px;text-align:center;"><?php echo e($rate); ?>% Available</div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Map & Grid Tabs -->
    <div class="card mb-24">
        <div class="map-controls">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <button class="map-type-btn active" id="btn-grid" onclick="showView('grid')">
                    <i class="fas fa-th"></i> Grid View
                </button>
                <button class="map-type-btn" id="btn-map" onclick="showView('map')">
                    <i class="fas fa-map-marked-alt"></i> Google Maps
                </button>
            </div>

            <!-- Zone Filter -->
            <div style="display:flex;align-items:center;gap:10px;margin-left:auto;">
                <label style="font-size:13px;font-weight:600;color:var(--text-secondary);">Filter Zone:</label>
                <select class="form-select" style="width:180px;padding:8px 36px 8px 14px;" id="zoneFilter" onchange="filterZone(this.value)">
                    <option value="">All Zones</option>
                    <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($zone->id); ?>"><?php echo e($zone->code); ?> — <?php echo e($zone->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select class="form-select" style="width:160px;padding:8px 36px 8px 14px;" id="statusFilter" onchange="filterStatus(this.value)">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                    <option value="reserved">Reserved</option>
                    <option value="occupied">Occupied</option>
                </select>
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView">
            <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="zone-section" data-zone="<?php echo e($zone->id); ?>">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px 8px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:38px;height:38px;background:<?php echo e(['ZA'=>'var(--grad-primary)','ZB'=>'var(--grad-secondary)','ZC'=>'var(--grad-success)','ZD'=>'var(--grad-accent)'][$zone->code] ?? 'var(--grad-primary)'); ?>;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:900;"><?php echo e($zone->code); ?></div>
                        <div>
                            <div style="font-size:15px;font-weight:700;"><?php echo e($zone->name); ?></div>
                            <div style="font-size:12px;color:var(--text-muted);"><?php echo e($zone->spots->count()); ?> spots total</div>
                        </div>
                    </div>
                </div>

                <div class="parking-grid">
                    <?php $__currentLoopData = $zone->spots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="parking-spot <?php echo e($spot->status); ?>" data-spot-id="<?php echo e($spot->id); ?>" data-status="<?php echo e($spot->status); ?>" data-zone="<?php echo e($zone->id); ?>"
                         onclick="selectSpot(<?php echo e($spot->id); ?>, '<?php echo e($spot->status); ?>', '<?php echo e($zone->code); ?>-<?php echo e($spot->spot_number); ?>')"
                         title="<?php echo e($zone->code); ?>-<?php echo e($spot->spot_number); ?> (<?php echo e(ucfirst($spot->type)); ?>) — <?php echo e(ucfirst($spot->status)); ?>">
                        <i class="spot-type-icon fas <?php echo e(['standard'=>'fa-car','staff'=>'fa-briefcase','disabled'=>'fa-wheelchair','vip'=>'fa-star'][$spot->type] ?? 'fa-car'); ?>"></i>
                        <span class="spot-number"><?php echo e($zone->code); ?>-<?php echo e($spot->spot_number); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Google Maps View -->
        <div id="mapView" style="display:none;">
            <!-- Map Type Controls -->
            <div style="padding:12px 20px;border-top:1px solid var(--border-color);display:flex;gap:10px;flex-wrap:wrap;">
                <button class="map-type-btn active" id="mapTypeRoadmap" onclick="setMapType('roadmap')"><i class="fas fa-road"></i> Roadmap</button>
                <button class="map-type-btn" id="mapTypeSatellite" onclick="setMapType('satellite')"><i class="fas fa-satellite"></i> Satellite</button>
                <button class="map-type-btn" id="mapTypeHybrid" onclick="setMapType('hybrid')"><i class="fas fa-layer-group"></i> Hybrid</button>
                <button class="map-type-btn" id="mapTypeTerrain" onclick="setMapType('terrain')"><i class="fas fa-mountain"></i> Terrain</button>
            </div>
            <div id="google-map"></div>
        </div>

        <!-- Map Legend -->
        <div class="map-legend">
            <span style="font-size:13px;font-weight:700;color:var(--text-secondary);">Status:</span>
            <div class="legend-item"><div class="legend-dot available"></div> Available</div>
            <div class="legend-item"><div class="legend-dot reserved"></div> Reserved</div>
            <div class="legend-item"><div class="legend-dot occupied"></div> Occupied</div>
            <div style="margin-left:auto;display:flex;gap:20px;">
                <span style="font-size:13px;font-weight:700;color:var(--text-secondary);">Type:</span>
                <div class="legend-item"><i class="fas fa-car" style="color:var(--primary);font-size:13px;"></i> Standard</div>
                <div class="legend-item"><i class="fas fa-briefcase" style="color:var(--secondary);font-size:13px;"></i> Staff</div>
                <div class="legend-item"><i class="fas fa-wheelchair" style="color:var(--success);font-size:13px;"></i> Accessible</div>
                <div class="legend-item"><i class="fas fa-star" style="color:var(--warning);font-size:13px;"></i> VIP</div>
            </div>
        </div>
    </div>
</div>

<!-- Spot Detail Modal -->
<div class="modal-overlay" id="spotModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="modalSpotName">Spot A-01</h3>
                <div id="modalSpotZone" style="font-size:13px;color:var(--text-muted);margin-top:2px;"></div>
            </div>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="spotDetailContent">
                <div class="flex-center" style="padding:30px;"><div class="spinner"></div></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Close</button>
            <a id="reserveBtn" href="#" class="btn btn-primary" style="display:none;">
                <i class="fas fa-calendar-plus"></i> Reserve This Spot
            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    let googleMap = null;
    let markers = [];
    let selectedSpotId = null;

    // ── Grid/Map View Toggle ──
    function showView(type) {
        document.getElementById('gridView').style.display = type === 'grid' ? 'block' : 'none';
        document.getElementById('mapView').style.display = type === 'map' ? 'block' : 'none';
        document.getElementById('btn-grid').classList.toggle('active', type === 'grid');
        document.getElementById('btn-map').classList.toggle('active', type === 'map');

        if (type === 'map' && !googleMap) {
            initMap();
        }
    }

    // ── Spot Selection ──
    function selectSpot(spotId, status, name) {
        selectedSpotId = spotId;
        document.getElementById('modalSpotName').textContent = 'Spot ' + name;
        document.getElementById('spotModal').classList.add('show');
        loadSpotDetails(spotId, status);
    }

    function closeModal() {
        document.getElementById('spotModal').classList.remove('show');
        // Remove selection highlight
        document.querySelectorAll('.parking-spot.selected').forEach(el => {
            const status = el.dataset.status;
            el.classList.remove('selected');
        });
    }

    function loadSpotDetails(spotId, status) {
        const content = document.getElementById('spotDetailContent');
        const reserveBtn = document.getElementById('reserveBtn');
        content.innerHTML = '<div class="flex-center" style="padding:30px;"><div class="spinner"></div></div>';

        fetch(`/api/spots/${spotId}`)
            .then(r => r.json())
            .then(data => {
                const statusColors = { available: 'success', reserved: 'warning', occupied: 'danger', maintenance: 'secondary' };
                const statusColor = statusColors[data.status] || 'secondary';

                const typeIcons = { standard: 'fa-car', staff: 'fa-briefcase', disabled: 'fa-wheelchair', vip: 'fa-star' };
                const typeIcon = typeIcons[data.type] || 'fa-car';

                document.getElementById('modalSpotZone').textContent = data.zone.name;

                content.innerHTML = `
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                        <div style="padding:16px;border-radius:var(--radius-md);background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);">
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Status</div>
                            <span class="badge badge-${statusColor}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>
                        </div>
                        <div style="padding:16px;border-radius:var(--radius-md);background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);">
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Type</div>
                            <div style="display:flex;align-items:center;gap:6px;font-weight:700;font-size:14px;color:var(--text-primary);">
                                <i class="fas ${typeIcon}" style="color:var(--primary);"></i>
                                ${data.type.charAt(0).toUpperCase() + data.type.slice(1)}
                            </div>
                        </div>
                    </div>

                    <div style="padding:16px;border-radius:var(--radius-md);background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);margin-bottom:16px;">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:8px;">Zone Information</div>
                        <div style="font-size:14px;font-weight:600;color:var(--text-primary);">${data.zone.name} (${data.zone.code})</div>
                    </div>

                    ${data.active_reservation ? `
                    <div style="padding:16px;border-radius:var(--radius-md);background:rgba(255,183,3,0.06);border:1px solid rgba(255,183,3,0.2);">
                        <div style="font-size:12px;font-weight:700;color:var(--warning);margin-bottom:8px;"><i class="fas fa-clock"></i> Currently Reserved</div>
                        <div style="font-size:13px;color:var(--text-secondary);">Until: <strong>${data.active_reservation.end_time}</strong></div>
                    </div>` : ''}
                `;

                if (data.status === 'available') {
                    reserveBtn.style.display = 'inline-flex';
                    reserveBtn.href = `/reservations/create?spot_id=${spotId}`;
                } else {
                    reserveBtn.style.display = 'none';
                }
            })
            .catch(() => {
                content.innerHTML = '<div style="text-align:center;color:var(--text-muted);padding:20px;">Failed to load spot details.</div>';
            });
    }

    // ── Zone Filter ──
    function filterZone(zoneId) {
        document.querySelectorAll('.zone-section').forEach(el => {
            el.style.display = (!zoneId || el.dataset.zone === zoneId) ? 'block' : 'none';
        });
    }

    // ── Status Filter ──
    function filterStatus(status) {
        document.querySelectorAll('.parking-spot').forEach(el => {
            el.style.display = (!status || el.dataset.status === status) ? 'flex' : 'none';
        });
    }

    // ── Google Maps ──
    function initMap() {
        const center = { lat: 27.5114, lng: 41.7208 };
        googleMap = new google.maps.Map(document.getElementById('google-map'), {
            center: center,
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
                { featureType: 'transit', stylers: [{ visibility: 'off' }] }
            ],
            mapTypeControl: false,
            streetViewControl: true,
            fullscreenControl: true,
        });

        // Load zones and add markers
        fetch('/api/zones')
            .then(r => r.json())
            .then(data => {
                data.zones.forEach(zone => {
                    if (!zone.latitude || !zone.longitude) return;

                    const availRate = zone.total_spots > 0 ? (zone.available / zone.total_spots) : 0;
                    const color = availRate > 0.5 ? '#06D6A0' : (availRate > 0.2 ? '#FFB703' : '#EF476F');

                    const marker = new google.maps.Marker({
                        position: { lat: parseFloat(zone.latitude), lng: parseFloat(zone.longitude) },
                        map: googleMap,
                        title: zone.name,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 20,
                            fillColor: color,
                            fillOpacity: 0.9,
                            strokeColor: '#fff',
                            strokeWeight: 3,
                        },
                        label: {
                            text: zone.code,
                            color: '#fff',
                            fontSize: '11px',
                            fontWeight: '700',
                        }
                    });

                    const infoWindow = new google.maps.InfoWindow({
                        content: `
                            <div class="spot-tooltip" style="font-family:'Poppins',sans-serif;padding:16px;min-width:200px;">
                                <div style="font-weight:800;font-size:15px;margin-bottom:8px;color:#1a1a2e;">${zone.name}</div>
                                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:12px;">
                                    <div style="text-align:center;padding:8px;background:rgba(6,214,160,0.08);border-radius:8px;">
                                        <div style="font-size:18px;font-weight:800;color:#06D6A0;">${zone.available}</div>
                                        <div style="font-size:10px;color:#718096;font-weight:600;">Free</div>
                                    </div>
                                    <div style="text-align:center;padding:8px;background:rgba(255,183,3,0.08);border-radius:8px;">
                                        <div style="font-size:18px;font-weight:800;color:#FFB703;">${zone.reserved}</div>
                                        <div style="font-size:10px;color:#718096;font-weight:600;">Reserved</div>
                                    </div>
                                    <div style="text-align:center;padding:8px;background:rgba(239,71,111,0.08);border-radius:8px;">
                                        <div style="font-size:18px;font-weight:800;color:#EF476F;">${zone.occupied}</div>
                                        <div style="font-size:10px;color:#718096;font-weight:600;">Occupied</div>
                                    </div>
                                </div>
                                <div style="background:#eee;border-radius:20px;height:6px;overflow:hidden;">
                                    <div style="height:100%;border-radius:20px;background:linear-gradient(135deg,#6C63FF,#4facfe);width:${zone.occupancy_rate}%;"></div>
                                </div>
                                <div style="margin-top:6px;font-size:11px;color:#a0aec0;text-align:center;">${zone.occupancy_rate}% occupied</div>
                                <a href="/reservations/create" style="display:block;text-align:center;margin-top:12px;padding:8px;background:linear-gradient(135deg,#6C63FF,#4facfe);color:#fff;border-radius:20px;font-size:13px;font-weight:700;text-decoration:none;">Reserve a Spot</a>
                            </div>
                        `,
                    });

                    marker.addListener('click', () => {
                        markers.forEach(m => m.infoWindow && m.infoWindow.close());
                        infoWindow.open(googleMap, marker);
                    });

                    marker.infoWindow = infoWindow;
                    markers.push(marker);
                });
            });
    }

    function setMapType(type) {
        if (googleMap) {
            googleMap.setMapTypeId(google.maps.MapTypeId[type.toUpperCase()]);
        }
        document.querySelectorAll('[id^="mapType"]').forEach(btn => btn.classList.remove('active'));
        document.getElementById('mapType' + type.charAt(0).toUpperCase() + type.slice(1)).classList.add('active');
    }

    // Close modal on overlay click
    document.getElementById('spotModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Auto-refresh spot statuses every 30s
    setInterval(function() {
        fetch('/api/spots')
            .then(r => r.json())
            .then(data => {
                data.spots.forEach(spot => {
                    const el = document.querySelector(`[data-spot-id="${spot.id}"]`);
                    if (el) {
                        el.className = `parking-spot ${spot.status}`;
                        el.dataset.status = spot.status;
                    }
                });
            });
    }, 30000);
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google_maps.key')); ?>&callback=Function.prototype">
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/user/parking-map.blade.php ENDPATH**/ ?>