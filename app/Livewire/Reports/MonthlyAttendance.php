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
    public $projectId = '';

    public function mount()
    {
        $this->filterMonth = date('n');
        $this->filterYear = date('Y');
    }

    public function getReportData()
    {
        $startDate = Carbon::createFromDate($this->filterYear, $this->filterMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $query = Worker::query();
        if ($this->projectId) {
            $query->whereHas('attendances', function ($q) use ($startDate, $endDate) {
                $q->where('project_id', $this->projectId)
                  ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            });
        }

        $workers = $query->orderBy('name')->get();

        $attendances = Attendance::whereIn('worker_id', $workers->pluck('id'))
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            
        if ($this->projectId) {
            $attendances->where('project_id', $this->projectId);
        }
        
        $attendances = $attendances->get()->groupBy('worker_id');

        $reportData = [];
        foreach ($workers as $worker) {
            $workerAttendances = $attendances->get($worker->id, collect());
            $days = [];
            $totalHours = 0;
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = $startDate->copy()->addDays($day - 1)->format('Y-m-d');
                $att = $workerAttendances->firstWhere('date', $currentDate);
                
                if ($att && is_numeric($att->hours)) {
                    $days[$day] = $att->hours;
                    $totalHours += $att->hours;
                } elseif ($att && $att->hours === 'A') {
                    $days[$day] = 'A';
                } else {
                    $days[$day] = '-';
                }
            }
            
            $reportData[] = [
                'worker' => $worker,
                'days' => $days,
                'totalHours' => $totalHours,
            ];
        }

        return [
            'reportData' => $reportData,
            'daysInMonth' => $daysInMonth,
            'startDate' => $startDate,
        ];
    }

    public function downloadPdf()
    {
        $data = $this->getReportData();
        $project = $this->projectId ? Project::find($this->projectId) : null;
        
        $pdfName = 'Monthly_Attendance_' . date('F_Y', mktime(0,0,0,$this->filterMonth, 1, $this->filterYear)) . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.monthly-attendance', [
            'reportData' => $data['reportData'],
            'daysInMonth' => $data['daysInMonth'],
            'startDate' => $data['startDate'],
            'filterMonth' => $this->filterMonth,
            'filterYear' => $this->filterYear,
            'project' => $project,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $pdfName);
    }

    public function render()
    {
        $projects = Project::orderBy('name')->get();
        $data = $this->getReportData();

        return view('livewire.reports.monthly-attendance', [
            'projects' => $projects,
            'reportData' => $data['reportData'],
            'daysInMonth' => $data['daysInMonth'],
            'startDate' => $data['startDate'],
        ]);
    }
}
