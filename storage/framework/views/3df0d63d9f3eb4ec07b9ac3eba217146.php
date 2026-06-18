<?php $__env->startSection('title', 'New Reservation'); ?>
<?php $__env->startSection('page-title', 'Book a Parking Spot'); ?>
<?php $__env->startSection('page-subtitle', 'Select a spot and define your reservation time'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-up">
    <div class="grid grid-2" style="gap:28px; align-items:start;">
        <!-- Reservation Form -->
        <div class="card">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border-color);">
                <div style="width:46px;height:46px;background:var(--grad-primary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;box-shadow:0 4px 15px rgba(108,99,255,0.4);">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div>
                    <div style="font-size:18px;font-weight:700;">New Reservation</div>
                    <div style="font-size:13px;color:var(--text-muted);">Fill in the details below to book your spot</div>
                </div>
            </div>

            <form action="<?php echo e(route('reservations.store')); ?>" method="POST" id="reservationForm">
                <?php echo csrf_field(); ?>

                <!-- Zone Selection -->
                <div class="form-group">
                    <label class="form-label">Select Zone <span class="required">*</span></label>
                    <select class="form-select" id="zoneSelect" onchange="loadZoneSpots(this.value)" required>
                        <option value="">— Choose a parking zone —</option>
                        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($zone->id); ?>"><?php echo e($zone->name); ?> (<?php echo e($zone->spots->count()); ?> spots available)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Spot Selection -->
                <div class="form-group">
                    <label class="form-label">Select Spot <span class="required">*</span></label>
                    <input type="hidden" name="spot_id" id="spotIdInput" value="<?php echo e($selectedSpot?->id); ?>">
                    <div id="spotSelector" style="border:2px solid var(--border-color);border-radius:var(--radius-md);padding:16px;min-height:80px;text-align:center;color:var(--text-muted);font-size:14px;">
                        <?php if($selectedSpot): ?>
                        <div id="selectedSpotDisplay" style="display:flex;align-items:center;justify-content:space-between;background:rgba(108,99,255,0.06);border:2px solid rgba(108,99,255,0.2);border-radius:var(--radius-md);padding:14px 18px;">
                            <div>
                                <div style="font-size:24px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo e($selectedSpot->zone->code); ?>-<?php echo e($selectedSpot->spot_number); ?></div>
                                <div style="font-size:13px;color:var(--text-muted);"><?php echo e($selectedSpot->zone->name); ?> — <?php echo e(ucfirst($selectedSpot->type)); ?></div>
                            </div>
                            <span class="badge badge-success">Available</span>
                        </div>
                        <?php else: ?>
                        <div id="spotPlaceholder">
                            <i class="fas fa-parking" style="font-size:28px;margin-bottom:8px;display:block;opacity:0.3;"></i>
                            Select a zone above to see available spots
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Spot Grid (shown when zone selected) -->
                <div id="zoneSpotGrid" style="display:none; margin-bottom:20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                        <label class="form-label" style="margin-bottom:0;">Available Spots</label>
                        <div style="display:flex;gap:8px;font-size:12px;">
                            <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;border-radius:50%;background:rgba(6,214,160,0.5);display:inline-block;"></span> Available</span>
                            <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;border-radius:50%;background:rgba(239,71,111,0.5);display:inline-block;"></span> Taken</span>
                        </div>
                    </div>
                    <div id="spotGridContent" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(65px,1fr));gap:8px;max-height:200px;overflow-y:auto;padding:4px;"></div>
                </div>

                <!-- Date & Time -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Start Date & Time <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-calendar"></i></span>
                            <input type="datetime-local" name="start_time" class="form-control" id="startTime"
                                   min="<?php echo e(now()->addMinutes(5)->format('Y-m-d\TH:i')); ?>"
                                   value="<?php echo e(old('start_time', now()->addHour()->format('Y-m-d\TH:i'))); ?>"
                                   onchange="updateMinEndTime()" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date & Time <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-calendar-check"></i></span>
                            <input type="datetime-local" name="end_time" class="form-control" id="endTime"
                                   min="<?php echo e(now()->addHours(2)->format('Y-m-d\TH:i')); ?>"
                                   value="<?php echo e(old('end_time', now()->addHours(3)->format('Y-m-d\TH:i'))); ?>"
                                   onchange="updateDuration()" required>
                        </div>
                    </div>
                </div>

                <!-- Duration Display -->
                <div id="durationDisplay" style="background:rgba(108,99,255,0.05);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-clock" style="color:var(--primary);"></i>
                    <span style="font-size:14px;font-weight:600;color:var(--text-primary);" id="durationText">Duration: calculating...</span>
                </div>

                <!-- Vehicle Plate -->
                <div class="form-group">
                    <label class="form-label">Vehicle License Plate</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-id-card"></i></span>
                        <input type="text" name="vehicle_plate" class="form-control"
                               placeholder="<?php echo e(auth()->user()->vehicle_plate ?? 'e.g. ABC 123'); ?>"
                               value="<?php echo e(old('vehicle_plate', auth()->user()->vehicle_plate)); ?>"
                               style="text-transform:uppercase;">
                    </div>
                    <p class="form-hint">Leave blank to use your registered vehicle plate</p>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Any special notes or instructions..."><?php echo e(old('notes')); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg" id="submitBtn">
                    <i class="fas fa-calendar-plus"></i>
                    Confirm Reservation
                </button>
            </form>
        </div>

        <!-- Summary Panel -->
        <div>
            <!-- Reservation Summary -->
            <div class="card mb-20" id="summaryCard" style="background:linear-gradient(135deg,rgba(108,99,255,0.03),rgba(79,172,254,0.03));border:1px solid rgba(108,99,255,0.1);">
                <div style="font-size:15px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-receipt" style="color:var(--primary);"></i> Reservation Summary
                </div>

                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;justify-content:space-between;padding:12px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border-color);">
                        <span style="font-size:13px;color:var(--text-muted);">Spot</span>
                        <span style="font-size:13px;font-weight:700;" id="summarySpot">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:12px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border-color);">
                        <span style="font-size:13px;color:var(--text-muted);">Zone</span>
                        <span style="font-size:13px;font-weight:700;" id="summaryZone">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:12px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border-color);">
                        <span style="font-size:13px;color:var(--text-muted);">Duration</span>
                        <span style="font-size:13px;font-weight:700;" id="summaryDuration">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:12px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border-color);">
                        <span style="font-size:13px;color:var(--text-muted);">Vehicle</span>
                        <span style="font-size:13px;font-weight:700;"><?php echo e(auth()->user()->vehicle_plate ?? 'Not set'); ?></span>
                    </div>
                </div>

                <div style="margin-top:16px;padding:14px;background:rgba(6,214,160,0.06);border:1px solid rgba(6,214,160,0.2);border-radius:var(--radius-md);">
                    <div style="font-size:12px;color:var(--success);font-weight:700;display:flex;align-items:center;gap:6px;">
                        <i class="fas fa-shield-alt"></i> Free Reservation
                    </div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">Campus parking reservations are free for all registered university members.</div>
                </div>
            </div>

            <!-- Rules Card -->
            <div class="card">
                <div style="font-size:15px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-info-circle" style="color:var(--secondary);"></i> Reservation Rules
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--text-secondary);">
                        <i class="fas fa-check" style="color:var(--success);margin-top:2px;flex-shrink:0;"></i>
                        Reservations can be cancelled up to the start time
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--text-secondary);">
                        <i class="fas fa-check" style="color:var(--success);margin-top:2px;flex-shrink:0;"></i>
                        The system prevents double-booking automatically
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--text-secondary);">
                        <i class="fas fa-check" style="color:var(--success);margin-top:2px;flex-shrink:0;"></i>
                        You will receive a confirmation notification
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--text-secondary);">
                        <i class="fas fa-exclamation-triangle" style="color:var(--warning);margin-top:2px;flex-shrink:0;"></i>
                        Overstaying your reservation may result in a violation
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--text-secondary);">
                        <i class="fas fa-exclamation-triangle" style="color:var(--warning);margin-top:2px;flex-shrink:0;"></i>
                        Parking in reserved spots without booking is not allowed
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const zones = <?php echo json_encode($zonesData, 15, 512) ?>;

    let selectedSpotData = null;
    <?php if($selectedSpot): ?>
    selectedSpotData = {
        id: <?php echo e($selectedSpot->id); ?>,
        name: '<?php echo e($selectedSpot->zone->code); ?>-<?php echo e($selectedSpot->spot_number); ?>',
        zone: '<?php echo e($selectedSpot->zone->name); ?>'
    };
    document.getElementById('summarySpot').textContent = selectedSpotData.name;
    document.getElementById('summaryZone').textContent = selectedSpotData.zone;
    <?php endif; ?>

    function loadZoneSpots(zoneId) {
        const grid = document.getElementById('zoneSpotGrid');
        const content = document.getElementById('spotGridContent');

        if (!zoneId) {
            grid.style.display = 'none';
            return;
        }

        const zone = zones.find(z => z.id == zoneId);
        if (!zone) return;

        const typeIcons = { standard: 'fa-car', staff: 'fa-briefcase', disabled: 'fa-wheelchair', vip: 'fa-star' };

        content.innerHTML = zone.spots.map(spot => `
            <div class="parking-spot ${spot.status}" data-spot-id="${spot.id}" data-zone-code="${zone.code}" data-zone-name="${zone.name}"
                 ${spot.status === 'available' ? `onclick="chooseSpot(${spot.id}, '${zone.code}-${spot.spot_number}', '${zone.name}', '${spot.type}')"` : ''}
                 style="cursor:${spot.status === 'available' ? 'pointer' : 'not-allowed'};">
                <i class="fas ${typeIcons[spot.type] || 'fa-car'}" style="font-size:14px;"></i>
                <span style="font-size:10px;font-weight:700;">${zone.code}-${spot.spot_number}</span>
            </div>
        `).join('');

        grid.style.display = 'block';
    }

    function chooseSpot(id, name, zoneName, type) {
        // Clear previous selection
        document.querySelectorAll('#spotGridContent .parking-spot.selected').forEach(el => {
            el.classList.remove('selected');
        });

        // Select new spot
        const el = document.querySelector(`#spotGridContent [data-spot-id="${id}"]`);
        if (el) el.classList.add('selected');

        // Update hidden input
        document.getElementById('spotIdInput').value = id;

        // Update display
        document.getElementById('spotSelector').innerHTML = `
            <div id="selectedSpotDisplay" style="display:flex;align-items:center;justify-content:space-between;background:rgba(108,99,255,0.06);border:2px solid rgba(108,99,255,0.2);border-radius:var(--radius-md);padding:14px 18px;">
                <div>
                    <div style="font-size:24px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">${name}</div>
                    <div style="font-size:13px;color:var(--text-muted);">${zoneName} — ${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                </div>
                <span class="badge badge-success">Selected</span>
            </div>
        `;

        document.getElementById('summarySpot').textContent = name;
        document.getElementById('summaryZone').textContent = zoneName;
    }

    function updateMinEndTime() {
        const start = document.getElementById('startTime').value;
        if (start) {
            const startDate = new Date(start);
            startDate.setMinutes(startDate.getMinutes() + 30);
            document.getElementById('endTime').min = startDate.toISOString().slice(0, 16);
        }
        updateDuration();
    }

    function updateDuration() {
        const start = new Date(document.getElementById('startTime').value);
        const end = new Date(document.getElementById('endTime').value);

        if (start && end && end > start) {
            const diffMs = end - start;
            const hours = Math.floor(diffMs / (1000 * 60 * 60));
            const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

            let durationText = '';
            if (hours > 0) durationText += hours + 'h ';
            durationText += minutes + 'm';

            document.getElementById('durationText').textContent = 'Duration: ' + durationText;
            document.getElementById('summaryDuration').textContent = durationText;
        } else {
            document.getElementById('durationText').textContent = 'Duration: Invalid time range';
        }
    }

    // Form validation
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        const spotId = document.getElementById('spotIdInput').value;
        if (!spotId) {
            e.preventDefault();
            alert('Please select a parking spot before submitting.');
            return;
        }
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<div class="spinner" style="width:18px;height:18px;border-width:2px;"></div> Processing...';
    });

    // Initialize duration
    updateDuration();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xamp\htdocs\unipark\resources\views/user/reserve.blade.php ENDPATH**/ ?>