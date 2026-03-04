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
    public $tradeFilter = '';
    public $isModalOpen = false;
    public $worker_id, $name, $worker_id_number, $trade, $internal_pay_rate;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function filterByTrade(string $trade)
    {
        $this->tradeFilter = $trade;
        $this->resetPage();
    }

    public function resetTradeFilter()
    {
        $this->tradeFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Worker::query();

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('worker_id_number', 'like', '%' . $this->search . '%')
                  ->orWhere('trade', 'like', '%' . $this->search . '%');
            });
        }

        // Trade filter
        if ($this->tradeFilter) {
            $query->where('trade', $this->tradeFilter);
        }

        $workers = $query->orderBy('name', 'asc')->paginate(15);

        // Trade stats: all distinct trades with counts
        $tradeStats = Worker::selectRaw('trade, count(*) as total')
            ->whereNotNull('trade')
            ->groupBy('trade')
            ->orderBy('trade')
            ->get();

        $totalWorkers = Worker::count();

        // Distinct trades for datalist suggestions
        $tradeSuggestions = $tradeStats->pluck('trade')
            ->merge(['HELPER', 'MASON'])
            ->unique()->sort()->values();

        return view('livewire.workers.worker-manager', [
            'workers'          => $workers,
            'tradeSuggestions' => $tradeSuggestions,
            'tradeStats'       => $tradeStats,
            'totalWorkers'     => $totalWorkers,
        ]);
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
        // Normalise: treat blank string as null so multiple workers can have no ID
        $this->worker_id_number = trim($this->worker_id_number) !== '' ? trim($this->worker_id_number) : null;

        $this->validate([
            'name'               => 'required',
            'trade'              => 'required',
            'internal_pay_rate'  => 'required|numeric',
            'worker_id_number'   => [
                'nullable',
                $this->worker_id_number
                    ? 'unique:workers,worker_id_number,' . $this->worker_id
                    : '',
            ],
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
                'name'               => $this->name,
                'worker_id_number'   => $this->worker_id_number ?: null,
                'trade'              => $this->trade,
                'internal_pay_rate'  => $this->internal_pay_rate,
            ]);
        } else {
            $worker = Worker::create([
                'name'               => $this->name,
                'worker_id_number'   => $this->worker_id_number ?: null,
                'trade'              => $this->trade,
                'internal_pay_rate'  => $this->internal_pay_rate,
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
