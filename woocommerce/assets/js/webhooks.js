/*!
 * Webhook generation + validation panel (T6.10).
 *
 * Wires three actions on .woo-webhook-panel:
 *   - generate: POST topics, then re-pull status
 *   - validate: re-pull status and rerender
 *   - delete:   DELETE one remote webhook then re-pull status
 *
 * Reads endpoint URLs from data-* attributes on the panel root so
 * the same JS handles both the embedded (wizard step 4) and the
 * standalone page contexts without URL drift.
 */
(function () {
    'use strict';

    var panels = document.querySelectorAll('.woo-webhook-panel');
    if (!panels.length) return;

    panels.forEach(initPanel);

    function initPanel(root) {
        var statusUrl   = root.getAttribute('data-status-url');
        var generateUrl = root.getAttribute('data-generate-url');
        var deleteUrl   = root.getAttribute('data-delete-url');
        var feedback    = root.querySelector('.woo-webhook-panel__feedback');
        var rowsBody    = root.querySelector('[data-rows]');

        function setFeedback(msg, kind) {
            if (!feedback) return;
            feedback.textContent = msg;
            feedback.className = 'woo-webhook-panel__feedback' + (kind ? ' is-' + kind : '');
        }

        function fetchStatus() {
            setFeedback('…', 'pending');
            window.WooFetch.get(statusUrl)
                .then(function (r) { return r.json(); })
                .then(function (json) {
                    if (!json || !json.success) {
                        setFeedback(json && json.error || 'error', 'error');
                        return;
                    }
                    renderRows(json.rows || []);
                    setFeedback('', '');
                })
                .catch(function () { setFeedback('error', 'error'); });
        }

        function renderRows(rows) {
            if (!rowsBody) return;
            if (!rows.length) {
                return; // leave the data-empty placeholder visible
            }
            var html = rows.map(function (r) {
                var sig = (r.sig_ok || 0) + ' / ' + (r.sig_fail || 0);
                var statusVariant = r.status === 'active' ? 'completed' : 'on-hold';
                return '<tr>' +
                    '<td><code>' + escapeHtml(r.topic) + '</code></td>' +
                    '<td><span class="woo-status-pill woo-status-pill--' + statusVariant + '">' + escapeHtml(r.status || '—') + '</span></td>' +
                    '<td>' + (r.deliveries || 0) + '</td>' +
                    '<td>' + escapeHtml(r.last_received || '—') + '</td>' +
                    '<td>' + sig + '</td>' +
                    '<td><button type="button" class="btn btn-link btn-xs text-danger" data-delete="' + r.remote_id + '">' +
                    '<i class="fa fa-trash"></i></button></td>' +
                    '</tr>';
            }).join('');
            rowsBody.innerHTML = html;
        }

        function escapeHtml(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        root.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-action]');
            if (btn) {
                var action = btn.getAttribute('data-action');
                if (action === 'validate') {
                    fetchStatus();
                    return;
                }
                if (action === 'generate') {
                    var topics = Array.prototype.slice
                        .call(root.querySelectorAll('[data-topic]'))
                        .filter(function (cb) { return cb.checked; })
                        .map(function (cb) { return cb.getAttribute('data-topic'); });
                    if (!topics.length) {
                        setFeedback(btn.dataset.empty || 'select at least one', 'error');
                        return;
                    }
                    btn.disabled = true;
                    setFeedback('…', 'pending');
                    var body = new URLSearchParams();
                    topics.forEach(function (t) { body.append('topics[]', t); });
                    window.WooFetch.post(generateUrl, body)
                        .then(function (r) { return r.json(); })
                        .then(function (json) {
                            btn.disabled = false;
                            if (!json || !json.success) {
                                setFeedback(json && json.error || 'error', 'error');
                                return;
                            }
                            setFeedback('✓', 'success');
                            fetchStatus();
                        })
                        .catch(function () { btn.disabled = false; setFeedback('error', 'error'); });
                    return;
                }
            }

            var del = e.target.closest('[data-delete]');
            if (del) {
                var remoteId = parseInt(del.getAttribute('data-delete'), 10);
                if (!remoteId) return;
                del.disabled = true;
                window.WooFetch.post(deleteUrl, { remote_id: String(remoteId) })
                    .then(function (r) { return r.json(); })
                    .then(function (json) {
                        if (!json || !json.success) {
                            setFeedback(json && json.error || 'error', 'error');
                        }
                        fetchStatus();
                    })
                    .catch(function () { setFeedback('error', 'error'); });
            }
        });

        fetchStatus();
    }
})();
