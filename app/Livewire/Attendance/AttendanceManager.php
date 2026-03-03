<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Project;
use App\Models\Worker;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceManager extends Component
{
    public $project_id;
    public $month;
    public $year;
    
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
        if (in_array($propertyName, ['project_id', 'month', 'year'])) {
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

    public function render()
    {
        $workers = Worker::where('is_active', true)->orderBy('name')->get();

        return view('livewire.attendance.attendance-manager', [
            'workers' => $workers
        ]);
    }
}
