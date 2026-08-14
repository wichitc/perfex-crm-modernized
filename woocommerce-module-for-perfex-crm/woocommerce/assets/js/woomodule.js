/*!
 * Module-level UX primitives (T6.15).
 *
 * Two things:
 *   - WooToast: top-right stacked toast notifications, 4-second
 *     auto-dismiss, severity-colored, ARIA live region.
 *   - WooValidate: inline validation pattern — adds .is-invalid,
 *     ARIA invalid + describedby, and renders inline error copy.
 *
 * Both attach to `window.WooToast` / `window.WooValidate` so other
 * module scripts (stores.js, products.js, field_mappings.js) can
 * reuse them without bundling.
 */
(function () {
    'use strict';

    /* ---------------------------------- Toast --------------------------- */
    var hostId = 'wooToastHost';
    function host() {
        var h = document.getElementById(hostId);
        if (h) return h;
        h = document.createElement('div');
        h.id = hostId;
        h.className = 'woo-toast-host';
        h.setAttribute('role', 'region');
        h.setAttribute('aria-live', 'polite');
        h.setAttribute('aria-label', 'Notifications');
        document.body.appendChild(h);
        return h;
    }

    /**
     * Show a toast.
     * @param {string} severity   info | success | warning | danger
     * @param {string} message    plain text body
     * @param {{ duration?: number }} [opts]
     */
    function show(severity, message, opts) {
        opts = opts || {};
        var duration = typeof opts.duration === 'number' ? opts.duration : 4000;

        var toast = document.createElement('div');
        toast.className = 'woo-toast woo-toast--' + severity;
        toast.setAttribute('role', severity === 'danger' ? 'alert' : 'status');

        var msg = document.createElement('span');
        msg.className = 'woo-toast__msg';
        msg.textContent = message;
        toast.appendChild(msg);

        var close = document.createElement('button');
        close.type = 'button';
        close.className = 'woo-toast__close';
        close.setAttribute('aria-label', 'Close');
        close.textContent = '×';
        close.addEventListener('click', function () { remove(toast); });
        toast.appendChild(close);

        host().appendChild(toast);
        // Stagger fade-in via class so CSS transition can fire on next frame.
        requestAnimationFrame(function () { toast.classList.add('is-visible'); });

        if (duration > 0) {
            setTimeout(function () { remove(toast); }, duration);
        }
        return toast;
    }

    function remove(toast) {
        if (!toast || !toast.parentNode) return;
        toast.classList.remove('is-visible');
        setTimeout(function () {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 250);
    }

    window.WooToast = {
        info:    function (m, o) { return show('info',    m, o); },
        success: function (m, o) { return show('success', m, o); },
        warning: function (m, o) { return show('warning', m, o); },
        danger:  function (m, o) { return show('danger',  m, o); },
    };

    /* ------------------------------ Validate ---------------------------- */

    /**
     * Mark a field as invalid (or clear it). Idempotent.
     * @param {HTMLElement} field
     * @param {string|null} message  null clears the error
     */
    function setFieldError(field, message) {
        if (!field) return;
        var existing = field.parentNode &&
            field.parentNode.querySelector('[data-woo-error-for="' + field.name + '"]');

        if (message == null || message === '') {
            field.classList.remove('is-invalid');
            field.removeAttribute('aria-invalid');
            field.removeAttribute('aria-describedby');
            if (existing) existing.parentNode.removeChild(existing);
            return;
        }

        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');

        if (existing) {
            existing.textContent = message;
        } else {
            var hint = document.createElement('p');
            hint.className = 'help-block woo-field-error';
            hint.setAttribute('data-woo-error-for', field.name || '');
            hint.id = 'woo-error-' + (field.id || ('f' + Math.random().toString(36).slice(2, 8)));
            hint.textContent = message;
            field.parentNode.appendChild(hint);
            field.setAttribute('aria-describedby', hint.id);
        }
    }

    window.WooValidate = { setFieldError: setFieldError };

    /* ------------------------------- Fetch ------------------------------ */
    /*
     * Thin fetch wrapper that auto-injects Perfex's CSRF token + standard
     * headers. Perfex enables `csrf_protection` and exposes the current
     * token via the global `csrfData = { token_name, hash, formatted }`
     * (defined by `csrf_jquery_token()` in general_helper.php). jQuery's
     * `$.ajaxSetup({ data: csrfData.formatted })` handles jQuery calls
     * automatically — but we use native fetch from module JS, so without
     * this wrapper every POST got rejected with 419 Page Expired.
     *
     * `csrf_regenerate` is `false` on the Perfex install (config.php:489)
     * so the token is stable for the whole session — no need to rotate
     * it after each response.
     */
    function csrfPair() {
        if (typeof window.csrfData === 'undefined' || !window.csrfData) return null;
        var name = window.csrfData.token_name;
        var hash = window.csrfData.hash;
        if (!name || !hash) return null;
        return { name: name, hash: hash };
    }

    function attachCsrf(body) {
        var pair = csrfPair();
        if (!pair) return body;

        if (body instanceof FormData) {
            // Don't re-append if the form already has it (form_open() does).
            if (!body.has(pair.name)) body.append(pair.name, pair.hash);
            return body;
        }

        if (body instanceof URLSearchParams) {
            if (!body.has(pair.name)) body.set(pair.name, pair.hash);
            return body;
        }

        // Plain object → URLSearchParams.
        var sp = new URLSearchParams();
        if (body && typeof body === 'object') {
            Object.keys(body).forEach(function (k) {
                var v = body[k];
                if (Array.isArray(v)) v.forEach(function (vv) { sp.append(k + '[]', vv); });
                else if (v !== null && v !== undefined) sp.set(k, String(v));
            });
        }
        sp.set(pair.name, pair.hash);
        return sp;
    }

    function handleResponse(r) {
        if (r.status === 419 && window.WooToast) {
            window.WooToast.warning('Page expired — refresh and try again.');
        }
        return r;
    }

    window.WooFetch = {
        post: function (url, body, opts) {
            opts = opts || {};
            return fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: Object.assign(
                    { 'X-Requested-With': 'XMLHttpRequest' },
                    opts.headers || {}
                ),
                body: attachCsrf(body)
            }).then(handleResponse);
        },
        get: function (url, opts) {
            opts = opts || {};
            return fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: Object.assign(
                    { 'X-Requested-With': 'XMLHttpRequest' },
                    opts.headers || {}
                )
            }).then(handleResponse);
        }
    };
})();
