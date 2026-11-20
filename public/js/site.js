/* Situs publik — interaksi ringan (tanpa build tool) */
(function () {
    'use strict';

    /* Keep the current section visible in horizontally scrollable navigation. */
    const sectionNav = document.querySelector('.section-navigation-links');
    if (sectionNav) {
        const revealActiveSection = function () {
            const active = sectionNav.querySelector('[aria-current="page"]');
            if (!active || sectionNav.scrollWidth <= sectionNav.clientWidth) return;
            sectionNav.scrollLeft += active.getBoundingClientRect().left - sectionNav.getBoundingClientRect().left - 16;
        };
        revealActiveSection();
        if (document.fonts) document.fonts.ready.then(revealActiveSection);
    }

    /* ---------- Lightbox: elemen <a data-lightbox="grup" data-caption="..."> ---------- */
    const lbEl = document.getElementById('lightbox');
    if (lbEl) {
        const modal = new bootstrap.Modal(lbEl);
        const img = lbEl.querySelector('.lightbox-img');
        const cap = lbEl.querySelector('.lightbox-caption');
        const counter = lbEl.querySelector('.lightbox-counter');
        const dl = lbEl.querySelector('.lightbox-download');
        const prev = lbEl.querySelector('.prev');
        const next = lbEl.querySelector('.next');
        let items = [];
        let index = 0;

        const show = function (i) {
            index = (i + items.length) % items.length;
            const a = items[index];
            img.src = a.getAttribute('href');
            img.alt = a.dataset.caption || '';
            cap.textContent = a.dataset.caption || '';
            dl.href = a.getAttribute('href');
            counter.textContent = items.length > 1 ? (index + 1) + ' / ' + items.length : '';
            prev.style.display = next.style.display = items.length > 1 ? '' : 'none';
        };

        document.addEventListener('click', function (e) {
            const a = e.target.closest('a[data-lightbox]');
            if (!a) return;
            e.preventDefault();
            items = Array.from(document.querySelectorAll('a[data-lightbox="' + a.dataset.lightbox + '"]'));
            show(items.indexOf(a));
            modal.show();
        });
        prev.addEventListener('click', function () { show(index - 1); });
        next.addEventListener('click', function () { show(index + 1); });
        lbEl.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') show(index - 1);
            if (e.key === 'ArrowRight') show(index + 1);
        });
        let startX = null;
        lbEl.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
        lbEl.addEventListener('touchend', function (e) {
            if (startX === null) return;
            const dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 50) show(index + (dx < 0 ? 1 : -1));
            startX = null;
        });
    }

    /* ---------- Fokus otomatis input pencarian ---------- */
    const searchModal = document.getElementById('searchModal');
    if (searchModal) {
        searchModal.addEventListener('shown.bs.modal', function () {
            searchModal.querySelector('input[name=q]').focus();
        });
    }

    /* ---------- Salin tautan ---------- */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-copy]');
        if (!btn) return;
        const text = btn.dataset.copy;
        const done = function () {
            const icon = btn.querySelector('i');
            const old = icon.className;
            icon.className = 'bi bi-check2';
            btn.setAttribute('aria-label', 'Tautan disalin');
            setTimeout(function () { icon.className = old; btn.setAttribute('aria-label', 'Salin tautan'); }, 1800);
        };
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(done);
        } else {
            const t = document.createElement('textarea');
            t.value = text; document.body.appendChild(t); t.select();
            document.execCommand('copy'); document.body.removeChild(t); done();
        }
    });

    /* ---------- Video YouTube: muat iframe saat diklik (hemat kuota) ---------- */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.video-thumb[data-embed]');
        if (!btn) return;
        const iframe = document.createElement('iframe');
        iframe.src = btn.dataset.embed + '?autoplay=1&rel=0';
        iframe.title = btn.dataset.title || 'Video';
        iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;
        btn.innerHTML = '';
        btn.appendChild(iframe);
    });

    /* ---------- Filter instan direktori Polsek ---------- */
    const polsekSearch = document.getElementById('polsekSearch');
    if (polsekSearch) {
        const cards = document.querySelectorAll('[data-polsek]');
        const empty = document.getElementById('polsekEmpty');
        polsekSearch.addEventListener('input', function () {
            const q = polsekSearch.value.trim().toLowerCase();
            let shown = 0;
            cards.forEach(function (c) {
                const match = c.dataset.polsek.includes(q);
                c.classList.toggle('d-none', !match);
                if (match) shown++;
            });
            if (empty) empty.classList.toggle('d-none', shown > 0);
        });
    }

    /* ---------- Sorot menu daftar isi saat menggulir (profil) ---------- */
    const toc = document.querySelector('.toc');
    if (toc && 'IntersectionObserver' in window) {
        const links = toc.querySelectorAll('a');
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    links.forEach(function (l) { l.classList.toggle('active', l.getAttribute('href') === '#' + entry.target.id); });
                }
            });
        }, { rootMargin: '-40% 0px -55% 0px' });
        document.querySelectorAll('.profile-section').forEach(function (s) { observer.observe(s); });
    }
})();
