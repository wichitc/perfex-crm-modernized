/*!
 * Store wizard (T6.3) — step navigation, AJAX test-connection,
 * AJAX form submit. Plain ES2017, no framework dependency beyond
 * Bootstrap selectpicker (used inside step 3).
 *
 * Mounted by views/modals/_store_form.php; harmless on every other
 * page since the entry-point looks for #wooStoreForm and bails if
 * absent.
 */
(function () {
    'use strict';

    var form = document.getElementById('wooStoreForm');
    if (!form) {
        return;
    }

    var steps = Array.prototype.slice.call(form.querySelectorAll('.woo-wizard__step'));
    var panels = Array.prototype.slice.call(form.querySelectorAll('.woo-wizard__panel'));

    function setStep(n) {
        steps.forEach(function (s) {
            var isCurrent = parseInt(s.getAttribute('data-goto'), 10) === n;
            s.classList.toggle('is-current', isCurrent);
            s.setAttribute('aria-current', isCurrent ? 'step' : 'false');
        });
        panels.forEach(function (p) {
            var isCurrent = parseInt(p.getAttribute('data-step'), 10) === n;
            p.classList.toggle('is-current', isCurrent);
            if (isCurrent) {
                p.removeAttribute('hidden');
                var firstField = p.querySelector('input, select, textarea, button');
                if (firstField) firstField.focus();
            } else {
                p.setAttribute('hidden', 'hidden');
            }
        });
    }

    /* The "next" button only advances when the current panel's
     * required fields validate. Native HTML5 validation gives
     * users the inline error messages for free. */
    form.addEventListener('click', function (e) {
        var nextBtn = e.target.closest('[data-next]');
        if (nextBtn) {
            var current = nextBtn.closest('.woo-wizard__panel');
            var requiredFields = current.querySelectorAll('[required]');
            var ok = true;
            requiredFields.forEach(function (f) {
                if (!f.checkValidity()) {
                    f.reportValidity();
                    ok = false;
                }
            });
            if (ok) {
                setStep(parseInt(nextBtn.getAttribute('data-next'), 10));
            }
            return;
        }
        var prevBtn = e.target.closest('[data-prev]');
        if (prevBtn) {
            setStep(parseInt(prevBtn.getAttribute('data-prev'), 10));
            return;
        }
        var jumpBtn = e.target.closest('.woo-wizard__step');
        if (jumpBtn) {
            setStep(parseInt(jumpBtn.getAttribute('data-goto'), 10));
        }
    });

    /* Test connection — POSTs the in-progress credentials to
     * Stores::credentials_test and renders inline pass/fail copy. */
    var testBtn = document.getElementById('wooTestConnection');
    var testOut = document.getElementById('wooTestResult');
    if (testBtn && testOut) {
        testBtn.addEventListener('click', function () {
            var url = (form.querySelector('[name="url"]') || {}).value;
            var ck = (form.querySelector('[name="consumer_key"]') || {}).value;
            var cs = (form.querySelector('[name="consumer_secret"]') || {}).value;
            var verify = !!(form.querySelector('[name="verify_ssl"]:checked'));

            if (!url || !ck || !cs) {
                testOut.textContent = testOut.dataset.missing || 'Fill all credential fields first.';
                testOut.className = 'woo-test-result is-error';
                return;
            }

            testBtn.disabled = true;
            testOut.textContent = '…';
            testOut.className = 'woo-test-result is-pending';

            window.WooFetch.post(window.admin_url + 'woocommerce/stores/credentials_test', {
                url: url,
                consumer_key: ck,
                consumer_secret: cs,
                verify_ssl: verify ? '1' : ''
            })
                .then(function (r) { return r.json(); })
                .then(function (json) {
                    if (json && json.success) {
                        testOut.textContent = '✓ ' + (testBtn.dataset.successLabel || 'Connection OK');
                        testOut.className = 'woo-test-result is-success';
                    } else {
                        testOut.textContent = '✗ ' + (testBtn.dataset.failLabel || 'Connection failed');
                        testOut.className = 'woo-test-result is-error';
                    }
                })
                .catch(function () {
                    testOut.textContent = '✗ ' + (testBtn.dataset.failLabel || 'Connection failed');
                    testOut.className = 'woo-test-result is-error';
                })
                .finally(function () {
                    testBtn.disabled = false;
                });
        });
    }

    /* Submit. AJAX so we can stay on the page on validation errors
     * and pop a toast on success. */
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var submit = document.getElementById('wooStoreSubmit');
        if (submit) submit.disabled = true;

        window.WooFetch.post(form.action, new FormData(form))
            .then(function (r) { return r.json().catch(function () { return null; }); })
            .then(function (json) {
                if (json && json.success && json.redirect) {
                    window.location.href = json.redirect;
                    return;
                }
                if (submit) submit.disabled = false;
                if (window.alert_float) {
                    window.alert_float('danger', (json && json.error) || 'save_failed');
                }
            })
            .catch(function () {
                if (submit) submit.disabled = false;
            });
    });

    setStep(1);
})();
