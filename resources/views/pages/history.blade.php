@extends('layouts.dashboard')

@section('title', 'History')

@section('content')
    <div class="ocr-history">

        <div class="ocr-header">
            <div>
                <h1 class="dash-title">History</h1>
                <p class="dash-subtitle">
                    {{ $results->total() }} receipts
                    @if($historyLimited ?? false)
                        &mdash; showing last 30 &nbsp;<a href="{{ route('pricing') }}" style="color:var(--accent);text-decoration:none;">Upgrade for unlimited</a>
                    @endif
                </p>
            </div>
            <a href="{{ route('extract.index') }}" class="hero-cta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                New Receipt
            </a>
        </div>

        @if ($results->isEmpty())
            <div class="history-empty">
                <div class="history-empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div class="history-empty-title">No receipts yet</div>
                <p>Upload your first receipt to get started.</p>
                <a href="{{ route('extract.index') }}" class="hero-cta" style="margin-top:1.5rem;">Extract now</a>
            </div>
        @else
            <div class="history-table">
                <div class="history-table-head">
                    <span>File</span>
                    <span>Type</span>
                    <span>Status</span>
                    <span>Date</span>
                    <span></span>
                </div>

                @foreach ($results as $item)
                    <a href="{{ route('result.show', $item) }}" class="history-row">
                        <div class="history-cell history-file">
                            <div class="history-file-icon">
                                @if ($item->file_type === 'pdf')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                    </svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                @endif
                            </div>
                            <div class="history-file-info">
                                <span class="history-filename">{{ $item->filename }}</span>
                                @if ($item->extracted_text)
                                    <span class="history-preview">{{ Str::limit(trim($item->extracted_text), 60) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="history-cell">
                            <span class="history-type">{{ strtoupper($item->file_type) }}</span>
                        </div>

                        <div class="history-cell">
                            <span class="badge {{ $item->status === 'done' ? 'badge-success' : ($item->status === 'failed' ? 'badge-warn' : '') }}">
                                {{ $item->status }}
                            </span>
                        </div>

                        <div class="history-cell history-date">
                            <span>{{ $item->created_at->format('M d, Y') }}</span>
                            <span>{{ $item->created_at->format('H:i') }}</span>
                        </div>

                        <div class="history-cell history-actions">
                            <span class="history-arrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($results->hasPages())
                <div class="history-pagination">
                    {{ $results->links() }}
                </div>
            @endif

            <x-ad-slot />
        @endif

    </div>
@endsection
