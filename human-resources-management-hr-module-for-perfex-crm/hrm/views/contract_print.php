<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo get_locale_key(); ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body { background:#f4f4f4; font-family: Arial, sans-serif; color:#222; }
        .page {
            background:#fff;
            max-width: 900px;
            margin: 30px auto;
            padding: 60px 70px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            min-height: 1000px;
        }
        .page h1 { margin-top:0; font-size: 24px; }
        .page .meta { color:#777; font-size: 12px; margin-bottom: 30px; }
        .page .body { font-size: 14px; line-height: 1.6; }
        .page .body img { max-width: 100%; height: auto; }
        .page .body table { width:100%; border-collapse: collapse; }
        .page .body table td, .page .body table th { border:1px solid #ddd; padding:6px; }
        .toolbar { max-width:900px; margin: 12px auto 0; text-align:right; }
        .signed-block { margin-top: 50px; border-top:1px solid #ccc; padding-top: 20px; }
        .signature-box { border:1px solid #ccc; background:#fff; }
        .signature-box canvas { display:block; width: 100%; height: 180px; cursor: crosshair; }
        @media print {
            body { background:#fff; }
            .toolbar, .no-print { display:none !important; }
            .page { box-shadow:none; margin: 0; padding: 0; max-width: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <button type="button" class="btn btn-info" onclick="window.print();">
            <i class="fa fa-print"></i> <?php echo _l('print_or_save_pdf'); ?>
        </button>
        <button type="button" class="btn btn-default" onclick="window.close();">
            <?php echo _l('close'); ?>
        </button>
    </div>

    <div class="page">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <div class="meta">
            <?php echo _l('contract_code'); ?>: <strong><?php echo htmlspecialchars($contract['contract_code'] ?? ''); ?></strong>
            &middot;
            <?php echo _l('start_valid'); ?>: <?php echo !empty($contract['start_valid']) ? _d($contract['start_valid']) : '—'; ?>
            &middot;
            <?php echo _l('end_valid'); ?>: <?php echo !empty($contract['end_valid']) ? _d($contract['end_valid']) : '—'; ?>
        </div>

        <div class="body">
            <?php
                if ($body !== '') {
                    echo $body;
                } else {
                    echo '<p class="text-muted"><em>' . _l('no_contract_body_yet') . '</em></p>';
                }
            ?>
        </div>

        <div class="signed-block">
            <?php if (!empty($contract['signed_at'])): ?>
                <p>
                    <strong><?php echo _l('contract_signed_label'); ?>:</strong>
                    <?php echo _dt($contract['signed_at']); ?>
                    <?php if (!empty($contract['signed_ip'])) {
                        echo ' <small class="text-muted">(IP: ' . htmlspecialchars($contract['signed_ip']) . ')</small>';
                    } ?>
                </p>
                <?php if (!empty($contract['signature_image'])): ?>
                    <img src="<?php echo htmlspecialchars($contract['signature_image']); ?>"
                         alt="signature"
                         style="max-height:140px;background:#fff;padding:6px;border:1px solid #eee;">
                <?php endif; ?>
            <?php elseif (!empty($can_sign)): ?>
                <div class="no-print">
                    <p><strong><?php echo _l('sign_below'); ?></strong></p>
                    <div class="signature-box">
                        <canvas id="hrm-signature-pad"></canvas>
                    </div>
                    <p class="text-muted" style="margin-top:8px;">
                        <small><?php echo _l('signature_legal_notice'); ?></small>
                    </p>
                    <button type="button" id="hrm-sig-clear" class="btn btn-default btn-sm">
                        <?php echo _l('clear'); ?>
                    </button>
                    <button type="button" id="hrm-sig-save" class="btn btn-info" data-contract-id="<?php echo (int) ($contract['id_contract'] ?? 0); ?>">
                        <i class="fa fa-check"></i> <?php echo _l('confirm_and_sign'); ?>
                    </button>
                    <span id="hrm-sig-status" style="margin-left:10px;"></span>
                </div>
            <?php else: ?>
                <p class="text-muted"><em><?php echo _l('contract_not_yet_signed'); ?></em></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Lightweight, dependency-free signature pad. Captures the user's
        // pointer/touch input on a <canvas> and exposes toDataURL() to POST
        // back as a base64 PNG. Auto-resizes to its parent on init.
        (function () {
            var canvas = document.getElementById('hrm-signature-pad');
            if (!canvas) return;
            var ctx = canvas.getContext('2d');
            var drawing = false;
            var hasStrokes = false;
            var lastX = 0, lastY = 0;

            function resize() {
                var ratio = window.devicePixelRatio || 1;
                var w = canvas.offsetWidth;
                var h = canvas.offsetHeight;
                canvas.width = w * ratio;
                canvas.height = h * ratio;
                ctx.scale(ratio, ratio);
                ctx.lineJoin = 'round';
                ctx.lineCap = 'round';
                ctx.lineWidth = 2.2;
                ctx.strokeStyle = '#222';
            }
            resize();
            window.addEventListener('resize', resize);

            function pos(e) {
                var r = canvas.getBoundingClientRect();
                var p = (e.touches && e.touches[0]) ? e.touches[0] : e;
                return { x: p.clientX - r.left, y: p.clientY - r.top };
            }
            function start(e) { e.preventDefault(); drawing = true; var p = pos(e); lastX = p.x; lastY = p.y; }
            function move(e) {
                if (!drawing) return;
                e.preventDefault();
                var p = pos(e);
                ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(p.x, p.y); ctx.stroke();
                lastX = p.x; lastY = p.y; hasStrokes = true;
            }
            function end() { drawing = false; }

            canvas.addEventListener('mousedown', start);
            canvas.addEventListener('mousemove', move);
            canvas.addEventListener('mouseup', end);
            canvas.addEventListener('mouseleave', end);
            canvas.addEventListener('touchstart', start, {passive:false});
            canvas.addEventListener('touchmove', move, {passive:false});
            canvas.addEventListener('touchend', end);

            document.getElementById('hrm-sig-clear').addEventListener('click', function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasStrokes = false;
            });

            document.getElementById('hrm-sig-save').addEventListener('click', function () {
                var btn = this;
                var status = document.getElementById('hrm-sig-status');
                if (!hasStrokes) {
                    status.innerHTML = '<span class="text-danger"><?php echo _l('please_draw_signature'); ?></span>';
                    return;
                }
                btn.disabled = true;
                status.innerHTML = '<?php echo _l('saving'); ?>...';

                var dataUrl = canvas.toDataURL('image/png');
                var fd = new FormData();
                fd.append('signature_image', dataUrl);
                var csrfName  = '<?php echo $this->security->get_csrf_token_name(); ?>';
                var csrfHash  = '<?php echo $this->security->get_csrf_hash(); ?>';
                fd.append(csrfName, csrfHash);

                fetch('<?php echo admin_url('hrm/contract_sign/' . (int) ($contract['id_contract'] ?? 0)); ?>', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd
                })
                .then(function (r) { return r.json(); })
                .then(function (j) {
                    if (j && j.success) {
                        status.innerHTML = '<span class="text-success">' + j.message + '</span>';
                        setTimeout(function () { window.location.reload(); }, 800);
                    } else {
                        btn.disabled = false;
                        status.innerHTML = '<span class="text-danger">' + ((j && j.message) || 'Error') + '</span>';
                    }
                })
                .catch(function () {
                    btn.disabled = false;
                    status.innerHTML = '<span class="text-danger">Network error</span>';
                });
            });
        })();

        <?php if (!empty($auto_print)): ?>
        window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 300); });
        <?php endif; ?>
    </script>
</body>
</html>
