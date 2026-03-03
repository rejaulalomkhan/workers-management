<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\Worker;
use App\Models\Attendance;
use Livewire\Component;
use Illuminate\Support\Carbon;

class ProjectView extends Component
{
    public Project $project;
    public $filterMonth;
    public $filterYear;

    public $todayDate;
    
    // Quick Attendance state
    public $todayAttendances = []; // [worker_id => hours/status]
    public $newWorkerId = '';
    
    // Manage temporary added workers for the current session that don't have DB rows yet
    public $temporaryWorkersForToday = [];
    
    // Manage permanent workers
    public $newPermanentWorkerId = '';

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->filterMonth = date('n');
        $this->filterYear = date('Y');
        $this->todayDate = date('Y-m-d');
        
        $this->loadTodayAttendances();
    }
    
    public function updatedTodayDate()
    {
        $this->temporaryWorkersForToday = []; // Reset temporary workers when switching dates
        $this->loadTodayAttendances();
    }
    
    public function loadTodayAttendances()
    {
        $existing = Attendance::where('project_id', $this->project->id)
            ->where('date', $this->todayDate)
            ->get()->keyBy('worker_id');
            
        // We want to show:
        // 1. Permanent workers for this project
        // 2. Workers who already have attendance records on this date
        // 3. Workers temporarily added via dropdown for this date session
        
        $permanentWorkerIds = $this->project->workers()->pluck('workers.id')->toArray();
        $existingWorkerIds = $existing->keys()->toArray();
        
        $workerIds = array_unique(array_merge($permanentWorkerIds, $existingWorkerIds, $this->temporaryWorkersForToday));
        $workers = Worker::whereIn('id', $workerIds)->get();
        
        $this->todayAttendances = []; // reset state array
        
        foreach($workers as $w) {
            if(isset($existing[$w->id])) {
                $this->todayAttendances[$w->id] = $existing[$w->id]->hours;
            } else {
                $this->todayAttendances[$w->id] = '';
            }
        }
    }
    
    // MANAGE TODAY'S TAB
    public function addNewWorkerToProject()
    {
        if ($this->newWorkerId && !in_array($this->newWorkerId, $this->temporaryWorkersForToday)) {
            $this->temporaryWorkersForToday[] = $this->newWorkerId;
            $this->loadTodayAttendances();
        }
        $this->newWorkerId = '';
    }
    
    public function removeWorkerFromToday($workerId)
    {
        // 1. Remove from temporary
        if (($key = array_search($workerId, $this->temporaryWorkersForToday)) !== false) {
            unset($this->temporaryWorkersForToday[$key]);
        }
        
        // 2. Delete database record for this specific day
        Attendance::where('worker_id', $workerId)
            ->where('project_id', $this->project->id)
            ->where('date', $this->todayDate)
            ->delete();
            
        $this->loadTodayAttendances();
        session()->flash('message', 'Worker removed from today\'s attendance record.');
    }
    
    public function saveTodayAttendance()
    {
        foreach($this->todayAttendances as $worker_id => $val) {
            $val = trim((string)$val);
            if($val === '') {
                $val = 'A';
            }
            
            // Check if worker valid
            $worker = Worker::find($worker_id);
            if(!$worker) continue;
            
            Attendance::updateOrCreate(
                [
                    'worker_id' => $worker_id,
                    'project_id' => $this->project->id,
                    'date' => $this->todayDate,
                ],
                [
                    'hours' => strtoupper(trim((string)$val)),
                ]
            );
        }
        
        session()->flash('message', 'Attendance for ' . Carbon::parse($this->todayDate)->format('M d, Y') . ' recorded successfully.');
        $this->loadTodayAttendances();
    }
    
    // MANAGE PERMANENT WORKERS TAB
    public function assignPermanentWorker()
    {
        if ($this->newPermanentWorkerId) {
            $this->project->workers()->syncWithoutDetaching([$this->newPermanentWorkerId]);
            $this->newPermanentWorkerId = '';
            session()->flash('permanent_message', 'Worker assigned permanently to this project.');
            $this->loadTodayAttendances(); // Refresh today's grid
        }
    }
    
    public function removePermanentWorker($workerId)
    {
        $this->project->workers()->detach($workerId);
        session()->flash('permanent_message', 'Worker removed from permanent list.');
        $this->loadTodayAttendances(); // Refresh today's grid
    }

    public function render()
    {
        $categories = $this->project->categories->keyBy('name');
        
        $attendances = Attendance::with('worker')->where('project_id', $this->project->id)->get();
        
        $totalHours = 0;
        $totalReceivable = 0;
        
        $monthHours = 0;
        $monthReceivable = 0;
        
        $currentMonthWorkers = [];
        $dateWiseWorkers = [];
        $uniqueWorkers = [];

        foreach($attendances as $att) {
            if(!is_numeric($att->hours)) continue;
            
            $worker = $att->worker;
            $uniqueWorkers[$worker->id] = $worker;
            
            $rate = 0;
            if(isset($categories[$worker->trade])) {
                $rate = $categories[$worker->trade]->billing_rate;
            }
            
            $totalHours += $att->hours;
            $totalReceivable += ($att->hours * $rate);
            
            $attDate = Carbon::parse($att->date);
            if($attDate->month == $this->filterMonth && $attDate->year == $this->filterYear) {
                $monthHours += $att->hours;
                $monthReceivable += ($att->hours * $rate);
                
                if(!isset($currentMonthWorkers[$worker->id])) {
                    $currentMonthWorkers[$worker->id] = [
                        'worker' => $worker,
                        'hours' => 0,
                        'amount' => 0,
                        'rate' => $rate
                    ];
                }
                $currentMonthWorkers[$worker->id]['hours'] += $att->hours;
                $currentMonthWorkers[$worker->id]['amount'] += ($att->hours * $rate);
                
                $dateStr = $attDate->format('Y-m-d');
                if(!isset($dateWiseWorkers[$dateStr])) {
                    $dateWiseWorkers[$dateStr] = [
                        'date_display' => $attDate->format('d M, Y'),
                        'total_workers' => 0,
                        'total_hours' => 0,
                        'workers' => []
                    ];
                }
                $dateWiseWorkers[$dateStr]['total_workers'] += 1;
                $dateWiseWorkers[$dateStr]['total_hours'] += $att->hours;
                $dateWiseWorkers[$dateStr]['workers'][] = [
                    'worker' => $worker,
                    'hours' => $att->hours,
                    'amount' => ($att->hours * $rate)
                ];
            }
        }
        
        // Sort descending by date
        krsort($dateWiseWorkers);
        
        $availableWorkersForTodaySelect = Worker::whereNotIn('id', array_keys($this->todayAttendances))->get();
        
        $permanentWorkers = $this->project->workers;
        $availableWorkersForPermanentSelect = Worker::whereNotIn('id', $permanentWorkers->pluck('id')->toArray())->get();

        return view('livewire.projects.project-view', [
            'totalHours' => $totalHours,
            'totalReceivable' => $totalReceivable,
            'monthHours' => $monthHours,
            'monthReceivable' => $monthReceivable,
            'currentMonthWorkers' => $currentMonthWorkers,
            'dateWiseWorkers' => $dateWiseWorkers,
            'allWorkers' => collect($uniqueWorkers)->values(),
            
            'todaysWorkersList' => Worker::whereIn('id', array_keys($this->todayAttendances))->orderBy('name')->get()->keyBy('id'),
            'availableWorkersForTodaySelect' => collect($availableWorkersForTodaySelect),
            
            'permanentWorkers' => $permanentWorkers,
            'availableWorkersForPermanentSelect' => collect($availableWorkersForPermanentSelect),
        ]);
    }
}
