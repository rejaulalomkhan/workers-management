<?php

namespace App\Livewire\Workers;

use Livewire\Component;
use App\Models\WorkerCategory;

class WorkerCategoryManager extends Component
{
    public $categories;
    public $isModalOpen = false;
    public $category_id, $name, $status = true;

    public function render()
    {
        $this->categories = WorkerCategory::withCount('workers')->get();
        return view('livewire.workers.worker-category-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->category_id = null;
        $this->name = '';
        $this->status = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|unique:worker_categories,name,' . $this->category_id,
            'status' => 'boolean',
        ]);

        $category = WorkerCategory::updateOrCreate(['id' => $this->category_id], [
            'name' => $this->name,
            'status' => $this->status,
        ]);

        // Keep workers' legacy 'trade' field in sync automatically 
        // to ensure old reports/PDFs still work perfectly.
        \App\Models\Worker::where('worker_category_id', $category->id)->update([
            'trade' => $category->name
        ]);

        session()->flash('message', $this->category_id ? 'Category updated successfully.' : 'Category created successfully.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = WorkerCategory::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->status = (bool) $category->status;
        $this->openModal();
    }

    public function delete($id)
    {
        WorkerCategory::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }
}
