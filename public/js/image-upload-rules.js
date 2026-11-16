(function () {
    'use strict';
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];

    document.addEventListener('change', function (event) {
        const input = event.target;
        if (!(input instanceof HTMLInputElement) || input.type !== 'file') return;
        if (!input.accept || !input.accept.toLowerCase().includes('image')) return;

        const files = Array.from(input.files || []);
        const gif = files.find(file => file.type.toLowerCase() === 'image/gif');
        const acceptsNonImage = input.accept.split(',').some(type => {
            type = type.trim().toLowerCase();
            return type && !type.startsWith('image/') && !['.jpg', '.jpeg', '.png', '.webp'].includes(type);
        });
        const invalid = gif || (!acceptsNonImage && files.find(file => !allowed.includes(file.type.toLowerCase())));
        if (!invalid) return;

        input.value = '';
        event.stopImmediatePropagation();
        appAlert(gif
            ? 'ไม่รองรับไฟล์ GIF กรุณาใช้ JPG, PNG หรือ WebP'
            : 'รองรับเฉพาะรูป JPG, PNG และ WebP');
    }, true);
})();

