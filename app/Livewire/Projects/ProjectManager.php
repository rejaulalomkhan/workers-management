<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectCategory;

class ProjectManager extends Component
{
    public $projects;
    public $isModalOpen = false;
    public $project_id, $name, $location, $customer_name, $customer_address, $customer_trn;
    public $categories = [];
    
    public function render()
    {
        $this->projects = Project::with('categories')->orderBy('created_at', 'desc')->get();
        return view('livewire.projects.project-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->categories = [['name' => '', 'billing_rate' => '']];
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
        $this->project_id = '';
        $this->name = '';
        $this->location = '';
        $this->customer_name = '';
        $this->customer_address = '';
        $this->customer_trn = '';
        $this->categories = [];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'categories.*.name' => 'required',
            'categories.*.billing_rate' => 'required|numeric',
        ]);

        $project = Project::updateOrCreate(['id' => $this->project_id ? $this->project_id : null], [
            'name' => $this->name,
            'location' => $this->location,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
            'customer_trn' => $this->customer_trn,
        ]);

        $project->categories()->delete();
        foreach ($this->categories as $cat) {
            if(!empty($cat['name'])){
                $project->categories()->create([
                    'name' => $cat['name'],
                    'billing_rate' => $cat['billing_rate']
                ]);
            }
        }

        session()->flash('message', $this->project_id ? 'Project updated completely.' : 'Project created completely.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $project = Project::with('categories')->findOrFail($id);
        $this->project_id = $id;
        $this->name = $project->name;
        $this->location = $project->location;
        $this->customer_name = $project->customer_name;
        $this->customer_address = $project->customer_address;
        $this->customer_trn = $project->customer_trn;
        $this->categories = $project->categories->map(function($c) {
            return ['name'=> $c->name, 'billing_rate'=> $c->billing_rate];
        })->toArray();
        if (empty($this->categories)) {
            $this->categories = [['name' => '', 'billing_rate' => '']];
        }
        $this->openModal();
    }

    public function delete($id)
    {
        Project::find($id)->delete();
        session()->flash('message', 'Project deleted successfully.');
    }

    public function addCategory()
    {
        $this->categories[] = ['name' => '', 'billing_rate' => ''];
    }

    public function removeCategory($index)
    {
        unset($this->categories[$index]);
        $this->categories = array_values($this->categories);
    }
}
