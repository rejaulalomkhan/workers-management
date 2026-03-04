<?php

namespace App\Livewire\Salary;

use Livewire\Component;
use App\Models\Worker;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryReport extends Component
{
    public $month;
    public $year;
    public $reportData = [];

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->generateReport();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'year'])) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $this->reportData = [];
        $workers = Worker::orderBy('name')->get();
        
        foreach ($workers as $worker) {
            $attendances = Attendance::where('worker_id', $worker->id)
                ->whereYear('date', $this->year)
                ->whereMonth('date', $this->month)
                ->get();
                
            $totalHours = 0;
            $daysPresent = 0;
            $daysAbsent = 0;

            foreach ($attendances as $att) {
                if (is_numeric($att->hours)) {
                    $totalHours += (float)$att->hours;
                    $daysPresent++;
                } else if (strtoupper($att->hours) === 'A') {
                    $daysAbsent++;
                }
            }

            if ($totalHours > 0 || $daysPresent > 0 || $daysAbsent > 0) {
                $totalPay = $totalHours * $worker->internal_pay_rate;

                $this->reportData[] = [
                    'worker_id' => $worker->id,
                    'worker_id_number' => $worker->worker_id_number,
                    'name' => $worker->name,
                    'trade' => $worker->trade,
                    'rate' => $worker->internal_pay_rate,
                    'total_hours' => $totalHours,
                    'days_present' => $daysPresent,
                    'days_absent' => $daysAbsent,
                    'total_pay' => $totalPay,
                ];
            }
        }
    }

    public function exportPdf()
    {
        $setting = Setting::first();
        $dateLabel = Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
        $fileName = 'salary_report_' . $this->year . '_' . $this->month . '.pdf';

        $pdf = Pdf::loadView('pdfs.salary', [
            'reportData' => $this->reportData,
            'dateLabel'  => $dateLabel,
            'setting'    => $setting,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function downloadWorkerPdf($workerId)
    {
        $this->redirect(route('salary.worker-pdf', [
            'worker' => $workerId,
            'month'  => $this->month,
            'year'   => $this->year,
        ]));
    }

    public function render()
    {
        return view('livewire.salary.salary-report');
    }
}
