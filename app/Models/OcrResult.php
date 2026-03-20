<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OcrResult extends Model
{
    protected $fillable = [
        // ── Core upload fields ────────────────────────────────────────────────
        'user_id',
        'filename',
        'file_path',
        'preview_path',
        'file_type',
        'language',
        'extracted_text',   // raw OCR output — maps to schema's raw_text
        'confidence',       // Tesseract word-level confidence, 0–100 scale
        'status',
        'ocr_engine',
        'custom_prompt',

        // ── Notafy expense receipt schema ─────────────────────────────────────
        'platform',         // shopee|tokopedia|gofood|gocar|goride|grabfood|grabbike|grabcar|indomaret|nota_warung|unknown
        'category',         // transport|food|belanja|other
        'transaction_id',   // No. Pesanan / Order ID / Booking Code
        'transaction_date', // YYYY-MM-DD
        'transaction_time', // HH:mm (nullable)
        'vendor_name',      // nama toko / resto / seller
        'employee_name',    // nama karyawan, from transport receipts (nullable)
        'subtotal',         // IDR, no decimals (nullable)
        'discount',         // IDR (nullable)
        'delivery_fee',     // IDR (nullable)
        'service_fee',      // IDR (nullable)
        'tax',              // PPN, IDR (nullable)
        'total_amount',     // IDR — always required
        'payment_method',   // GoPay|LinkAja|Bank BRI|COD|etc
        'source_type',      // digital_pdf|digital_jpg|thermal_scan|photo_hardcopy
        'confidence_score', // 0.0–1.0, Notafy's own quality assessment
        'needs_review',     // true if confidence_score < 0.75
    ];

    protected $casts = [
        'needs_review'     => 'boolean',
        'confidence_score' => 'float',
        'subtotal'         => 'integer',
        'discount'         => 'integer',
        'delivery_fee'     => 'integer',
        'service_fee'      => 'integer',
        'tax'              => 'integer',
        'total_amount'     => 'integer',
        'transaction_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
