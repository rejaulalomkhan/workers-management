<?php

namespace App\Livewire\Workers;

use Livewire\Component;
use App\Models\Worker;
use App\Models\WorkerRate;
use Carbon\Carbon;
use Livewire\WithPagination;

class WorkerManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $worker_id, $name, $worker_id_number, $trade, $internal_pay_rate;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $workers = Worker::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('worker_id_number', 'like', '%' . $this->search . '%')
            ->orWhere('trade', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(15);
            
        return view('livewire.workers.worker-manager', ['workers' => $workers]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->worker_id = '';
        $this->name = '';
        $this->worker_id_number = '';
        $this->trade = '';
        $this->internal_pay_rate = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'trade' => 'required',
            'internal_pay_rate' => 'required|numeric',
            'worker_id_number' => 'nullable|unique:workers,worker_id_number,' . $this->worker_id,
        ]);

        $worker = Worker::find($this->worker_id);

        if ($worker) {
            if ($worker->internal_pay_rate != $this->internal_pay_rate) {
                WorkerRate::create([
                    'worker_id' => $worker->id,
                    'rate' => $this->internal_pay_rate,
                    'effective_from' => Carbon::now()->toDateString()
                ]);
            }
            $worker->update([
                'name' => $this->name,
                'worker_id_number' => $this->worker_id_number,
                'trade' => $this->trade,
                'internal_pay_rate' => $this->internal_pay_rate,
            ]);
        } else {
            $worker = Worker::create([
                'name' => $this->name,
                'worker_id_number' => $this->worker_id_number,
                'trade' => $this->trade,
                'internal_pay_rate' => $this->internal_pay_rate,
            ]);
            WorkerRate::create([
                'worker_id' => $worker->id,
                'rate' => $this->internal_pay_rate,
                'effective_from' => Carbon::now()->toDateString()
            ]);
        }

        session()->flash('message', $this->worker_id ? 'Worker Updated Successfully.' : 'Worker Created Successfully.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $worker = Worker::findOrFail($id);
        $this->worker_id = $id;
        $this->name = $worker->name;
        $this->worker_id_number = $worker->worker_id_number;
        $this->trade = $worker->trade;
        $this->internal_pay_rate = $worker->internal_pay_rate;
        $this->openModal();
    }

    public function delete($id)
    {
        Worker::find($id)->delete();
        session()->flash('message', 'Worker Deleted Successfully.');
    }
}
