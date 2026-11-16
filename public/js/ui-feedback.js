(function () {
    'use strict';

    const confirmedElements = new WeakSet();

    function fallbackDialog(title, message, confirmMode, settings, resolve) {
        const overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;inset:0;z-index:10000;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(15,23,42,.65);backdrop-filter:blur(4px)';
        const box = document.createElement('div');
        box.style.cssText = 'width:100%;max-width:448px;padding:32px;border-radius:24px;background:#fff;box-shadow:0 25px 50px rgba(0,0,0,.25);text-align:center;font-family:Sarabun,sans-serif';
        const heading = document.createElement('h3');
        heading.textContent = title;
        heading.style.cssText = 'margin:0 0 10px;font-size:20px;font-weight:900;color:#1e293b';
        const detail = document.createElement('p');
        detail.textContent = message;
        detail.style.cssText = 'margin:0 0 28px;white-space:pre-line;font-size:14px;line-height:1.6;color:#64748b';
        const actions = document.createElement('div');
        actions.style.cssText = 'display:flex;gap:12px';
        const close = function (value) { overlay.remove(); resolve(value); };

        if (confirmMode) {
            const cancel = document.createElement('button');
            cancel.type = 'button';
            cancel.textContent = 'ยกเลิก';
            cancel.style.cssText = 'flex:1;padding:13px;border:0;border-radius:14px;background:#e2e8f0;color:#475569;font-weight:800;cursor:pointer';
            cancel.addEventListener('click', function () { close(false); });
            actions.appendChild(cancel);
        }

        const ok = document.createElement('button');
        ok.type = 'button';
        ok.textContent = confirmMode ? settings.confirmText : 'ตกลง';
        ok.style.cssText = 'flex:1;padding:13px;border:0;border-radius:14px;background:' + (settings.danger ? '#dc2626' : 'var(--ui-primary,#059669)') + ';color:#fff;font-weight:800;cursor:pointer';
        ok.addEventListener('click', function () { close(true); });
        actions.appendChild(ok);
        box.append(heading, detail, actions);
        overlay.appendChild(box);
        document.body.appendChild(overlay);
    }

    window.appAlert = function (message, options) {
        const settings = Object.assign({ title: 'แจ้งเตือน', type: 'warning' }, options || {});

        return new Promise(function (resolve) {
            if (typeof window.showCustomAlert === 'function') {
                window.showCustomAlert(settings.title, String(message || ''), settings.type, resolve);
                return;
            }

            if (window.Swal && typeof window.Swal.fire === 'function') {
                window.Swal.fire({ title: settings.title, text: String(message || ''), icon: settings.type });
                resolve();
                return;
            }

            fallbackDialog(settings.title, String(message || ''), false, settings, resolve);
        });
    };

    window.appConfirm = function (message, options) {
        const settings = Object.assign({
            title: 'ยืนยันการดำเนินการ',
            confirmText: 'ยืนยัน',
            danger: true
        }, options || {});

        return new Promise(function (resolve) {
            if (typeof window.showCustomConfirm === 'function') {
                let settled = false;
                window.showCustomConfirm(
                    settings.title,
                    String(message || ''),
                    function () { settled = true; resolve(true); },
                    settings.confirmText,
                    settings.danger
                );

                const modal = document.getElementById('custom-confirm-modal');
                if (modal) {
                    const cancel = modal.querySelector('#confirm-cancel');
                    if (cancel) cancel.addEventListener('click', function () {
                        if (!settled) resolve(false);
                    }, { once: true });
                    modal.addEventListener('click', function (event) {
                        if (event.target === modal && !settled) resolve(false);
                    }, { once: true });
                }
                return;
            }

            if (window.Swal && typeof window.Swal.fire === 'function') {
                window.Swal.fire({
                    title: settings.title,
                    text: String(message || ''),
                    icon: settings.danger ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonText: settings.confirmText,
                    cancelButtonText: 'ยกเลิก'
                }).then(function (result) { resolve(Boolean(result.isConfirmed)); });
                return;
            }

            fallbackDialog(settings.title, String(message || ''), true, settings, resolve);
        });
    };

    window.appConfirmSubmit = function (element, message, options) {
        if (confirmedElements.has(element)) {
            confirmedElements.delete(element);
            return true;
        }

        window.appConfirm(message, options).then(function (confirmed) {
            if (!confirmed) return;
            if (element instanceof HTMLFormElement) {
                HTMLFormElement.prototype.submit.call(element);
                return;
            }
            confirmedElements.add(element);
            element.click();
        });
        return false;
    };

    window.appConfirmForm = function (formId, message, options) {
        window.appConfirm(message, options).then(function (confirmed) {
            const form = document.getElementById(formId);
            if (confirmed && form) form.submit();
        });
        return false;
    };

    // Compatibility guard: legacy alert calls are rendered with the website UI.
    window.alert = function (message) {
        window.appAlert(message);
    };
})();

