<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Project;
use App\Models\Worker;
use App\Models\Attendance;
use Carbon\Carbon;

class MonthlyAttendance extends Component
{
    public $filterMonth;
    public $filterYear;
    public $projectId  = '';
    public $tradeFilter = '';   // '' = all trades

    // PDF date range
    public $pdfFromDate;
    public $pdfToDate;

    public function mount()
    {
        $this->filterMonth = date('n');
        $this->filterYear  = date('Y');

        // Default PDF range = first & last day of current month
        $this->pdfFromDate = date('Y-m-01');
        $this->pdfToDate   = date('Y-m-t');
    }

    public function getReportData(?string $from = null, ?string $to = null)
    {
        if ($from && $to) {
            $startDate   = Carbon::parse($from)->startOfDay();
            $endDate     = Carbon::parse($to)->endOfDay();
        } else {
            $startDate   = Carbon::createFromDate($this->filterYear, $this->filterMonth, 1)->startOfMonth();
            $endDate     = $startDate->copy()->endOfMonth();
        }

        // Build day-range array (day number → date string)
        $dayList = [];
        $cursor  = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $dayList[] = $cursor->format('Y-m-d');
            $cursor->addDay();
        }
        $daysInMonth = count($dayList);

        $query = Worker::query();
        if ($this->projectId) {
            $query->whereHas('attendances', function ($q) use ($startDate, $endDate) {
                $q->where('project_id', $this->projectId)
                  ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            });
        }
        // Filter by trade/category
        if ($this->tradeFilter) {
            $query->where('trade', $this->tradeFilter);
        }

        $workers = $query->get();

        $attendanceQuery = Attendance::whereIn('worker_id', $workers->pluck('id'))
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($this->projectId) {
            $attendanceQuery->where('project_id', $this->projectId);
        }

        $attendances = $attendanceQuery->get()->groupBy('worker_id');

        $reportData = [];
        foreach ($workers as $worker) {
            $workerAttendances = $attendances->get($worker->id, collect());
            $days       = [];
            $totalHours = 0;

            foreach ($dayList as $i => $dateStr) {
                $dayNum = $i + 1; // 1-based column
                $att    = $workerAttendances->firstWhere('date', $dateStr);

                if ($att && is_numeric($att->hours)) {
                    $days[$dayNum] = $att->hours;
                    $totalHours   += $att->hours;
                } elseif ($att && $att->hours === 'A') {
                    $days[$dayNum] = 'A';
                } else {
                    $days[$dayNum] = '-';
                }
            }

            $reportData[] = [
                'worker'     => $worker,
                'days'       => $days,
                'totalHours' => $totalHours,
            ];
        }

        return [
            'reportData'  => $reportData,
            'daysInMonth' => $daysInMonth,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'dayList'     => $dayList,
        ];
    }

    public function downloadPdf()
    {
        $from = $this->pdfFromDate;
        $to   = $this->pdfToDate;

        $data    = $this->getReportData($from, $to);
        $project = $this->projectId ? Project::find($this->projectId) : null;

        $fromLabel = Carbon::parse($from)->format('d.m.Y');
        $toLabel   = Carbon::parse($to)->format('d.m.Y');
        $dateLabel = $fromLabel . ' – ' . $toLabel;

        $tradeLabel = $this->tradeFilter ? ' [' . $this->tradeFilter . ']' : '';
        $pdfName = 'Attendance_' . Carbon::parse($from)->format('d-m-Y')
                    . '_to_' . Carbon::parse($to)->format('d-m-Y')
                    . ($this->tradeFilter ? '_' . str_replace(' ', '_', $this->tradeFilter) : '') . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.monthly-attendance', [
            'reportData'  => $data['reportData'],
            'daysInMonth' => $data['daysInMonth'],
            'startDate'   => $data['startDate'],
            'endDate'     => $data['endDate'],
            'dayList'     => $data['dayList'],
            'filterMonth' => $this->filterMonth,
            'filterYear'  => $this->filterYear,
            'project'     => $project,
            'dateLabel'   => $dateLabel . $tradeLabel,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $pdfName);
    }

    public function render()
    {
        $projects = Project::orderBy('name')->get();
        $data     = $this->getReportData();

        // All distinct trades for filter dropdown
        $trades = Worker::withoutGlobalScope('orderById')
            ->select('trade')
            ->distinct()
            ->whereNotNull('trade')
            ->orderBy('trade')
            ->pluck('trade');

        return view('livewire.reports.monthly-attendance', [
            'projects'    => $projects,
            'trades'      => $trades,
            'reportData'  => $data['reportData'],
            'daysInMonth' => $data['daysInMonth'],
            'startDate'   => $data['startDate'],
        ]);
    }
}
