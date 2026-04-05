<div class="space-y-6" x-data="{ tab: 'overview' }">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="/workers" class="p-2 bg-white rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Worker Details</h2>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Profile -->
        <div class="w-full lg:w-1/3 xl:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 h-24"></div>
                <div class="flex justify-center -mt-12">
                    <div class="w-24 h-24 bg-white rounded-full border-4 border-white flex items-center justify-center shadow-md overflow-hidden text-blue-600 text-3xl font-bold bg-blue-50">
                        {{ substr($worker->name, 0, 1) }}
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900">{{ $worker->name }}</h3>
                    <p class="text-sm text-gray-500 mb-2">{{ $worker->worker_id_number ?? 'No ID' }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 tracking-wide mb-4">
                        {{ $worker->trade }}
                    </span>
                    
                    <div class="space-y-3 text-left mt-4 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Pay Rate</span>
                            <span class="font-bold text-gray-900">{{ number_format($worker->internal_pay_rate, 2) }} /hr</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="font-bold text-green-600">Active</span>
                        </div>
                    </div>

                    <div class="mt-6 bg-red-50 p-4 rounded-lg border border-red-100">
                        <div class="text-sm text-red-500 font-semibold mb-1">Total Due Balance</div>
                        <div class="text-2xl font-bold text-red-700">{{ number_format($dueAmount, 2) }} <span class="text-sm font-normal">{{ \App\Models\Setting::first()->currency ?? 'AED' }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="w-full lg:w-2/3 xl:w-3/4 space-y-6">
            
            <!-- Navigation Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-1 flex overflow-x-auto gap-1">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'" class="shrink-0 px-4 sm:px-6 py-2.5 rounded-md text-sm transition whitespace-nowrap">Overview</button>
                <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'" class="shrink-0 px-4 sm:px-6 py-2.5 rounded-md text-sm transition whitespace-nowrap">Attendance History</button>
                <button @click="tab = 'payments'" :class="tab === 'payments' ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'" class="shrink-0 px-4 sm:px-6 py-2.5 rounded-md text-sm transition whitespace-nowrap">Payments</button>
            </div>

            <!-- TAB: Overview -->
            <div x-show="tab === 'overview'" x-transition.opacity>
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Hours</p>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $totalHours }} <span class="text-sm font-normal text-gray-400">hr</span></h4>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Earned</p>
                            <h4 class="text-2xl font-bold text-gray-900">{{ number_format($totalEarned, 2) }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Paid</p>
                            <h4 class="text-2xl font-bold text-gray-900">{{ number_format($totalPaid, 2) }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Projects History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Projects Worked On</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-3 font-medium">Project Name</th>
                                    <th class="px-6 py-3 font-medium text-right">Total Hours Logged</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($projectsHistory as $ph)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $ph['project_name'] }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-700">{{ $ph['total_hours'] }} hr</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-6 py-8 text-center text-gray-500">No project data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB: Attendance History -->
            <div x-show="tab === 'attendance'" style="display: none;" x-transition.opacity>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Recent Attendance</h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px]">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 bg-white shadow-sm z-10">
                                <tr class="text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-3 font-medium">Date</th>
                                    <th class="px-6 py-3 font-medium">Project</th>
                                    <th class="px-6 py-3 font-medium text-center">Status</th>
                                    <th class="px-6 py-3 font-medium text-right">Hours</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($attendances as $att)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($att->date)->format('d-m-Y') }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-600 truncate max-w-[200px]">{{ $att->project->name }}</td>
                                        <td class="px-6 py-3 text-center">
                                            @if(is_numeric($att->hours))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Present</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Absent</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-right font-bold {{ is_numeric($att->hours) ? 'text-gray-900' : 'text-gray-400' }}">
                                            {{ is_numeric($att->hours) ? $att->hours : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No attendance records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB: Payments -->
            <div x-show="tab === 'payments'" style="display: none;" x-transition.opacity>
                
                <!-- Add Payment Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-bold text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Record New Payment
                        </h3>
                    </div>
                    <form wire:submit="addPayment" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amount ({{ \App\Models\Setting::first()->currency ?? 'AED' }}) *</label>
                                <input type="number" step="0.01" wire:model="amount" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" required>
                                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                                <div class="relative" x-data="{ 
                                    date: @entangle('payment_date'),
                                    get fmt() {
                                        if(!this.date) return '';
                                        let parts = this.date.split('-');
                                        if(parts.length < 3) return this.date;
                                        return `${parts[2]}-${parts[1]}-${parts[0]}`;
                                    }
                                }">
                                    <div class="w-full rounded-md border border-gray-300 shadow-sm text-sm p-2 bg-white flex justify-between items-center h-[38px]">
                                        <span x-text="fmt" class="text-gray-700 font-medium"></span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="date" wire:model="payment_date" x-model="date" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer" onclick="this.showPicker()" required>
                                </div>
                                @error('payment_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes / Reference</label>
                            <input type="text" wire:model="notes" placeholder="e.g. Bank Transfer TXN-123" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md shadow flex items-center transition">
                                <span wire:loading.remove wire:target="addPayment">Save Payment</span>
                                <span wire:loading wire:target="addPayment">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Transaction History</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-3 font-medium">Date</th>
                                    <th class="px-6 py-3 font-medium">Notes</th>
                                    <th class="px-6 py-3 font-medium text-right">Amount ({{ \App\Models\Setting::first()->currency ?? 'AED' }})</th>
                                    <th class="px-6 py-3 font-medium text-center">Slip</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($payments as $payment)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->notes ?? '-' }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-green-600">+ {{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="/payment-receipt/{{ $worker->id }}/{{ \Carbon\Carbon::parse($payment->payment_date)->month }}/{{ \Carbon\Carbon::parse($payment->payment_date)->year }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1 text-xs px-2.5 py-1 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium rounded transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('m-Y') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No payment history found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Salary Slips --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6"
                     x-data="{ slipMonth: {{ date('n') }}, slipYear: {{ date('Y') }} }">
                    <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-indigo-800 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Salary Slip
                        </h3>
                    </div>
                    <div class="p-6 flex flex-wrap items-end gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                            <select x-model="slipMonth" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm p-2 border">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <select x-model="slipYear" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm p-2 border">
                                @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <a :href="`/salary/worker-pdf/{{ $worker->id }}/${slipMonth}/${slipYear}`"
                               target="_blank"
                               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-5 rounded-md shadow transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Slip PDF
                            </a>
                        </div>
                        <p class="text-xs text-gray-400 w-full mt-1">
                            Generates a detailed salary slip PDF for <strong>{{ $worker->name }}</strong> for the selected month including daily attendance grid.
                        </p>
                    </div>
                </div>

        </div>
    </div>
</div>
