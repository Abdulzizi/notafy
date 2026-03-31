@extends('layouts.dashboard')

@section('title', $ocr->filename)

@section('content')
    <div class="ocr-result">

        <a href="{{ route('history') }}" class="result-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 5l-7 7 7 7" />
            </svg>
            Back to History
        </a>

        @if (session('warning'))
            <div class="alert-warning">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
                {{ session('warning') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert-warning" style="background:rgba(112,200,152,0.07);border-color:rgba(112,200,152,0.2);color:var(--success);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="result-header">
            <div class="result-header-left">
                <div class="result-filename">{{ $ocr->filename }}</div>
                <div class="result-info">
                    <span class="badge {{ $ocr->status === 'done' ? 'badge-success' : 'badge-warn' }}">
                        {{ $ocr->status }}
                    </span>
                    <span class="result-dot">·</span>
                    <span>{{ strtoupper($ocr->file_type) }}</span>
                    <span class="result-dot">·</span>
                    <span>{{ $ocr->created_at->format('M d, Y · H:i') }}</span>
                    <span class="result-dot">·</span>
                    <span style="text-transform:uppercase;font-size:0.72em;letter-spacing:0.05em;">
                        ✦ Mistral
                    </span>
                </div>
            </div>

            <div class="result-header-right">
                @if ($ocr->status === 'done' && $ocr->extracted_text)
                    <button class="result-copy" id="copy-btn" onclick="copyText()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" />
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                        </svg>
                        <span id="copy-label">Copy text</span>
                    </button>
                    <form method="POST" action="{{ route('result.rerun', $ocr) }}" id="rerun-form" style="display:inline;">
                        @csrf
                        <button type="button" class="result-copy" onclick="confirmRerun()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 4 23 10 17 10" />
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                            </svg>
                            Re-run
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('result.destroy', $ocr) }}" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="result-delete" onclick="confirmDelete()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            <path d="M10 11v6M14 11v6" />
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        @if ($ocr->status === 'failed')
            <div class="result-failed">
                <div class="result-failed-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div class="result-failed-title">Failed to read receipt</div>
                <p>Make sure the photo is clear. The file may be corrupted, locked, or contain no readable text.</p>
                <a href="{{ route('extract.index') }}" class="hero-cta" style="margin-top:1.5rem;">Try another</a>
            </div>
        @else
            <div class="result-stack">

                <div class="result-preview">
                    <div class="result-section-label">Original file</div>
                    @if ($ocr->file_type === 'image')
                        <div class="result-preview-img">
                            <img src="{{ route('ocr.file', $ocr) }}" alt="{{ $ocr->filename }}">
                        </div>
                    @else
                        @if ($ocr->preview_path)
                            <div class="result-preview-img">
                                <img src="{{ route('ocr.preview', $ocr) }}" alt="Page 1 preview">
                            </div>
                        @else
                            <div class="result-preview-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                </svg>
                                <span>PDF preview not available</span>
                            </div>
                        @endif
                    @endif
                </div>

                @if (!$ocr->extracted_text || trim($ocr->extracted_text) === '')
                    <div class="result-failed">
                        <div class="result-failed-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>
                        <div class="result-failed-title">No text detected</div>
                        <p>The file was processed but no text was found. Try a clearer photo.</p>
                    </div>
                @else
                    <div class="result-body">

                        <div class="result-toolbar">
                            <div class="result-section-label" style="margin:0;">Extracted Text</div>
                            <div class="result-toolbar-right">
                                <span class="result-wordcount" id="word-count"></span>

                                @if ($ocr->confidence !== null)
                                    @php
                                        $conf = $ocr->confidence;
                                        $confClass = $conf >= 70 ? 'conf-good' : ($conf >= 40 ? 'conf-warn' : 'conf-bad');
                                        $confLabel = $conf >= 70 ? 'Good' : ($conf >= 40 ? 'Fair' : 'Poor');
                                    @endphp
                                    <div class="result-confidence {{ $confClass }}">
                                        <div class="conf-bar">
                                            <div class="conf-fill" style="width:{{ min($conf, 100) }}%"></div>
                                        </div>
                                        <span>{{ $confLabel }} ({{ $conf }}%)</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($ocr->needs_review)
                            <div class="result-low-quality">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                                <div>
                                    <strong>Manual Review Recommended</strong>
                                    <p>Confidence score is below 75%. Verify key fields before use.</p>
                                </div>
                            </div>
                        @endif

                        <div class="result-text" id="result-text">{{ $ocr->extracted_text }}</div>

                        @if(auth()->user()->isPro())
                            <div class="result-download-row">
                                <a href="{{ route('result.download', [$ocr, 'txt']) }}" class="result-download-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Download .txt
                                </a>
                                <a href="{{ route('result.download', [$ocr, 'pdf']) }}" class="result-download-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                    Download .pdf
                                </a>
                            </div>
                        @else
                            <div class="result-download-locked">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <span>Download as .txt or .pdf</span>
                                <a href="{{ route('pricing') }}" class="result-copy--locked">Upgrade to Pro</a>
                            </div>
                        @endif

                    </div>
                @endif

            </div>
        @endif

        <x-ad-slot />

    </div>

    <script>
        const textEl = document.getElementById('result-text');
        const countEl = document.getElementById('word-count');
        if (textEl && countEl) {
            const words = textEl.textContent.trim().split(/\s+/).filter(Boolean).length;
            const chars = textEl.textContent.trim().length;
            countEl.textContent = `${words.toLocaleString()} words · ${chars.toLocaleString()} chars`;
        }

        function copyText() {
            const text = document.getElementById('result-text').textContent;
            navigator.clipboard.writeText(text).then(() => {
                const label = document.getElementById('copy-label');
                const btn = document.getElementById('copy-btn');
                label.textContent = 'Copied!';
                btn.classList.add('copied');
                setTimeout(() => {
                    label.textContent = 'Copy text';
                    btn.classList.remove('copied');
                }, 2000);
            });
        }

        function confirmRerun() {
            document.getElementById('rerun-modal').classList.add('modal-overlay--visible');
            document.body.style.overflow = 'hidden';
        }
        function closeRerunModal() {
            document.getElementById('rerun-modal').classList.remove('modal-overlay--visible');
            document.body.style.overflow = '';
        }
        function submitRerun() {
            const btn = document.querySelector('#rerun-modal .modal-btn--confirm');
            if (btn.disabled) return;
            btn.disabled = true;
            btn.textContent = 'Processing…';
            closeRerunModal();
            showRerunOverlay();
            document.getElementById('rerun-form').submit();
        }

        function showRerunOverlay() {
            const overlay = document.getElementById('rerun-overlay');
            if (overlay) {
                overlay.classList.add('scan-overlay--visible');
                document.body.style.overflow = 'hidden';
            }
        }
        document.getElementById('rerun-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeRerunModal();
        });

        function confirmDelete() {
            if (confirm('Delete this receipt? This cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>

{{-- Re-run scan overlay --}}
<div id="rerun-overlay" aria-hidden="true">
    <div class="scan-card">
        <div class="scan-receipt-wrap">
            <svg class="scan-doc-icon" viewBox="0 0 72 90" fill="none">
                <rect x="10" y="11" width="52" height="68" rx="5" stroke="var(--border)" stroke-width="2" fill="var(--surface)"/>
                <line x1="20" y1="30" x2="52" y2="30" stroke="var(--border)" stroke-width="2"/>
                <line x1="20" y1="41" x2="52" y2="41" stroke="var(--border)" stroke-width="2"/>
                <line x1="20" y1="52" x2="44" y2="52" stroke="var(--border)" stroke-width="2"/>
                <line x1="20" y1="63" x2="38" y2="63" stroke="var(--border)" stroke-width="2"/>
            </svg>
            <div class="scan-line"></div>
        </div>
        <div class="scan-filename" style="max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ocr->filename }}</div>
        <div class="scan-label">Re-processing with Mistral AI</div>
        <div class="scan-dots"><span></span><span></span><span></span></div>
        <div class="scan-credit-tag">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Using 1 credit
        </div>
    </div>
</div>

{{-- Re-run confirm modal --}}
<div id="rerun-modal" class="modal-overlay" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
            </svg>
        </div>
        <h3 class="modal-title">Re-run extraction?</h3>
        <p class="modal-body">
            This will re-process <strong>{{ $ocr->filename }}</strong> using Mistral AI.<br>
            <span class="modal-credit-cost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Uses 1 credit from your balance
            </span>
        </p>
        <div class="modal-footer">
            <button class="modal-btn modal-btn--cancel" onclick="closeRerunModal()">Cancel</button>
            <button class="modal-btn modal-btn--confirm" onclick="submitRerun()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <polyline points="23 4 23 10 17 10"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                Use 1 credit &amp; re-run
            </button>
        </div>
    </div>
</div>
@endsection
