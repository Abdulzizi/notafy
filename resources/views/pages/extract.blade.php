@extends('layouts.dashboard')

@section('title', 'Extract Receipt')

@section('content')
<div class="extract-page">

    @if ($errors->has('extract'))
        <div class="alert alert-error">{{ $errors->first('extract') }}</div>
    @endif

    <div class="extract-columns">

        {{-- LEFT: Upload --}}
        <div class="extract-col extract-col--upload">
            <div class="extract-section-title">Upload Receipt</div>

            <form action="{{ route('extract.submit') }}" method="POST" enctype="multipart/form-data" id="extract-form">
                @csrf

                <div class="upload-zone" id="upload-zone">
                    <input type="file" name="receipt" id="receipt-input" accept=".jpg,.jpeg,.png,.pdf" class="upload-input">

                    <div class="upload-idle" id="upload-idle">
                        <div class="upload-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                        </div>
                        <p class="upload-label">Drop your receipt here</p>
                        <p class="upload-sub">or <button type="button" class="upload-browse" id="upload-browse">click to browse</button></p>
                        <p class="upload-sub">JPG, PNG, PDF &middot; max 10MB</p>
                    </div>

                    <div class="upload-preview" id="upload-preview" style="display:none;">
                        <div class="preview-icon" id="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>
                        <div class="preview-info">
                            <div class="preview-name" id="preview-name"></div>
                            <div class="preview-size" id="preview-size"></div>
                        </div>
                        <button type="button" class="preview-remove" id="preview-remove" title="Remove">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                </div>

                @error('receipt')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                <button type="submit" class="ocr-submit" id="extract-submit" disabled style="display:none;">
                    <span id="submit-label">Extract Receipt</span>
                </button>

                <p class="extract-credit-note">1 credit will be used · extracts automatically on file select</p>
            </form>
        </div>

        {{-- RIGHT: Result --}}
        <div class="extract-col extract-col--result">
            <div class="extract-section-title">Extraction Result</div>

            @if (!isset($result))
                <div class="extract-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="8" y1="13" x2="16" y2="13" />
                        <line x1="8" y1="17" x2="16" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    <p>Upload a receipt to see results</p>
                </div>
            @else
                @php
                    $r = $result;
                    $conf = $r->confidence_score ? round($r->confidence_score * 100) : null;
                    $confClass = $conf === null ? '' : ($conf >= 75 ? 'conf-good' : ($conf >= 50 ? 'conf-warn' : 'conf-bad'));
                    $confLabel = $conf === null ? 'N/A' : ($conf >= 75 ? 'Good' : ($conf >= 50 ? 'Fair' : 'Poor'));
                @endphp

                <div class="result-schema">

                    {{-- Platform + category badges --}}
                    <div class="result-badges">
                        @if ($r->platform)
                            <span class="badge badge-success">{{ $r->platform }}</span>
                        @endif
                        @if ($r->category)
                            <span class="badge badge-warn">{{ $r->category }}</span>
                        @endif
                        @if ($r->needs_review)
                            <span class="badge badge-warn">⚠ Review needed</span>
                        @endif
                    </div>

                    {{-- Key fields --}}
                    <div class="result-fields">
                        @if ($r->transaction_id)
                            <div class="result-field-row">
                                <span class="result-field-label">Transaction ID</span>
                                <span class="result-field-value">{{ $r->transaction_id }}</span>
                            </div>
                        @endif
                        @if ($r->transaction_date)
                            <div class="result-field-row">
                                <span class="result-field-label">Date</span>
                                <span class="result-field-value">
                                    {{ $r->transaction_date->format('d M Y') }}
                                    @if ($r->transaction_time) &middot; {{ $r->transaction_time }} @endif
                                </span>
                            </div>
                        @endif
                        @if ($r->vendor_name)
                            <div class="result-field-row">
                                <span class="result-field-label">Vendor</span>
                                <span class="result-field-value">{{ $r->vendor_name }}</span>
                            </div>
                        @endif
                        @if ($r->employee_name)
                            <div class="result-field-row">
                                <span class="result-field-label">Employee</span>
                                <span class="result-field-value">{{ $r->employee_name }}</span>
                            </div>
                        @endif
                        @if ($r->payment_method)
                            <div class="result-field-row">
                                <span class="result-field-label">Payment</span>
                                <span class="result-field-value">{{ $r->payment_method }}</span>
                            </div>
                        @endif
                        @if ($r->total_amount)
                            <div class="result-field-row result-field-row--total">
                                <span class="result-field-label">Total</span>
                                <span class="result-field-value result-total">Rp {{ number_format($r->total_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Confidence bar --}}
                    @if ($conf !== null)
                        <div class="result-confidence {{ $confClass }}">
                            <div class="conf-header">
                                <span>Confidence</span>
                                <span>{{ $confLabel }} ({{ $conf }}%)</span>
                            </div>
                            <div class="conf-bar">
                                <div class="conf-fill" style="width:{{ $conf }}%"></div>
                            </div>
                        </div>
                    @endif

                    {{-- Line items table --}}
                    @if (!empty($r->line_items) && is_array($r->line_items))
                        <div class="result-section-label" style="margin-top:1rem;">Line Items</div>
                        <table class="result-items-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($r->line_items as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? '-' }}</td>
                                        <td>{{ $item['qty'] ?? '-' }}</td>
                                        <td>Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    {{-- Raw text --}}
                    @if ($r->extracted_text)
                        <div class="result-section-label" style="margin-top:1rem;">Raw Text</div>
                        <div class="result-text" id="result-raw">{{ $r->extracted_text }}</div>
                    @endif

                    {{-- Actions --}}
                    <div class="result-actions">
                        @if ($r->extracted_text)
                            <button class="result-copy" id="copy-btn" onclick="copyRaw()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" />
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                </svg>
                                <span id="copy-label">Copy JSON</span>
                            </button>
                        @endif
                        <button class="result-copy" onclick="resetForm()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="1 4 1 10 7 10" />
                                <path d="M3.51 15a9 9 0 1 0 .49-3.87" />
                            </svg>
                            Extract Another
                        </button>
                    </div>
                </div>

                @php
                    $jsonOutput = json_encode([
                        'platform'         => $r->platform,
                        'category'         => $r->category,
                        'transaction_id'   => $r->transaction_id,
                        'transaction_date' => $r->transaction_date?->format('Y-m-d'),
                        'transaction_time' => $r->transaction_time,
                        'vendor_name'      => $r->vendor_name,
                        'employee_name'    => $r->employee_name,
                        'subtotal'         => $r->subtotal,
                        'discount'         => $r->discount,
                        'delivery_fee'     => $r->delivery_fee,
                        'service_fee'      => $r->service_fee,
                        'tax'              => $r->tax,
                        'total_amount'     => $r->total_amount,
                        'payment_method'   => $r->payment_method,
                        'source_type'      => $r->source_type,
                        'confidence_score' => $r->confidence_score,
                        'needs_review'     => $r->needs_review,
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                @endphp
                <script>
                    window._resultJson = @json($jsonOutput);
                </script>
            @endif
        </div>
    </div>

</div>

<script>
    const receiptInput = document.getElementById('receipt-input');
    const uploadZone   = document.getElementById('upload-zone');
    const uploadIdle   = document.getElementById('upload-idle');
    const uploadPrev   = document.getElementById('upload-preview');
    const previewName  = document.getElementById('preview-name');
    const previewSize  = document.getElementById('preview-size');
    const removeBtn    = document.getElementById('preview-remove');
    const browseBtn    = document.getElementById('upload-browse');
    const submitBtn    = document.getElementById('extract-submit');
    const submitLabel  = document.getElementById('submit-label');
    const form         = document.getElementById('extract-form');

    if (browseBtn) browseBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        receiptInput.click();
    });

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function showPreview(file) {
        previewName.textContent = file.name;
        previewSize.textContent = formatBytes(file.size);
        uploadIdle.style.display = 'none';
        uploadPrev.style.display = 'flex';
        submitBtn.disabled = false;
        uploadZone.classList.add('has-file');
    }

    function clearPreview() {
        receiptInput.value = '';
        uploadIdle.style.display = 'flex';
        uploadPrev.style.display = 'none';
        submitBtn.disabled = true;
        uploadZone.classList.remove('has-file');
    }

    if (receiptInput) {
        receiptInput.addEventListener('change', () => {
            const file = receiptInput.files[0];
            if (file) {
                showPreview(file);
                showScanOverlay(file.name);
                form.submit();
            }
        });
    }

    function showScanOverlay(filename) {
        const overlay = document.getElementById('scan-overlay');
        const nameEl  = document.getElementById('scan-filename');
        if (nameEl) nameEl.textContent = filename;
        if (overlay) {
            overlay.classList.add('scan-overlay--visible');
            document.body.style.overflow = 'hidden';
        }
    }

    if (removeBtn) removeBtn.addEventListener('click', clearPreview);

    if (uploadZone) {
        uploadZone.addEventListener('click', (e) => {
            if (e.target.closest('#preview-remove')) return;
            if (uploadPrev.style.display !== 'none') return;
            receiptInput.click();
        });

        uploadZone.addEventListener('dragover', e => {
            e.preventDefault();
            uploadZone.classList.add('drag-over');
        });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
        uploadZone.addEventListener('drop', e => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                receiptInput.files = dt.files;
                showPreview(file);
            }
        });
    }

    function copyRaw() {
        const json = window._resultJson || '';
        navigator.clipboard.writeText(json).then(() => {
            const label = document.getElementById('copy-label');
            const btn   = document.getElementById('copy-btn');
            label.textContent = 'Copied!';
            if (btn) btn.classList.add('copied');
            setTimeout(() => {
                label.textContent = 'Copy JSON';
                if (btn) btn.classList.remove('copied');
            }, 2000);
        });
    }

    function resetForm() {
        clearPreview();
        window.location.href = '{{ route("extract.index") }}';
    }
