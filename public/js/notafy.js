(function () {
    'use strict';

    // ── Helpers ────────────────────────────────────────────────────────── //

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
    const MAX_SIZE = 10 * 1024 * 1024; // 10 MB

    function validateFile(file) {
        if (!ALLOWED_TYPES.includes(file.type)) {
            return 'Only JPG, PNG, and PDF files are accepted.';
        }
        if (file.size > MAX_SIZE) {
            return 'File must be smaller than 10 MB.';
        }
        return null;
    }

    // ── Upload zone ────────────────────────────────────────────────────── //

    function initUploadZone() {
        const uploadZone  = document.getElementById('upload-zone');
        const fileInput   = document.getElementById('receipt-input');
        const uploadIdle  = document.getElementById('upload-idle');
        const uploadPrev  = document.getElementById('upload-preview');
        const previewName = document.getElementById('preview-name');
        const previewSize = document.getElementById('preview-size');
        const removeBtn   = document.getElementById('preview-remove');
        const browseBtn   = document.getElementById('upload-browse');
        const submitBtn   = document.getElementById('extract-submit');
        const form        = document.getElementById('extract-form');
        const submitLabel = document.getElementById('submit-label');

        if (!uploadZone || !fileInput) return;

        function showPreview(file) {
            const err = validateFile(file);
            if (err) {
                alert(err);
                return;
            }
            previewName.textContent = file.name;
            previewSize.textContent = formatBytes(file.size);
            uploadIdle.style.display = 'none';
            uploadPrev.style.display = 'flex';
            submitBtn.disabled = false;
            uploadZone.classList.add('has-file');
        }

        function clearPreview() {
            fileInput.value = '';
            uploadIdle.style.display = 'flex';
            uploadPrev.style.display = 'none';
            submitBtn.disabled = true;
            uploadZone.classList.remove('has-file');
        }

        // Clicking the browse button opens the file picker
        if (browseBtn) {
            browseBtn.addEventListener('click', () => fileInput.click());
        }

        // Clicking anywhere on the idle zone also opens the picker
        uploadIdle.addEventListener('click', (e) => {
            if (e.target !== browseBtn) fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files[0]) showPreview(fileInput.files[0]);
        });

        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                clearPreview();
            });
        }

        // Drag and drop
        uploadZone.addEventListener('dragenter', (e) => {
            e.preventDefault();
            uploadZone.classList.add('drag-over');
        });
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('drag-over');
        });
        uploadZone.addEventListener('dragleave', (e) => {
            if (!uploadZone.contains(e.relatedTarget)) {
                uploadZone.classList.remove('drag-over');
            }
        });
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (!file) return;
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            showPreview(file);
        });

        // Form submit: loading state
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!fileInput.files[0]) {
                    e.preventDefault();
                    return;
                }
                submitBtn.disabled = true;
                submitLabel.textContent = 'Processing...';
                submitBtn.classList.add('loading');
            });
        }
    }

    // ── Copy JSON ──────────────────────────────────────────────────────── //

    function initCopyJson() {
        const copyBtn  = document.getElementById('copy-btn');
        const copyLabel = document.getElementById('copy-label');
        if (!copyBtn) return;

        copyBtn.addEventListener('click', () => {
            const json = window._resultJson || '';
            navigator.clipboard.writeText(json).then(() => {
                copyLabel.textContent = 'Copied!';
                copyBtn.classList.add('copied');
                setTimeout(() => {
                    copyLabel.textContent = 'Copy JSON';
                    copyBtn.classList.remove('copied');
                }, 2000);
            }).catch(() => {
                // Fallback for older browsers
                const ta = document.createElement('textarea');
                ta.value = json;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                copyLabel.textContent = 'Copied!';
                setTimeout(() => { copyLabel.textContent = 'Copy JSON'; }, 2000);
            });
        });
    }

    // ── Init ───────────────────────────────────────────────────────────── //

    document.addEventListener('DOMContentLoaded', () => {
        initUploadZone();
        initCopyJson();
    });

})();
