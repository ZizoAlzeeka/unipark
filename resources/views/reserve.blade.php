<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UniPark — Reserve a Spot</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
      .reserve-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
      }
      @media (max-width: 1100px) {
        .reserve-layout {
          grid-template-columns: 1fr;
        }
      }
      .map-wrapper {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        overflow: auto;
      }
      .parking-zone-label {
        font-family: "Syne", sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 8px 0 6px;
      }
      .zone-divider {
        height: 20px;
      }
      .road {
        height: 28px;
        background: repeating-linear-gradient(
          90deg,
          var(--surface2) 0 8px,
          transparent 8px 16px
        );
        border-radius: 4px;
        margin: 6px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text3);
        font-size: 0.7rem;
        letter-spacing: 0.05em;
      }
      .parking-row-wrap {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
      }
      .entrance-gate {
        background: rgba(0, 212, 180, 0.1);
        border: 1px solid rgba(0, 212, 180, 0.3);
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--teal);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
      }
      .spot-tooltip {
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.75rem;
        white-space: nowrap;
        z-index: 10;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.15s;
        box-shadow: var(--shadow);
      }
      .parking-spot:hover .spot-tooltip {
        opacity: 1;
      }
      .panel-sticky {
        position: sticky;
        top: 80px;
      }
      .time-slots {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 16px;
      }
      .time-slot {
        padding: 10px 12px;
        background: var(--surface);
        border: 2px solid var(--border2);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.82rem;
        text-align: center;
        color: var(--text2);
        font-weight: 500;
      }
      .time-slot.active {
        border-color: var(--accent);
        background: rgba(61, 127, 255, 0.12);
        color: var(--accent2);
      }
      .time-slot:hover:not(.active) {
        border-color: var(--border2);
        background: var(--surface2);
      }
      .filter-chips {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
      }
      .chip {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        background: var(--surface);
        border: 1px solid var(--border2);
        color: var(--text2);
      }
      .chip.active {
        background: rgba(61, 127, 255, 0.15);
        border-color: var(--accent);
        color: var(--accent2);
      }
      .chip:hover:not(.active) {
        background: var(--surface2);
      }
    </style>
  </head>
  <body>
    <div id="sidebarMount"></div>

    <div class="page-wrapper" id="pageWrapper">
      <div style="display: flex; flex-direction: column; flex: 1">
        <div class="topbar">
          <button class="topbar-btn menu-btn" onclick="toggleSidebar()">
            ☰
          </button>
          <span class="topbar-title">Reserve a Parking Spot</span>
          <div class="topbar-actions">
            <button
              class="topbar-btn"
              onclick="nav('notifications.html')"
              style="position: relative"
            >
              🔔
              <div class="notif-dot"></div>
            </button>
            <div
              class="avatar"
              style="cursor: pointer"
              onclick="nav('profile.html')"
            >
              RA
            </div>
          </div>
        </div>

        <div class="main-content">
          <div class="page-header">
            <div class="breadcrumb">
              🏠 <span>Dashboard</span> / Reserve a Spot
            </div>
            <div class="page-header-row">
              <div>
                <h1>Parking Map</h1>
                <p>
                  Select a date, time, and available spot to make a reservation
                </p>
              </div>
              <div style="display: flex; align-items: center; gap: 8px">
                <span class="badge badge-active"
                  ><span class="pulse">●</span> Live Updates</span
                >
              </div>
            </div>
          </div>

          <!-- Filters -->
          <div class="filter-bar">
            <div class="search-box" style="flex: 0 0 auto; min-width: 160px">
              <span>📅</span>
              <input
                type="date"
                id="reserveDate"
                style="
                  background: none;
                  border: none;
                  outline: none;
                  color: var(--text);
                  font-family: &quot;DM Sans&quot;, sans-serif;
                  font-size: 0.88rem;
                "
              />
            </div>
            <select
              class="filter-select"
              id="floorSelect"
              onchange="renderMap()"
            >
              <option value="all">All Zones</option>
              <option value="A">Zone A</option>
              <option value="B">Zone B</option>
              <option value="C">Zone C (Staff)</option>
              <option value="D">Zone D (Visitor)</option>
            </select>
            <div class="filter-chips">
              <div class="chip active" onclick="toggleChip(this, 'standard')">
                Standard
              </div>
              <div class="chip active" onclick="toggleChip(this, 'staff')">
                Staff
              </div>
              <div class="chip active" onclick="toggleChip(this, 'disabled')">
                Accessible ♿
              </div>
            </div>
            <button class="btn btn-ghost btn-sm" onclick="renderMap()">
              🔄 Refresh
            </button>
          </div>

          <div class="reserve-layout">
            <!-- Map Panel -->
            <div class="map-wrapper">
              <div
                style="
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  margin-bottom: 20px;
                  flex-wrap: wrap;
                  gap: 12px;
                "
              >
                <div>
                  <div
                    style="
                      font-family: &quot;Syne&quot;, sans-serif;
                      font-weight: 700;
                      font-size: 1rem;
                    "
                  >
                    University Parking Campus Map
                  </div>
                  <p style="font-size: 0.8rem; margin-top: 2px">
                    Click on a green spot to select it ·
                    <span
                      id="availCount"
                      style="color: var(--green); font-weight: 600"
                      >148 available</span
                    >
                  </p>
                </div>
                <div class="legend">
                  <div class="legend-item">
                    <div
                      class="legend-dot"
                      style="
                        background: rgba(0, 200, 150, 0.5);
                        border: 1px solid var(--green);
                      "
                    ></div>
                    Available
                  </div>
                  <div class="legend-item">
                    <div
                      class="legend-dot"
                      style="
                        background: rgba(61, 127, 255, 0.5);
                        border: 1px solid var(--accent);
                      "
                    ></div>
                    Reserved
                  </div>
                  <div class="legend-item">
                    <div
                      class="legend-dot"
                      style="
                        background: rgba(255, 77, 109, 0.5);
                        border: 1px solid var(--red);
                      "
                    ></div>
                    Occupied
                  </div>
                  <div class="legend-item">
                    <div
                      class="legend-dot"
                      style="
                        background: rgba(245, 200, 66, 0.5);
                        border: 1px solid var(--gold);
                      "
                    ></div>
                    Accessible
                  </div>
                </div>
              </div>

              <div id="parkingMap">
                <!-- Rendered by JS -->
              </div>
            </div>

            <!-- Booking Panel -->
            <div class="panel-sticky">
              <div class="card">
                <h3 class="card-title" style="margin-bottom: 4px">
                  Booking Details
                </h3>
                <p
                  style="
                    font-size: 0.82rem;
                    color: var(--text3);
                    margin-bottom: 20px;
                  "
                >
                  Selected spot info will appear here
                </p>

                <div id="spotInfoPanel">
                  <div class="empty-state" style="padding: 30px 10px">
                    <div class="icon">🅿️</div>
                    <p style="font-size: 0.85rem">
                      Click an available spot on the map to select it
                    </p>
                  </div>
                </div>
              </div>

              <!-- Duration -->
              <div class="card mt-16">
                <h3 class="card-title" style="margin-bottom: 16px">
                  Reservation Time
                </h3>
                <div class="form-group">
                  <label class="form-label">Date</label>
                  <input type="date" class="form-control" id="resDate" />
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label class="form-label">Start Time</label>
                    <select class="form-control" id="startTime">
                      <option>8:00 AM</option>
                      <option>8:30 AM</option>
                      <option selected>9:00 AM</option>
                      <option>9:30 AM</option>
                      <option>10:00 AM</option>
                      <option>10:30 AM</option>
                      <option>11:00 AM</option>
                      <option>11:30 AM</option>
                      <option>12:00 PM</option>
                      <option>12:30 PM</option>
                      <option>1:00 PM</option>
                      <option>1:30 PM</option>
                      <option>2:00 PM</option>
                      <option>2:30 PM</option>
                      <option>3:00 PM</option>
                      <option>3:30 PM</option>
                      <option>4:00 PM</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="form-label">End Time</label>
                    <select class="form-control" id="endTime">
                      <option>9:00 AM</option>
                      <option>9:30 AM</option>
                      <option>10:00 AM</option>
                      <option>10:30 AM</option>
                      <option selected>11:00 AM</option>
                      <option>11:30 AM</option>
                      <option>12:00 PM</option>
                      <option>12:30 PM</option>
                      <option>1:00 PM</option>
                      <option>1:30 PM</option>
                      <option>2:00 PM</option>
                      <option>2:30 PM</option>
                      <option>3:00 PM</option>
                      <option>3:30 PM</option>
                      <option>4:00 PM</option>
                      <option>4:30 PM</option>
                      <option>5:00 PM</option>
                    </select>
                  </div>
                </div>
                <div
                  style="
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 6px;
                    margin-bottom: 16px;
                  "
                >
                  <div
                    class="time-slot active"
                    onclick="setDuration(this, '1h')"
                  >
                    1 Hour
                  </div>
                  <div class="time-slot" onclick="setDuration(this, '2h')">
                    2 Hours
                  </div>
                  <div class="time-slot" onclick="setDuration(this, '4h')">
                    4 Hours
                  </div>
                  <div class="time-slot" onclick="setDuration(this, '6h')">
                    6 Hours
                  </div>
                  <div class="time-slot" onclick="setDuration(this, '8h')">
                    8 Hours
                  </div>
                  <div class="time-slot" onclick="setDuration(this, 'full')">
                    Full Day
                  </div>
                </div>
                <div
                  style="
                    background: rgba(0, 200, 150, 0.08);
                    border: 1px solid rgba(0, 200, 150, 0.2);
                    border-radius: var(--radius-sm);
                    padding: 12px;
                    margin-bottom: 16px;
                    display: flex;
                    gap: 8px;
                  "
                >
                  <span>✅</span>
                  <div style="font-size: 0.8rem; color: var(--text2)">
                    No charges apply. Parking is complimentary for registered
                    students and staff.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reserve Confirmation Modal -->
    <div class="modal-overlay" id="reserveModal">
      <div class="modal">
        <div class="modal-header">
          <div>
            <h3>Confirm Reservation</h3>
            <p style="font-size: 0.85rem; margin-top: 4px">
              Please review your booking details
            </p>
          </div>
          <button class="modal-close" onclick="closeModal('reserveModal')">
            ✕
          </button>
        </div>
        <div
          style="
            background: var(--surface);
            border-radius: var(--radius-sm);
            padding: 18px;
            margin-bottom: 20px;
          "
        >
          <div
            style="
              display: grid;
              grid-template-columns: 1fr 1fr;
              gap: 12px;
              font-size: 0.88rem;
            "
          >
            <div>
              <div style="color: var(--text3); font-size: 0.75rem">Spot</div>
              <div
                style="font-weight: 700; color: var(--text); font-size: 1rem"
                id="confSpot"
              >
                —
              </div>
            </div>
            <div>
              <div style="color: var(--text3); font-size: 0.75rem">Zone</div>
              <div style="font-weight: 600; color: var(--text)">Zone B</div>
            </div>
            <div>
              <div style="color: var(--text3); font-size: 0.75rem">Date</div>
              <div style="font-weight: 600; color: var(--text)" id="confDate">
                —
              </div>
            </div>
            <div>
              <div style="color: var(--text3); font-size: 0.75rem">
                Duration
              </div>
              <div style="font-weight: 600; color: var(--text)">2 Hours</div>
            </div>
            <div>
              <div style="color: var(--text3); font-size: 0.75rem">Start</div>
              <div style="font-weight: 600; color: var(--text)" id="confStart">
                9:00 AM
              </div>
            </div>
            <div>
              <div style="color: var(--text3); font-size: 0.75rem">End</div>
              <div style="font-weight: 600; color: var(--text)" id="confEnd">
                11:00 AM
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Notes (optional)</label>
          <input
            type="text"
            class="form-control"
            placeholder="e.g. I drive a white Toyota Camry"
          />
        </div>
        <div
          style="
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
          "
        >
          <input
            type="checkbox"
            id="autoCancel"
            checked
            style="accent-color: var(--accent); width: 16px; height: 16px"
          />
          <label
            for="autoCancel"
            style="font-size: 0.82rem; color: var(--text2)"
            >Auto-cancel if not arrived within 15 minutes</label
          >
        </div>
        <div class="modal-footer">
          <button class="btn btn-ghost" onclick="closeModal('reserveModal')">
            Cancel
          </button>
          <button class="btn btn-primary" onclick="confirmReservation()">
            🎉 Confirm Booking
          </button>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div class="modal-overlay" id="successModal">
      <div class="modal" style="max-width: 400px; text-align: center">
        <div style="font-size: 4rem; margin-bottom: 16px">🎉</div>
        <h2
          style="font-family: &quot;Syne&quot;, sans-serif; margin-bottom: 8px"
        >
          Reservation Confirmed!
        </h2>
        <p style="color: var(--text2); margin-bottom: 24px">
          Your parking spot has been successfully reserved. You'll receive a
          confirmation notification.
        </p>
        <div
          style="
            background: var(--surface);
            border-radius: var(--radius-sm);
            padding: 16px;
            text-align: left;
            margin-bottom: 24px;
          "
        >
          <div
            style="
              display: grid;
              grid-template-columns: 1fr 1fr;
              gap: 8px;
              font-size: 0.85rem;
            "
          >
            <div>
              <div style="color: var(--text3)">Booking ID</div>
              <div style="font-weight: 700; color: var(--accent2)">
                #RES-2026-0847
              </div>
            </div>
            <div>
              <div style="color: var(--text3)">Spot</div>
              <div style="font-weight: 600; color: var(--text)" id="sucSpot">
                —
              </div>
            </div>
          </div>
        </div>
        <div style="display: flex; gap: 10px">
          <button
            class="btn btn-ghost"
            style="flex: 1; justify-content: center"
            onclick="nav('my-reservations.html')"
          >
            View Reservations
          </button>
          <button
            class="btn btn-primary"
            style="flex: 1; justify-content: center"
            onclick="
              closeModal('successModal');
              location.reload();
            "
          >
            Reserve Another
          </button>
        </div>
      </div>
    </div>

    <div id="toastContainer" class="toast-container"></div>
    <script src="js/app.js"></script>
    <script src="js/sidebar.js"></script>
    <script>
      document.getElementById("sidebarMount").innerHTML = renderSidebar(
        "student",
        "reserve",
      );
      document.getElementById("reserveDate").valueAsDate = new Date();
      document.getElementById("resDate").valueAsDate = new Date();

      document.querySelector(".page-wrapper").style.marginLeft =
        window.innerWidth > 1024 ? "260px" : "0";
      window.addEventListener("resize", () => {
        document.querySelector(".page-wrapper").style.marginLeft =
          window.innerWidth > 1024 ? "260px" : "0";
      });

      // Parking data
      const zones = {
        A: {
          label: "Zone A — General Parking",
          spots: 15,
          color: "var(--accent)",
        },
        B: {
          label: "Zone B — General Parking",
          spots: 14,
          color: "var(--teal)",
        },
        C: { label: "Zone C — Staff Parking", spots: 10, color: "var(--gold)" },
        D: {
          label: "Zone D — Visitor & Accessible",
          spots: 8,
          color: "var(--red)",
        },
      };

      const statuses = {
        "A-01": "available",
        "A-02": "available",
        "A-03": "occupied",
        "A-04": "reserved",
        "A-05": "available",
        "A-06": "available",
        "A-07": "available",
        "A-08": "occupied",
        "A-09": "available",
        "A-10": "available",
        "A-11": "reserved",
        "A-12": "available",
        "A-13": "available",
        "A-14": "occupied",
        "A-15": "available",
        "B-01": "available",
        "B-02": "reserved",
        "B-03": "available",
        "B-04": "available",
        "B-05": "occupied",
        "B-06": "available",
        "B-07": "available",
        "B-08": "reserved",
        "B-09": "available",
        "B-10": "available",
        "B-11": "occupied",
        "B-12": "available",
        "B-13": "available",
        "B-14": "reserved",
        "C-01": "available",
        "C-02": "available",
        "C-03": "occupied",
        "C-04": "available",
        "C-05": "reserved",
        "C-06": "available",
        "C-07": "available",
        "C-08": "occupied",
        "C-09": "available",
        "C-10": "available",
        "D-01": "disabled",
        "D-02": "disabled",
        "D-03": "available",
        "D-04": "available",
        "D-05": "reserved",
        "D-06": "available",
        "D-07": "occupied",
        "D-08": "available",
      };

      const types = {};
      ["D-01", "D-02"].forEach((s) => (types[s] = "accessible"));
      [
        "C-01",
        "C-02",
        "C-03",
        "C-04",
        "C-05",
        "C-06",
        "C-07",
        "C-08",
        "C-09",
        "C-10",
      ].forEach((s) => (types[s] = "staff"));

      function renderMap() {
        const filter = document.getElementById("floorSelect").value;
        const map = document.getElementById("parkingMap");
        let html = "";

        Object.entries(zones).forEach(([zoneKey, zone]) => {
          if (filter !== "all" && filter !== zoneKey) return;
          html += `<div class="parking-zone-label">${zone.label}</div>`;
          html += `<div class="entrance-gate">🚗 Entrance Gate ${zoneKey}</div>`;
          html += `<div class="road">← DRIVE LANE →</div>`;
          html += `<div class="parking-row-wrap">`;
          for (let i = 1; i <= zone.spots; i++) {
            const id = `${zoneKey}-${String(i).padStart(2, "0")}`;
            const status = statuses[id] || "available";
            const type = types[id] || "standard";
            const icon =
              type === "accessible" ? "♿" : type === "staff" ? "🏢" : "🚗";
            html += `
        <div class="parking-spot ${status}" data-zone="${zoneKey}" data-type="${type}"
             style="position:relative" onclick="selectSpot(this,'${id}')">
          <div class="spot-icon">${icon}</div>
          <div class="spot-num">${id}</div>
          <div class="spot-tooltip">${id} · ${type} · ${status}</div>
        </div>`;
          }
          html += '</div><div class="zone-divider"></div>';
        });
        map.innerHTML = html;
      }

      renderMap();

      function toggleChip(el, type) {
        el.classList.toggle("active");
      }

      function setDuration(el, dur) {
        document
          .querySelectorAll(".time-slot")
          .forEach((t) => t.classList.remove("active"));
        el.classList.add("active");
      }

      function confirmReservation() {
        if (!selectedSpot) {
          showToast(
            "No Spot Selected",
            "Please select an available spot first",
            "error",
          );
          closeModal("reserveModal");
          return;
        }
        const spotId = selectedSpot.querySelector(".spot-num").textContent;
        document.getElementById("sucSpot").textContent = spotId;
        closeModal("reserveModal");
        openModal("successModal");
      }

      // Override updateSpotInfo to use local modal trigger
      function updateSpotInfo(spot) {
        const panel = document.getElementById("spotInfoPanel");
        if (!panel) return;
        if (!spot) {
          panel.innerHTML =
            '<div class="empty-state" style="padding:30px 10px"><div class="icon">🅿️</div><p style="font-size:0.85rem">Click an available spot on the map to select it</p></div>';
          return;
        }
        panel.innerHTML = `
    <div style="display:flex;gap:12px;align-items:center;margin-bottom:16px">
      <div style="width:52px;height:52px;background:rgba(61,127,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.8rem">🅿️</div>
      <div>
        <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.3rem;color:var(--text)">Spot ${spot.id}</div>
        <div style="font-size:0.8rem;color:var(--text3)">Zone ${spot.zone} · ${spot.type || "Standard"}</div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;font-size:0.82rem">
      <div style="background:var(--surface);padding:10px;border-radius:8px"><div style="color:var(--text3)">Status</div><div style="color:var(--green);font-weight:600;margin-top:2px">Available ✓</div></div>
      <div style="background:var(--surface);padding:10px;border-radius:8px"><div style="color:var(--text3)">Type</div><div style="color:var(--text);font-weight:600;margin-top:2px">${spot.type || "Standard"}</div></div>
      <div style="background:var(--surface);padding:10px;border-radius:8px"><div style="color:var(--text3)">Zone</div><div style="color:var(--text);font-weight:600;margin-top:2px">Zone ${spot.zone}</div></div>
      <div style="background:var(--surface);padding:10px;border-radius:8px"><div style="color:var(--text3)">Floor</div><div style="color:var(--text);font-weight:600;margin-top:2px">Ground</div></div>
    </div>
    <button class="btn btn-primary w-full" style="justify-content:center" onclick="prepareReservation('${spot.id}')">Reserve Spot ${spot.id}</button>
  `;
      }

      function prepareReservation(id) {
        document.getElementById("confSpot").textContent = id;
        document.getElementById("confDate").textContent =
          document.getElementById("resDate").value || "Today";
        document.getElementById("confStart").textContent =
          document.getElementById("startTime").value;
        document.getElementById("confEnd").textContent =
          document.getElementById("endTime").value;
        openModal("reserveModal");
      }
    </script>
  </body>
</html>
