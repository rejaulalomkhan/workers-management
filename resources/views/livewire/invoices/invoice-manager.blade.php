<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Invoices</h2>
        <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-3">
            <select wire:model.live="project_id" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <select wire:model.live="month" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border flex-1">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                    @endfor
                </select>
                <select wire:model.live="year" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border flex-1">
                    @for($y=date('Y')-2; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button wire:click="previewInvoice" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center justify-center whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Generate Monthly
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($previewData)
        <div class="bg-white border-2 border-blue-500 rounded-lg p-6 shadow-lg mb-8 relative">
            <button wire:click="$set('previewData', null)" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 border border-gray-300 rounded px-2 py-1 text-xs">Close Preview</button>
            <h3 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Invoice Preview: {{ $previewData['project']->name }}</h3>
            <p class="text-sm text-gray-600 mb-6">Period: {{ \Carbon\Carbon::parse($previewData['period_start'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($previewData['period_end'])->format('d M Y') }}</p>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Quantity (Hours)</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Rate</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($previewData['items'] as $item)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $item['description'] }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-right">{{ $item['quantity'] }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-right">{{ number_format($item['rate'], 2) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-end">
                <div class="w-full sm:w-64 space-y-2 text-sm">
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium text-gray-700">Subtotal:</span>
                        <span class="text-gray-900">{{ number_format($previewData['subtotal'], 2) }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium text-gray-700">VAT ({{ $previewData['vat_rate'] }}%):</span>
                        <span class="text-gray-900">{{ number_format($previewData['vat_amount'], 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="font-bold text-gray-900 text-lg">Total:</span>
                        <span class="font-bold text-gray-900 text-lg">{{ $setting->currency ?? 'AED' }} {{ number_format($previewData['total_amount'], 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button wire:click="generateInvoice" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">
                    Confirm & Save Invoice
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date / Period</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-blue-600">{{ $inv->invoice_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inv->project->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('dM Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($inv->period_start)->format('M y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                {{ number_format($inv->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('invoices.view', $inv->id) }}" target="_blank" class="text-green-600 border border-green-600 hover:bg-green-50 px-3 py-1 rounded inline-flex items-center cursor-pointer mr-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View
                                </a>
                                <button wire:click="downloadPdf({{ $inv->id }})" class="text-blue-600 border border-blue-600 hover:bg-blue-50 px-3 py-1 rounded inline-flex items-center cursor-pointer">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    PDF
                                </button>
                                <button wire:click="delete({{ $inv->id }})" class="text-red-600 hover:text-red-900 ml-3" onclick="confirm('Are you certain? This deletes the invoice forever.') || event.stopImmediatePropagation()">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                No invoices generated yet. Use the tool above to generate an invoice.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
