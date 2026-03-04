<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Project;
use App\Models\Worker;
use App\Models\Attendance;
use Carbon\Carbon;

use Livewire\Attributes\Lazy;

#[Lazy]
class AttendanceManager extends Component
{
    public $project_id;
    public $month;
    public $year;
    public $workerFilter = 'all';   // 'all' or worker id
    public $savedCell = null;       // 'workerId.day' — used to flash green checkmark
    
    public $projects;
    public $daysInMonth;
    public $attendances = [];

    public function mount()
    {
        $this->projects = Project::orderBy('name')->get();
        if ($this->projects->count() > 0) {
            $this->project_id = $this->projects->first()->id;
        }
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        
        $this->loadData();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['project_id', 'month', 'year', 'workerFilter'])) {
            $this->loadData();
        }
    }

    public function updatedAttendances($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) === 2) {
            $worker_id = $parts[0];
            $day = $parts[1];
            $this->saveDay($worker_id, $day, $value);
            $this->savedCell = $worker_id . '.' . $day;
            // Clear the flash indicator after 2 seconds via JS dispatch
            $this->dispatch('cell-saved', cell: $this->savedCell);
        }
    }

    private function saveDay($worker_id, $day, $hours)
    {
        if (!$this->project_id || !$this->month || !$this->year) return;
        
        $date = Carbon::createFromDate($this->year, $this->month, $day)->format('Y-m-d');
        
        $hours = trim($hours);
        if ($hours === '') {
            $hours = 'A';
        }

        Attendance::updateOrCreate(
            [
                'worker_id' => $worker_id,
                'project_id' => $this->project_id,
                'date' => $date
            ],
            ['hours' => strtoupper($hours)]
        );
    }

    public function fillAllPresent($worker_id, $hours = 8)
    {
        if (!$this->project_id || !$this->month || !$this->year || !$this->daysInMonth) return;

        for ($day = 1; $day <= $this->daysInMonth; $day++) {
            $existing = $this->attendances[$worker_id][$day] ?? '';
            // Only fill empty cells — don't overwrite existing entries
            if ($existing === '' || $existing === null) {
                $this->saveDay($worker_id, $day, (string)$hours);
                $this->attendances[$worker_id][$day] = (string)$hours;
            }
        }
        session()->flash('bulk_saved_' . $worker_id, true);
    }

    public function clearWorker($worker_id)
    {
        if (!$this->project_id || !$this->month || !$this->year) return;

        Attendance::where('worker_id', $worker_id)
            ->where('project_id', $this->project_id)
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->delete();

        $this->attendances[$worker_id] = [];
    }

    public function loadData()
    {
        $this->attendances = [];
        if (!$this->project_id || !$this->month || !$this->year) return;

        $this->daysInMonth = Carbon::createFromDate($this->year, $this->month, 1)->daysInMonth;
        
        $records = Attendance::where('project_id', $this->project_id)
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get();
            
        foreach ($records as $record) {
            $day = (int)Carbon::parse($record->date)->day;
            $this->attendances[$record->worker_id][$day] = $record->hours;
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="space-y-5">
            {{-- Header skeleton --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <div class="h-8 w-52 bg-gray-200 rounded-lg animate-pulse mb-2"></div>
                    <div class="h-3 w-40 bg-gray-100 rounded animate-pulse"></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <div class="h-9 w-48 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="h-9 w-20 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="h-9 w-20 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="h-9 w-32 bg-gray-100 rounded-lg animate-pulse"></div>
                </div>
            </div>
            {{-- Table skeleton --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="h-5 w-64 bg-gray-200 rounded animate-pulse"></div>
                </div>
                <div class="p-4 space-y-3">
                    @for($i = 0; $i < 8; $i++)
                    <div class="flex gap-2">
                        <div class="h-8 w-36 bg-gray-200 rounded animate-pulse flex-shrink-0"></div>
                        @for($j = 0; $j < 10; $j++)
                        <div class="h-8 w-10 bg-gray-100 rounded animate-pulse"></div>
                        @endfor
                    </div>
                    @endfor
                </div>
            </div>
            {{-- Loading label --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-400 py-2">
                <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Loading attendance data...
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        $query = Worker::where('is_active', true)->orderBy('name');
        if ($this->workerFilter !== 'all') {
            $query->where('id', $this->workerFilter);
        }
        $workers = $query->get();
        $allWorkers = Worker::where('is_active', true)->orderBy('name')->get();

        return view('livewire.attendance.attendance-manager', [
            'workers'    => $workers,
            'allWorkers' => $allWorkers,
        ]);
    }
}
