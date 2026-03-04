<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Worker;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class PdfController extends Controller
{
    /**
     * View invoice PDF inline in the browser.
     */
    public function viewInvoice(int $id)
    {
        $invoice = Invoice::with('items', 'project')->findOrFail($id);
        $setting = Setting::first();

        $pdf = Pdf::loadView('pdfs.invoice', compact('invoice', 'setting'))
            ->setPaper('a4', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Download individual worker salary slip PDF.
     */
    public function workerSalaryPdf(int $worker, int $month, int $year)
    {
        $worker      = Worker::findOrFail($worker);
        $setting     = Setting::first();
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // Daily attendance keyed by day number
        $attendances = Attendance::with('project')
            ->where('worker_id', $worker->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(fn($a) => (int) Carbon::parse($a->date)->day);

        // Attendance totals for the month
        $totalHours  = 0;
        $daysPresent = 0;
        $daysAbsent  = 0;

        foreach ($attendances as $att) {
            if (is_numeric($att->hours)) {
                $totalHours += (float) $att->hours;
                $daysPresent++;
            } elseif (strtoupper($att->hours) === 'A') {
                $daysAbsent++;
            }
        }

        $grossSalary = $totalHours * ($worker->internal_pay_rate ?? 0);

        // Payments made during this specific month
        $monthPayments = $worker->payments()
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->latest('payment_date')
            ->get();

        $monthPaid = $monthPayments->sum('amount');

        // Overall due = total earned all time – total paid all time
        $allAttendances = Attendance::where('worker_id', $worker->id)->get();
        $totalEarnedAll = $allAttendances
            ->filter(fn($a) => is_numeric($a->hours))
            ->sum(fn($a) => (float) $a->hours * ($worker->internal_pay_rate ?? 0));
        $totalPaidAll = $worker->payments()->sum('amount');
        $overallDue   = $totalEarnedAll - $totalPaidAll;

        $dateLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');

        $pdf = Pdf::loadView('pdfs.worker-salary', compact(
            'worker', 'setting', 'attendances', 'daysInMonth',
            'totalHours', 'daysPresent', 'daysAbsent',
            'grossSalary', 'monthPayments', 'monthPaid',
            'overallDue', 'dateLabel', 'month', 'year'
        ))->setPaper('a4', 'landscape');

        $fileName = 'salary_' . Str::slug($worker->name) . '_' . $year . '_' . $month . '.pdf';

        return response()->streamDownload(fn() => print($pdf->stream()), $fileName);
    }

    /**
     * Simple payment receipt PDF for a worker (used in Transaction History).
     */
    public function paymentReceipt(int $worker, int $month, int $year)
    {
        $worker    = Worker::findOrFail($worker);
        $setting   = Setting::first();
        $dateLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');

        $payments = $worker->payments()
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->oldest('payment_date')
            ->get();

        $totalPaid = $payments->sum('amount');

        $pdf = Pdf::loadView('pdfs.payment-receipt', compact(
            'worker', 'setting', 'payments', 'totalPaid', 'dateLabel', 'month', 'year'
        ))->setPaper('a4', 'portrait');

        $fileName = 'payment_receipt_' . Str::slug($worker->name) . '_' . $year . '_' . $month . '.pdf';

        return response()->streamDownload(fn() => print($pdf->stream()), $fileName);
    }
}
