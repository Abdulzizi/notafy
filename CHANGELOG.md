# Changelog

All notable changes to Notafy are documented here.

---

## [1.0.0] - 2026-03-20

### Added

- **Expense receipt OCR** for 8 Indonesian platforms: Shopee, Tokopedia, GoFood,
  GoCar, GoRide, GrabFood, GrabBike, GrabCar, Indomaret, and nota warung
- **Structured JSON output schema** covering platform, category, transaction ID,
  date/time, vendor, employee name, subtotal, discount, delivery fee, service fee,
  tax, total amount, payment method, source type, confidence score, and needs_review flag
- **Mistral OCR integration** — dual-call pipeline: `mistral-ocr-latest` for raw text
  extraction, then `mistral-small-latest` with a platform-aware Indonesian receipt
  prompt for structured field extraction
- **Confidence scoring** — `confidence_score` (0.0–1.0) assigned per receipt based on
  source quality: digital PDFs score 0.9–1.0, photos/scans 0.5–0.69, handwritten 0.0–0.49
- **`needs_review` flag** — automatically set `true` when `confidence_score < 0.75`,
  surfaced in the result UI with a warning banner
- **Platform detection** — Mistral prompt detects platform from visual and textual cues
  (logo colors, header patterns, transaction ID prefixes, field names)
- **Tesseract fallback** — local OCR via Tesseract with ImageMagick preprocessing
  (deskew, contrast stretch, sharpening) for fast zero-cost extraction
- **Mistral monthly quota** — Free plan: 20 calls/month, Pro plan: 300 calls/month,
  with per-user tracking and reset at start of each month
- **Mistral rerun** — existing Tesseract results can be re-processed with Mistral OCR
  via `POST /ocr/result/{id}/rerun`
- **Google SSO** — login and registration via Google OAuth 2.0
- **Dual billing** — Stripe (USD, recurring subscription via Cashier) and Mayar
  (IDR, hosted membership page with HMAC-SHA256 webhook verification)
- **Pro plan** — download results as `.txt` or `.pdf`, unlimited history,
  no ads, 300 Mistral calls/month
- **AdSense integration** — ad slots shown to Free-plan users only
- **PDF multi-page support** — all pages extracted and concatenated for both Tesseract
  and Mistral engines
- **Download as PDF** — branded Notafy PDF export with metadata (engine, language,
  confidence, timestamp) via DomPDF

### Changed

- Rebranded from generic Laravel OCR scaffold to **Notafy** — an expense receipt
  OCR tool for Indonesian corporate audit and reimbursement workflows
- UI copy updated to casual Indonesian/English mix (`nota`, `struk`, `scan nota`, etc.)
- `<title>` tags across all layouts updated to `Notafy — OCR Nota Otomatis`
- `composer.json` name updated to `notafy/notafy`
- Default `APP_NAME` updated from `Laravel` to `Notafy` in `.env.example`,
  `config/app.php`, and blade fallback strings
- OCR submit button and processing state updated from generic "Extract text" /
  "Extracting…" to context-aware labels ("Baca Nota", "Scanning…")
- History page "extractions" count replaced with "nota" count
- Confidence label values updated: Good / Fair / Poor retained in English for
  UI clarity, with Indonesian contextual descriptions for low-confidence banners
- Mistral rerun banner copy updated to reflect receipt-specific context
  (stamps, embossed headers, handwriting on struk)
- Flash messages from controllers localized to casual Indonesian
  (e.g. "Nota dihapus.", "Diproses ulang pakai Mistral OCR.")

### Fixed

- **Mistral OCR was silently skipped on initial upload** — the `upload()` method
  incremented the Mistral quota counter but never called `runMistralOcr()`, leaving
  `extracted_text` as an empty string. Fixed by wiring the full OCR pipeline
  (raw extraction + structured schema extraction) into the Mistral branch of `upload()`.
