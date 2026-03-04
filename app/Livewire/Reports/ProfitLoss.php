<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Project;
use App\Models\Worker;
use App\Models\Attendance;
use App\Models\ProjectCategory;
use Carbon\Carbon;

class ProfitLoss extends Component
{
    public $month;
    public $year;
    public $selectedProject = 'all';
    public $activeTab = 'summary'; // summary | project | worker | category

    public $projects = [];
    public $summaryData = [];
    public $projectData = [];
    public $workerData = [];
    public $categoryData = [];

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year  = Carbon::now()->year;
        $this->projects = Project::orderBy('name')->get();
        $this->generateReport();
    }

    public function updated($property)
    {
        if (in_array($property, ['month', 'year', 'selectedProject', 'activeTab'])) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $query = Attendance::with(['worker', 'project.categories'])
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month);

        if ($this->selectedProject !== 'all') {
            $query->where('project_id', $this->selectedProject);
        }

        $attendances = $query->get();

        // ---- Summary ----
        $totalRevenue = 0;
        $totalCost    = 0;
        $totalHours   = 0;

        // ---- Project breakdown ----
        $projectMap = [];

        // ---- Worker breakdown ----
        $workerMap = [];

        // ---- Category breakdown ----
        $categoryMap = [];

        foreach ($attendances as $att) {
            if (!is_numeric($att->hours) || $att->hours <= 0) continue;

            $hours  = (float) $att->hours;
            $worker = $att->worker;
            if (!$worker) continue;

            $project  = $att->project;
            $trade    = $worker->trade;

            // Find billing rate from project categories matching worker's trade (case-insensitive)
            $billingRate = 0;
            if ($project) {
                $cat = $project->categories->first(fn($c) => strtolower(trim($c->name)) === strtolower(trim($trade)));
                $billingRate = $cat ? (float) $cat->billing_rate : 0;
            }

            $payRate = (float) ($worker->internal_pay_rate ?? 0);
            $revenue = $billingRate * $hours;
            $cost    = $payRate    * $hours;
            $profit  = $revenue - $cost;

            // Summary
            $totalRevenue += $revenue;
            $totalCost    += $cost;
            $totalHours   += $hours;

            // --- Project ---
            $pid = $project ? $project->id : 0;
            $pname = $project ? $project->name : 'Unknown';
            if (!isset($projectMap[$pid])) {
                $projectMap[$pid] = ['name' => $pname, 'revenue' => 0, 'cost' => 0, 'hours' => 0];
            }
            $projectMap[$pid]['revenue'] += $revenue;
            $projectMap[$pid]['cost']    += $cost;
            $projectMap[$pid]['hours']   += $hours;

            // --- Worker ---
            $wid = $worker->id;
            if (!isset($workerMap[$wid])) {
                $workerMap[$wid] = [
                    'name'       => $worker->name,
                    'trade'      => $worker->trade,
                    'pay_rate'   => $payRate,
                    'bill_rate'  => $billingRate,
                    'revenue'    => 0,
                    'cost'       => 0,
                    'hours'      => 0,
                ];
            }
            $workerMap[$wid]['revenue'] += $revenue;
            $workerMap[$wid]['cost']    += $cost;
            $workerMap[$wid]['hours']   += $hours;

            // --- Category (Trade) ---
            if (!isset($categoryMap[$trade])) {
                $categoryMap[$trade] = ['name' => $trade, 'revenue' => 0, 'cost' => 0, 'hours' => 0];
            }
            $categoryMap[$trade]['revenue'] += $revenue;
            $categoryMap[$trade]['cost']    += $cost;
            $categoryMap[$trade]['hours']   += $hours;
        }

        $totalProfit = $totalRevenue - $totalCost;
        $margin = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;

        $this->summaryData = compact('totalRevenue', 'totalCost', 'totalProfit', 'totalHours', 'margin');
        $this->projectData  = collect($projectMap)->map(fn($p) => array_merge($p, ['profit' => $p['revenue'] - $p['cost'], 'margin' => $p['revenue'] > 0 ? round(($p['revenue'] - $p['cost']) / $p['revenue'] * 100, 1) : 0]))->sortByDesc('revenue')->values()->toArray();
        $this->workerData   = collect($workerMap)->map(fn($w) => array_merge($w, ['profit' => $w['revenue'] - $w['cost'], 'margin' => $w['revenue'] > 0 ? round(($w['revenue'] - $w['cost']) / $w['revenue'] * 100, 1) : 0]))->sortByDesc('hours')->values()->toArray();
        $this->categoryData = collect($categoryMap)->map(fn($c) => array_merge($c, ['profit' => $c['revenue'] - $c['cost'], 'margin' => $c['revenue'] > 0 ? round(($c['revenue'] - $c['cost']) / $c['revenue'] * 100, 1) : 0]))->sortByDesc('revenue')->values()->toArray();
    }

    public function render()
    {
        return view('livewire.reports.profit-loss');
    }
}
