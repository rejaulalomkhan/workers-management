<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Setting;
use App\Models\Attendance;
use App\Models\Worker;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceManager extends Component
{
    public $project_id;
    public $month;
    public $year;
    
    public $projects;
    public $invoices;
    public $setting;

    public $previewData = null;

    public function mount()
    {
        $this->projects = Project::orderBy('name')->get();
        if ($this->projects->count() > 0) {
            $this->project_id = $this->projects->first()->id;
        }
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->setting = Setting::first();
        
        $this->loadInvoices();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['project_id', 'month', 'year'])) {
            $this->loadInvoices();
            $this->previewData = null; // reset preview
        }
    }

    public function loadInvoices()
    {
        $this->invoices = Invoice::with('items', 'project')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function previewInvoice()
    {
        if (!$this->project_id) {
            session()->flash('error', 'Select a project first.');
            return;
        }

        $project = Project::with('categories')->find($this->project_id);
        
        // Compute hours per trade for this project in the selected month
        $attendances = Attendance::where('project_id', $this->project_id)
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get();

        $trades = [];
        
        foreach ($attendances as $att) {
            if (is_numeric($att->hours) && $att->hours > 0) {
                $worker = Worker::find($att->worker_id);
                if ($worker) {
                    $trade = $worker->trade;
                    if (!isset($trades[$trade])) {
                        $trades[$trade] = 0;
                    }
                    $trades[$trade] += (float)$att->hours;
                }
            }
        }

        $items = [];
        $subtotal = 0;

        foreach ($trades as $trade => $total_hours) {
            // Find rate in project categories (case-insensitive match)
            $cat = $project->categories->first(fn($c) => strtolower(trim($c->name)) === strtolower(trim($trade)));
            $rate = $cat ? $cat->billing_rate : 0;
            $amount = $rate * $total_hours;
            $subtotal += $amount;

            $items[] = [
                'description' => "Labor Supply: " . $trade,
                'quantity' => $total_hours,
                'rate' => $rate,
                'amount' => $amount
            ];
        }

        $vat_rate = $this->setting->vat_rate ?? 5;
        $vat_amount = $subtotal * ($vat_rate / 100);
        $total_amount = $subtotal + $vat_amount;

        $startDate = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

        $this->previewData = [
            'project' => $project,
            'items' => $items,
            'subtotal' => $subtotal,
            'vat_rate' => $vat_rate,
            'vat_amount' => $vat_amount,
            'total_amount' => $total_amount,
            'period_start' => $startDate->format('Y-m-d'),
            'period_end' => $endDate->format('Y-m-d'),
        ];
    }

    public function generateInvoice()
    {
        if (!$this->previewData) {
            $this->previewInvoice();
        }

        if (empty($this->previewData['items'])) {
            session()->flash('error', 'No attendance data found for this period to generate invoice.');
            return;
        }

        $project = Project::find($this->project_id);
        
        // Generate prefix
        $prefix = "FAZ-" . date('ym');
        $lastInvoice = Invoice::latest()->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $invoiceNumber = $prefix . "-" . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'project_id' => $project->id,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'),
            'period_start' => $this->previewData['period_start'],
            'period_end' => $this->previewData['period_end'],
            'subtotal' => $this->previewData['subtotal'],
            'vat_amount' => $this->previewData['vat_amount'],
            'total_amount' => $this->previewData['total_amount'],
            'notes' => 'Generated automatically from attendance records.',
        ]);

        foreach ($this->previewData['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'amount' => $item['amount'],
            ]);
        }

        session()->flash('message', 'Invoice Generated Successfully: ' . $invoiceNumber);
        $this->previewData = null;
        $this->loadInvoices();
    }

    public function downloadPdf($invoiceId)
    {
        $invoice = Invoice::with('items', 'project')->findOrFail($invoiceId);
        $setting = Setting::first();

        $pdf = Pdf::loadView('pdfs.invoice', [
            'invoice' => $invoice,
            'setting' => $setting,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Invoice_' . $invoice->invoice_number . '.pdf');
    }

    public function delete($id)
    {
        Invoice::find($id)->delete();
        $this->loadInvoices();
        session()->flash('message', 'Invoice Deleted Successfully.');
    }

    public function render()
    {
        return view('livewire.invoices.invoice-manager');
    }
}
