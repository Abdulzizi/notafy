<?php

namespace App\Exports;

use App\Models\OcrResult;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HistoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private int $userId,
        private ?string $from = null,
        private ?string $to = null,
    ) {}

    public function collection(): Collection
    {
        $query = OcrResult::where('user_id', $this->userId)
            ->where('status', 'done')
            ->orderBy('created_at', 'desc');

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }
        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Time',
            'Platform',
            'Category',
            'Vendor',
            'Employee',
            'Transaction ID',
            'Subtotal (Rp)',
            'Discount (Rp)',
            'Delivery Fee (Rp)',
            'Service Fee (Rp)',
            'Tax (Rp)',
            'Total (Rp)',
            'Payment Method',
            'Confidence Score',
            'Filename',
        ];
    }

    public function map($row): array
    {
        return [
            $row->transaction_date?->format('d/m/Y') ?? $row->created_at->format('d/m/Y'),
            $row->transaction_time ?? $row->created_at->format('H:i'),
            $row->platform ?? '',
            $row->category ?? '',
            $row->vendor_name ?? '',
            $row->employee_name ?? '',
            $row->transaction_id ?? '',
            $row->subtotal ?? 0,
            $row->discount ?? 0,
            $row->delivery_fee ?? 0,
            $row->service_fee ?? 0,
            $row->tax ?? 0,
            $row->total_amount ?? 0,
            $row->payment_method ?? '',
            $row->confidence_score ? number_format($row->confidence_score * 100, 1) . '%' : '',
            $row->filename,
        ];
    }
}
