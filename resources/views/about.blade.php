@extends('layouts.app')

@section('title', 'About')
@section('description', 'Notafy reads receipts so you do not have to. Built for anyone tired of retyping expense data.')

@section('content')
    <div class="about-page">

        <section class="about-fullbleed">
            <div class="about-orb"></div>
            <div class="about-fullbleed-inner">
                <div class="section-label">About</div>
                <blockquote class="about-quote">
                    Receipts pile up.<br>
                    <em>We read them for you.</em>
                </blockquote>
                <p class="about-quote-sub">
                    A clean interface on top of Mistral OCR.<br>
                    Built for finance teams, freelancers, and anyone tired of retyping.
                </p>
            </div>
        </section>

        <div class="about-statsbar">
            <div class="about-statsbar-item">
                <span class="about-statsbar-num">100+</span>
                <span class="about-statsbar-label">Languages</span>
            </div>
            <div class="about-statsbar-divider"></div>
            <div class="about-statsbar-item">
                <span class="about-statsbar-num">3</span>
                <span class="about-statsbar-label">File types</span>
            </div>
            <div class="about-statsbar-divider"></div>
            <div class="about-statsbar-item">
                <span class="about-statsbar-num">10MB</span>
                <span class="about-statsbar-label">Max upload</span>
            </div>
            <div class="about-statsbar-divider"></div>
            <div class="about-statsbar-item">
                <span class="about-statsbar-num">1</span>
                <span class="about-statsbar-label">Credit per scan</span>
            </div>
        </div>

        <section class="about-content">
            <div class="about-content-grid">
                <h2 class="about-section-heading">Why</h2>
                <div class="about-content-right">
                    <p>Receipts get trapped in photos every day. From e-commerce to food delivery to retail, the text is there but locked away. Getting it out should not mean manual retyping or expensive enterprise tools.</p>
                </div>
            </div>

            <div class="about-content-grid">
                <h2 class="about-section-heading">How</h2>
                <div class="about-content-right">
                    <div class="about-steps-minimal">
                        <div class="about-step-minimal">
                            <span class="about-step-n">01</span>
                            <div>
                                <strong>Upload</strong>
                                <p>Drop a receipt photo or PDF. It is stored privately in your account and never shared.</p>
                            </div>
                        </div>
                        <div class="about-step-minimal">
                            <span class="about-step-n">02</span>
                            <div>
                                <strong>Process</strong>
                                <p>Mistral AI reads the file and returns structured text. Multi-page PDFs are handled page by page.</p>
                            </div>
                        </div>
                        <div class="about-step-minimal">
                            <span class="about-step-n">03</span>
                            <div>
                                <strong>Copy</strong>
                                <p>The result is saved to your history and ready to copy or download with one click.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
