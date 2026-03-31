<?php

namespace App\Http\Controllers;

use App\Exports\HistoryExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function history(Request $request)
    {
        $user = $request->user();

        abort_unless($user->isStarter(), 403, 'Upgrade to Starter or Pro to export your history.');

        $format = $request->query('format', 'csv');

        // Excel export is Pro-only
        if ($format === 'xlsx') {
            abort_unless($user->isPro(), 403, 'Upgrade to Pro to export as Excel.');
        }

        abort_unless(in_array($format, ['csv', 'xlsx']), 404);

        $from = $request->query('from');
        $to   = $request->query('to');

        $export   = new HistoryExport($user->id, $from, $to);
        $filename = 'notafy-history-' . now()->format('Y-m-d') . '.' . $format;

        $writerType = $format === 'xlsx'
            ? \Maatwebsite\Excel\Excel::XLSX
            : \Maatwebsite\Excel\Excel::CSV;

        return Excel::download($export, $filename, $writerType);
    }
}
