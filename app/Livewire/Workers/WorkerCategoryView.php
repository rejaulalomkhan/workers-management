<?php

namespace App\Livewire\Workers;

use App\Models\WorkerCategory;
use Livewire\Component;

class WorkerCategoryView extends Component
{
    public WorkerCategory $category;

    public function mount(WorkerCategory $category)
    {
        $this->category = $category;
    }

    public function render()
    {
        // Load workers associated with this category
        $workers = $this->category->workers()->orderBy('name')->get();
        
        // Calculate dynamic stats for top cards
        $totalWorkers = $workers->count();
        $activeWorkers = $workers->where('is_active', true)->count();
        $averageRate = $totalWorkers > 0 ? $workers->avg('internal_pay_rate') : 0;
        
        return view('livewire.workers.worker-category-view', [
            'workers' => $workers,
            'totalWorkers' => $totalWorkers,
            'activeWorkers' => $activeWorkers,
            'averageRate' => $averageRate,
        ]);
    }
}
