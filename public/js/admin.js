/* Panel Admin — interaksi ringan tanpa build tool */
(function () {
    'use strict';

    // Konfirmasi sebelum submit form ber-atribut data-confirm (hapus, kembalikan, dll.)
    const modalEl = document.getElementById('confirmModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        let pendingForm = null;
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form.matches('form[data-confirm]') || form.dataset.confirmed === '1') return;
            e.preventDefault();
            pendingForm = form;
            document.getElementById('confirmMessage').textContent = form.dataset.confirm;
            modal.show();
        });
        document.getElementById('confirmOk').addEventListener('click', function () {
            if (!pendingForm) return;
            pendingForm.dataset.confirmed = '1';
            modal.hide();
            if (pendingForm.requestSubmit) { pendingForm.requestSubmit(); } else { pendingForm.submit(); }
        });
    }

    // Pratinjau gambar sebelum upload
    document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            const img = document.querySelector('[data-preview-for="' + input.id + '"]');
            if (img && input.files && input.files[0]) {
                img.src = URL.createObjectURL(input.files[0]);
            }
        });
    });

    // Editor teks Quill (tanpa API key). Isi disalin ke textarea tersembunyi saat submit.
    if (window.Quill) {
        document.querySelectorAll('.quill-editor').forEach(function (el) {
            const textarea = document.getElementById(el.dataset.input);
            const quill = new Quill(el, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [2, 3, 4, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['blockquote', 'link'],
                        [{ align: [] }],
                        ['clean']
                    ]
                }
            });
            if (textarea.value) {
                quill.clipboard.dangerouslyPasteHTML(textarea.value);
            }
            const form = el.closest('form');
            const sync = function () {
                const html = quill.root.innerHTML;
                textarea.value = quill.getText().trim() === '' ? '' : html;
            };
            quill.on('text-change', sync);
            if (form) {
                form.addEventListener('submit', function (e) {
                    sync();
                    if (textarea.dataset.required && !textarea.value) {
                        e.preventDefault();
                        el.closest('.editor-wrap').classList.add('border', 'border-danger');
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, true);
            }
        });
    }

    // Tampilkan/sembunyikan field unit sesuai role pada form pengguna
    const roleSelect = document.getElementById('f_role');
    const unitWrap = document.getElementById('unit-wrap');
    if (roleSelect && unitWrap) {
        const toggle = function () { unitWrap.classList.toggle('d-none', roleSelect.value !== 'admin-unit'); };
        roleSelect.addEventListener('change', toggle);
        toggle();
    }
})();
