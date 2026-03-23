<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OcrResult extends Model
{
    protected $fillable = [
        'user_id', 'filename', 'file_path', 'preview_path', 'file_type',
        'language', 'extracted_text', 'confidence', 'status', 'ocr_engine',
        'custom_prompt', 'platform', 'category', 'transaction_id',
        'transaction_date', 'transaction_time', 'vendor_name', 'employee_name',
        'subtotal', 'discount', 'delivery_fee', 'service_fee', 'tax',
        'total_amount', 'payment_method', 'source_type', 'confidence_score',
        'needs_review',
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

    public function authorizeOwner(): void
    {
        abort_if($this->user_id !== auth()->id(), 403);
    }

    public function deleteFiles(): void
    {
        Storage::disk('local')->delete($this->file_path);
        if ($this->preview_path) {
            Storage::disk('local')->delete($this->preview_path);
        }
    }
}