</script>

{{-- Full-screen scan overlay --}}
<div id="scan-overlay" aria-hidden="true">
    <div class="scan-card">
        <div class="scan-receipt-wrap">
            {{-- viewBox matches wrapper (72×90) so rect is pixel-perfect centered --}}
            <svg class="scan-doc-icon" viewBox="0 0 72 90" fill="none">
                <rect x="10" y="11" width="52" height="68" rx="5" stroke="var(--border)" stroke-width="2" fill="var(--surface)"/>
                <line x1="20" y1="30" x2="52" y2="30" stroke="var(--border)" stroke-width="2"/>
                <line x1="20" y1="41" x2="52" y2="41" stroke="var(--border)" stroke-width="2"/>
                <line x1="20" y1="52" x2="44" y2="52" stroke="var(--border)" stroke-width="2"/>
                <line x1="20" y1="63" x2="38" y2="63" stroke="var(--border)" stroke-width="2"/>
            </svg>
            <div class="scan-line"></div>
        </div>
        <div class="scan-filename" id="scan-filename"></div>
        <div class="scan-label">Analyzing with Mistral AI</div>
        <div class="scan-dots"><span></span><span></span><span></span></div>
        <div class="scan-credit-tag">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Using 1 credit
        </div>
    </div>
</div>
@endsection
