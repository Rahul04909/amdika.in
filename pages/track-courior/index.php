<?php
$page_title       = "Track Your Order — Amadika";
$page_description = "Track your Amadika shipment in real-time with our courier tracking tool.";
include '../../includes/header.php';
?>

<style>
.track-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
    padding: 60px 0 50px;
    text-align: center;
    color: #fff;
}
.track-hero h1 { font-size: 32px; font-weight: 700; margin: 0 0 6px; }
.track-hero p { color: rgba(255,255,255,.6); font-size: 15px; margin: 0; }

.track-section { max-width: 780px; margin: -30px auto 40px; padding: 0 16px; position: relative; z-index: 2; }

.track-card { background: #fff; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,.08); overflow: hidden; }
.track-card-body { padding: 28px 32px; }

.track-input-group { display: flex; gap: 10px; }
.track-input-group input {
    flex: 1; height: 52px; border: 2px solid #e5e7eb; border-radius: 10px; padding: 0 16px;
    font-size: 16px; font-weight: 500; letter-spacing: 1px; outline: none;
    transition: border-color .2s;
}
.track-input-group input:focus { border-color: #f97316; }
.track-input-group button {
    height: 52px; padding: 0 28px; background: #f97316; color: #fff; border: none;
    border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; gap: 8px; transition: all .2s; white-space: nowrap;
}
.track-input-group button:hover { background: #ea580c; }
.track-input-group button:disabled { opacity: .6; cursor: not-allowed; }
.track-input-group button .spinner { display: none; width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
.track-input-group button.loading .spinner { display: inline-block; }
.track-input-group button.loading .btn-text { display: none; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Result ── */
.track-result { margin-top: 24px; }

.track-status-bar { display: flex; align-items: center; gap: 14px; padding: 16px 20px; border-radius: 10px; margin-bottom: 20px; }
.track-status-bar.delivered { background: #f0fdf4; border: 1px solid #86efac; }
.track-status-bar.in-transit { background: #fff7ed; border: 1px solid #fdba74; }
.track-status-bar.pending { background: #f5f5f5; border: 1px solid #e5e7eb; }
.track-status-bar .status-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
.track-status-bar.delivered .status-icon { background: #22c55e; color: #fff; }
.track-status-bar.in-transit .status-icon { background: #f97316; color: #fff; }
.track-status-bar.pending .status-icon { background: #9ca3af; color: #fff; }
.track-status-bar .status-info { flex: 1; }
.track-status-bar .status-info .status-label { font-size: 16px; font-weight: 700; color: #111827; }
.track-status-bar .status-info .status-date { font-size: 13px; color: #6b7280; margin-top: 2px; }

/* ── Timeline ── */
.track-timeline { position: relative; padding-left: 32px; }
.track-timeline::before { content: ''; position: absolute; left: 11px; top: 8px; bottom: 8px; width: 2px; background: #e5e7eb; }
.timeline-item { position: relative; padding: 0 0 24px 20px; }
.timeline-item:last-child { padding-bottom: 0; }
.timeline-item .dot { position: absolute; left: -28px; top: 4px; width: 14px; height: 14px; border-radius: 50%; border: 2px solid #d1d5db; background: #fff; z-index: 1; }
.timeline-item.completed .dot { background: #22c55e; border-color: #22c55e; }
.timeline-item.active .dot { background: #f97316; border-color: #f97316; box-shadow: 0 0 0 4px rgba(249,115,22,.2); }
.timeline-item .time { font-size: 12px; color: #9ca3af; margin-bottom: 2px; }
.timeline-item .event { font-size: 14px; font-weight: 600; color: #111827; }
.timeline-item .location { font-size: 13px; color: #6b7280; margin-top: 2px; }

/* ── Info Grid ── */
.track-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
.track-info-item { }
.track-info-item .label { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #9ca3af; font-weight: 600; margin-bottom: 4px; }
.track-info-item .value { font-size: 14px; font-weight: 500; color: #111827; }

/* ── Error ── */
.track-error { padding: 20px; text-align: center; color: #ef4444; background: #fef2f2; border-radius: 10px; border: 1px solid #fecaca; margin-top: 24px; }
.track-error i { font-size: 24px; margin-bottom: 8px; display: block; }
.track-error .msg { font-size: 14px; font-weight: 500; }

/* ── Empty ── */
.track-empty { text-align: center; padding: 40px 20px; color: #9ca3af; }
.track-empty i { font-size: 48px; margin-bottom: 12px; display: block; opacity: .3; }
.track-empty p { font-size: 14px; margin: 0; }

@media (max-width: 576px) {
    .track-hero { padding: 40px 0 36px; }
    .track-hero h1 { font-size: 24px; }
    .track-card-body { padding: 20px; }
    .track-input-group { flex-direction: column; }
    .track-input-group button { justify-content: center; }
    .track-info-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<section class="track-hero">
    <h1>Track Your Shipment</h1>
    <p>Enter your AWB number to track your Amadika order in real-time</p>
</section>

<div class="track-section">
    <div class="track-card">
        <div class="track-card-body">
            <div class="track-input-group">
                <input type="text" id="awbInput" value="26040200188266" placeholder="Enter AWB number" autocomplete="off">
                <button id="trackBtn" onclick="trackOrder()">
                    <span class="spinner"></span>
                    <span class="btn-text"><i class="fas fa-search me-2"></i>Track</span>
                </button>
            </div>

            <div id="trackResult" class="track-result"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const awbInput = document.getElementById('awbInput');
const trackBtn = document.getElementById('trackBtn');
const trackResult = document.getElementById('trackResult');

awbInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') trackOrder();
});

function escHtml(str) {
    if (!str) return '';
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    try {
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }) +
               ' · ' + d.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
    } catch { return dateStr; }
}

function getStatusMeta(status) {
    const s = (status || '').toLowerCase();
    if (s.includes('deliver') || s.includes('delivered')) return { cls: 'delivered', icon: 'fas fa-check-circle', label: 'Delivered' };
    if (s.includes('transit') || s.includes('pickup') || s.includes('out for')) return { cls: 'in-transit', icon: 'fas fa-truck', label: 'In Transit' };
    if (s.includes('manifest') || s.includes('booked') || s.includes('lab') || s.includes('ready')) return { cls: 'in-transit', icon: 'fas fa-box', label: 'Processing' };
    return { cls: 'pending', icon: 'fas fa-clock', label: status || 'Pending' };
}

function trackOrder() {
    const awb = awbInput.value.trim();
    if (!awb) {
        Swal.fire({ icon: 'warning', title: 'Enter AWB Number', text: 'Please enter a valid AWB number to track.', confirmButtonColor: '#f97316' });
        return;
    }

    trackBtn.classList.add('loading');
    trackBtn.disabled = true;
    trackResult.innerHTML = '';

    fetch('https://apis-hubops.innofulfill.com/tracking/v2/' + encodeURIComponent(awb))
        .then(res => {
            if (!res.ok) throw new Error('API returned status ' + res.status);
            return res.json();
        })
        .then(data => {
            renderTracking(data);
        })
        .catch(err => {
            trackResult.innerHTML = `
                <div class="track-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="msg">Unable to fetch tracking details. Please try again later.</div>
                    <div style="font-size:12px;color:#9ca3af;margin-top:6px">${escHtml(err.message)}</div>
                </div>
            `;
        })
        .finally(() => {
            trackBtn.classList.remove('loading');
            trackBtn.disabled = false;
        });
}

function renderTracking(data) {
    // Normalize: API might return data in different structures
    const info = data?.data || data?.result || data;
    const trackingData = info?.tracking_data || info?.shipment || info;
    const events = trackingData?.scan_events || trackingData?.events || trackingData?.scans || info?.scan_events || [];
    const status = trackingData?.current_status || trackingData?.status || info?.current_status || info?.status || 'Pending';
    const awb = trackingData?.awb_number || trackingData?.awb || info?.awb_number || info?.awb || awbInput.value.trim();
    const origin = trackingData?.origin || trackingData?.from || info?.origin || '';
    const destination = trackingData?.destination || trackingData?.to || info?.destination || '';
    const courier = trackingData?.courier || trackingData?.carrier || info?.courier || info?.carrier || '';
    const weight = trackingData?.weight || info?.weight || '';
    const eta = trackingData?.eta || trackingData?.delivery_date || info?.eta || '';
    const sender = trackingData?.sender || info?.sender || '';
    const receiver = trackingData?.receiver || info?.receiver || '';
    const refNo = trackingData?.reference_no || trackingData?.ref_no || info?.reference_no || '';

    const statusMeta = getStatusMeta(status);
    const hasEvents = Array.isArray(events) && events.length > 0;

    let timelineHtml = '';
    if (hasEvents) {
        events.forEach((ev, idx) => {
            const isLast = idx === events.length - 1;
            const cls = isLast ? 'active' : 'completed';
            const loc = ev.location || ev.city || ev.place || ev.scan_location || '';
            const time = formatDate(ev.scan_datetime || ev.datetime || ev.date || ev.timestamp || '');
            const desc = ev.scan_description || ev.description || ev.event || ev.status || ev.scan || '';
            timelineHtml += `
                <div class="timeline-item ${cls}">
                    <div class="dot"></div>
                    <div class="time">${escHtml(time)}</div>
                    <div class="event">${escHtml(desc)}</div>
                    ${loc ? '<div class="location"><i class="fas fa-map-marker-alt me-1" style="color:#9ca3af;font-size:11px"></i>' + escHtml(loc) + '</div>' : ''}
                </div>
            `;
        });
    }

    trackResult.innerHTML = `
        <div class="track-status-bar ${statusMeta.cls}">
            <div class="status-icon"><i class="${statusMeta.icon}"></i></div>
            <div class="status-info">
                <div class="status-label">${statusMeta.label} — ${escHtml(awb)}</div>
                <div class="status-date">${escHtml(courier)}</div>
            </div>
        </div>

        ${hasEvents ? `
        <div class="track-timeline">${timelineHtml}</div>
        ` : `
        <div class="track-empty">
            <i class="fas fa-box-open"></i>
            <p>No tracking events available yet for this AWB number.</p>
        </div>
        `}

        <div class="track-info-grid">
            ${origin ? `<div class="track-info-item"><div class="label">Origin</div><div class="value">${escHtml(origin)}</div></div>` : ''}
            ${destination ? `<div class="track-info-item"><div class="label">Destination</div><div class="value">${escHtml(destination)}</div></div>` : ''}
            ${eta ? `<div class="track-info-item"><div class="label">Estimated Delivery</div><div class="value">${escHtml(eta)}</div></div>` : ''}
            ${weight ? `<div class="track-info-item"><div class="label">Weight</div><div class="value">${escHtml(weight)}</div></div>` : ''}
            ${sender ? `<div class="track-info-item"><div class="label">Sender</div><div class="value">${escHtml(sender)}</div></div>` : ''}
            ${receiver ? `<div class="track-info-item"><div class="label">Receiver</div><div class="value">${escHtml(receiver)}</div></div>` : ''}
            ${refNo ? `<div class="track-info-item"><div class="label">Reference</div><div class="value">${escHtml(refNo)}</div></div>` : ''}
        </div>
    `;

    trackResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Auto-track on load
trackOrder();
</script>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
