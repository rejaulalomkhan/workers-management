<?php

namespace App\Livewire\Workers;

use App\Models\Worker;
use Livewire\Component;

class WorkerView extends Component
{
    public Worker $worker;
    
    public $amount;
    public $payment_date;
    public $notes;
    
    public function mount(Worker $worker)
    {
        $this->worker = $worker;
        $this->payment_date = date('Y-m-d');
    }

    public function addPayment()
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $this->worker->payments()->create([
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'notes' => $this->notes,
        ]);

        $this->amount = '';
        $this->notes = '';
        session()->flash('message', 'Payment recorded successfully.');
    }

    public function render()
    {
        $attendances = $this->worker->attendances()->with('project')->orderBy('date', 'desc')->get();
        
        $totalHours = $attendances->filter(function($att) { return is_numeric($att->hours); })->sum('hours');
        
        $totalEarned = 0;
        foreach($attendances as $att) {
            if(is_numeric($att->hours)) {
                $totalEarned += $att->hours * $this->worker->internal_pay_rate; // Using current rate for simplicity
            }
        }
        
        $totalPaid = $this->worker->payments()->sum('amount');
        $dueAmount = $totalEarned - $totalPaid;
        
        $projectsHistory = $attendances->filter(function($att) { return is_numeric($att->hours); })
            ->groupBy('project_id')
            ->map(function ($atts) {
                return [
                    'project_name' => $atts->first()->project->name,
                    'total_hours' => $atts->sum('hours'),
                ];
            });

        return view('livewire.workers.worker-view', [
            'totalHours' => $totalHours,
            'totalEarned' => $totalEarned,
            'totalPaid' => $totalPaid,
            'dueAmount' => $dueAmount,
            'attendances' => $attendances,
            'projectsHistory' => $projectsHistory,
            'payments' => $this->worker->payments()->latest('payment_date')->get()
        ]);
    }
}
