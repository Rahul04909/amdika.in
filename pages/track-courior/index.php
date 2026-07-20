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
.timeline-item .dp-detail { font-size: 12px; color: #6b7280; margin-top: 4px; padding: 6px 10px; background: #f9fafb; border-radius: 6px; display: inline-block; }

.track-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
.track-info-item { }
.track-info-item .label { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #9ca3af; font-weight: 600; margin-bottom: 4px; }
.track-info-item .value { font-size: 14px; font-weight: 500; color: #111827; }

.track-error { padding: 20px; text-align: center; color: #ef4444; background: #fef2f2; border-radius: 10px; border: 1px solid #fecaca; margin-top: 24px; }
.track-error i { font-size: 24px; margin-bottom: 8px; display: block; }
.track-error .msg { font-size: 14px; font-weight: 500; }

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
                    <span class="btn-text"><i class="fas fa-search"></i> Track</span>
                </button>
            </div>
            <div id="trackResult" class="track-result"></div>
        </div>
    </div>
</div>

<script>
const awbInput = document.getElementById('awbInput');
const trackBtn = document.getElementById('trackBtn');
const trackResult = document.getElementById('trackResult');

awbInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') trackOrder();
});

function esc(str) {
    if (!str) return '';
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

function fmtDate(ts) {
    if (!ts) return '';
    try {
        var d = new Date(Number(ts));
        return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }) +
               ' \u00b7 ' + d.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
    } catch(e) { return ts; }
}

function fmtStatus(str) {
    if (!str) return 'Pending';
    return str.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
}

function getStatusMeta(category) {
    var c = (category || '').toUpperCase();
    if (c === 'DELIVERED') return { cls: 'delivered', icon: 'fas fa-check-circle', label: 'Delivered' };
    if (c === 'OUT_FOR_DELIVERY') return { cls: 'in-transit', icon: 'fas fa-truck', label: 'Out for Delivery' };
    if (c === 'IN_TRANSIT') return { cls: 'in-transit', icon: 'fas fa-box', label: 'In Transit' };
    if (c === 'PENDING') return { cls: 'pending', icon: 'fas fa-clock', label: 'Pending' };
    return { cls: 'in-transit', icon: 'fas fa-sync-alt', label: fmtStatus(category) };
}

function trackOrder() {
    var awb = awbInput.value.trim();
    if (!awb) {
        trackResult.innerHTML = '<div class="track-error"><i class="fas fa-exclamation-circle"></i><div class="msg">Please enter a valid AWB number.</div></div>';
        return;
    }

    trackBtn.classList.add('loading');
    trackBtn.disabled = true;
    trackResult.innerHTML = '';

    fetch('https://apis-hubops.innofulfill.com/tracking/v2/' + encodeURIComponent(awb))
        .then(function(res) {
            if (!res.ok) throw new Error('Request failed');
            return res.json();
        })
        .then(function(data) {
            renderTracking(data);
        })
        .catch(function(err) {
            trackResult.innerHTML = '<div class="track-error"><i class="fas fa-exclamation-circle"></i><div class="msg">Unable to fetch tracking details. Please try again.</div><div style="font-size:12px;color:#9ca3af;margin-top:6px">' + esc(err.message) + '</div></div>';
        })
        .finally(function() {
            trackBtn.classList.remove('loading');
            trackBtn.disabled = false;
        });
}

function renderTracking(data) {
    var info = data.orderInformation || {};
    var statuses = data.statuses || [];

    var awb = info.trackingId || '';
    var sourceCity = info.sourceLocation ? info.sourceLocation.city || '' : '';
    var sourceState = info.sourceLocation ? info.sourceLocation.state || '' : '';
    var destCity = info.destinationLocation ? info.destinationLocation.city || '' : '';
    var destState = info.destinationLocation ? info.destinationLocation.state || '' : '';
    var source = sourceCity + (sourceState ? ', ' + sourceState : '');
    var destination = destCity + (destState ? ', ' + destState : '');
    var sender = info.senderDetails ? (info.senderDetails.sender_name || '') : '';
    var receiver = info.receiverDetails ? (info.receiverDetails.receiver_name || '') : '';
    var phase = info.currentShipmentPhase || '';
    var podLinks = info.pod_links || [];

    var lastStatus = statuses.length > 0 ? statuses[0] : null;
    var category = lastStatus ? lastStatus.category : '';
    var statusLabel = lastStatus ? (lastStatus.subcategory || fmtStatus(lastStatus.status)) : 'Pending';
    var lastTime = lastStatus ? fmtDate(lastStatus.statusTimestamp) : '';

    var statusMeta = getStatusMeta(category);

    var timelineHtml = '';
    var reversed = statuses.slice().reverse();
    for (var i = 0; i < reversed.length; i++) {
        var ev = reversed[i];
        var isLast = i === reversed.length - 1;
        var cls = isLast ? 'active' : 'completed';
        var loc = ev.location || '';
        var time = fmtDate(ev.statusTimestamp);
        var desc = ev.subcategory || fmtStatus(ev.status);
        var dp = ev.dpDetails || {};
        var dpInfo = '';
        if (dp.daName || dp.daMobile) {
            dpInfo = '<div class="dp-detail"><i class="fas fa-user"></i> ' + esc(dp.daName || '') + (dp.daMobile ? ' &mdash; ' + esc(dp.daMobile) : '') + '</div>';
        }
        timelineHtml += '<div class="timeline-item ' + cls + '"><div class="dot"></div><div class="time">' + time + '</div><div class="event">' + esc(desc) + '</div>' + (loc ? '<div class="location"><i class="fas fa-map-marker-alt" style="color:#9ca3af;font-size:11px;margin-right:4px"></i>' + esc(loc) + '</div>' : '') + dpInfo + '</div>';
    }

    var infoHtml = '';
    var infoItems = [];
    if (source) infoItems.push('<div class="track-info-item"><div class="label">Origin</div><div class="value">' + esc(source) + '</div></div>');
    if (destination) infoItems.push('<div class="track-info-item"><div class="label">Destination</div><div class="value">' + esc(destination) + '</div></div>');
    if (sender) infoItems.push('<div class="track-info-item"><div class="label">Sender</div><div class="value">' + esc(sender) + '</div></div>');
    if (receiver) infoItems.push('<div class="track-info-item"><div class="label">Receiver</div><div class="value">' + esc(receiver) + '</div></div>');
    if (awb) infoItems.push('<div class="track-info-item"><div class="label">AWB Number</div><div class="value">' + esc(awb) + '</div></div>');
    if (infoItems.length) infoHtml = '<div class="track-info-grid">' + infoItems.join('') + '</div>';

    var podHtml = '';
    if (podLinks.length) {
        podHtml = '<div style="margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6;text-align:center">';
        podHtml += '<a href="' + esc(podLinks[0]) + '" target="_blank" class="btn btn-sm btn-outline-success" style="padding:8px 20px;border-radius:8px;text-decoration:none;border:1px solid #86efac;color:#16a34a;font-size:13px;font-weight:600"><i class="fas fa-image"></i> View Delivery Proof</a>';
        podHtml += '</div>';
    }

    var statusHtml = '<div class="track-status-bar ' + statusMeta.cls + '"><div class="status-icon"><i class="' + statusMeta.icon + '"></i></div><div class="status-info"><div class="status-label">' + statusMeta.label + ' \u2014 ' + esc(awb) + '</div><div class="status-date">' + esc(statusLabel) + (lastTime ? ' &middot; ' + lastTime : '') + '</div></div></div>';

    var content = statusHtml;
    if (timelineHtml) {
        content += '<div class="track-timeline">' + timelineHtml + '</div>';
    } else {
        content += '<div class="track-empty"><i class="fas fa-box-open"></i><p>No tracking events available yet.</p></div>';
    }
    content += infoHtml + podHtml;
    trackResult.innerHTML = content;
    trackResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

trackOrder();
</script>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
