<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Profit & Loss Report</h2>
            <p class="text-sm text-gray-500 mt-1">Revenue vs Cost breakdown by month, project, worker & trade category</p>
        </div>
        {{-- Filters --}}
        <div class="flex flex-wrap gap-2 items-center">
            <select wire:model.live="month" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 sm:text-sm p-2 border">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                @endfor
            </select>
            <select wire:model.live="year" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 sm:text-sm p-2 border">
                @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
            {{-- Custom compact project dropdown (safe against special chars in project names) --}}
            @php
                $projectMap = $projects->mapWithKeys(fn($p) => [$p->id => $p->name]);
                $currentLabel = $selectedProject === 'all'
                    ? 'All Projects'
                    : ($projectMap[$selectedProject] ?? 'All Projects');
            @endphp
            <div class="relative" x-data="{
                    open: false,
                    selected: '{{ $selectedProject }}',
                    label: {{ json_encode($currentLabel) }},
                    projects: {{ json_encode($projectMap) }},
                    select(val) {
                        this.selected = val;
                        this.label = val === 'all' ? 'All Projects' : (this.projects[val] ?? 'All Projects');
                        this.open = false;
                        $wire.set('selectedProject', val);
                    }
                }"
                @keydown.escape="open = false"
                @click.outside="open = false">

                <button @click="open = !open" type="button"
                        class="flex items-center gap-1.5 rounded-md border border-gray-300 bg-white shadow-sm px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                    <span x-text="label" class="max-w-[120px] truncate"></span>
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0 transition-transform duration-150"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-1 w-80 max-w-[92vw] bg-white border border-gray-200 rounded-lg shadow-xl z-50 max-h-72 overflow-y-auto"
                     style="display:none;">
                    <ul class="py-1 text-sm text-gray-700">
                        <li>
                            <button type="button" @click="select('all')"
                                    class="w-full text-left px-4 py-2.5 hover:bg-blue-50 hover:text-blue-700 transition"
                                    :class="selected === 'all' ? 'bg-blue-50 text-blue-700 font-semibold' : ''">
                                All Projects
                            </button>
                        </li>
                        <li class="border-t border-gray-100"></li>
                        @foreach($projects as $p)
                        <li>
                            {{-- Store id in data attribute; click reads from Alpine data map --}}
                            <button type="button"
                                    x-on:click="select('{{ $p->id }}')"
                                    class="w-full text-left px-4 py-2.5 hover:bg-blue-50 hover:text-blue-700 transition leading-snug"
                                    :class="selected == '{{ $p->id }}' ? 'bg-blue-50 text-blue-700 font-semibold' : ''">
                                {{ $p->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Trade Category Summary (NEW TOP SECTION) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-bold text-gray-800 flex items-center gap-2 text-lg">
                    <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </span>
                    ট্রেড ক্যাটাগরি অনুযায়ী রিপোর্ট
                </h3>
                <p class="text-xs text-gray-500 mt-1">সব ক্যাটাগরির রিয়েল-টাইম পারফরম্যান্স ওভারভিউ</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">সময়কাল:</span>
                <select wire:model.live="tradePeriod" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 text-sm py-1.5 pl-3 pr-8 border bg-white font-medium text-gray-700">
                    <option value="today">আজ</option>
                    <option value="yesterday">গতকাল</option>
                    <option value="this_week">এই সপ্তাহ</option>
                    <option value="this_month">এই মাস</option>
                </select>
            </div>
        </div>
        
        <div class="p-4 lg:p-6">
            @if(count($tradeSummary) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    @foreach($tradeSummary as $row)
                    @php $isPos = $row['profit'] >= 0; @endphp
                    <div class="relative group bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <span class="font-bold text-gray-800 truncate mr-2">{{ $row['name'] }}</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase shrink-0 {{ $row['margin'] >= 15 ? 'bg-green-100 text-green-700' : ($row['margin'] >= 5 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                                {{ $row['margin'] }}%
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-2 text-xs">
                            <div class="flex flex-col">
                                <span class="text-gray-400 font-medium">Revenue</span>
                                <span class="text-blue-600 font-bold leading-none mt-1">{{ number_format($row['revenue'], 0) }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-gray-400 font-medium">Cost</span>
                                <span class="text-orange-500 font-bold leading-none mt-1">{{ number_format($row['cost'], 0) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-400 font-medium">Profit</span>
                                <span class="{{ $isPos ? 'text-green-600' : 'text-red-500' }} font-bold leading-none mt-1">{{ number_format($row['profit'], 0) }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-gray-400 font-medium">Hours</span>
                                <span class="text-gray-700 font-bold leading-none mt-1">{{ number_format($row['hours'], 1) }}h</span>
                            </div>
                        </div>
                        {{-- Mini progress bar --}}
                        <div class="mt-3 w-full bg-gray-50 rounded-full h-1 overflow-hidden">
                            <div class="bg-blue-500 h-full rounded-full transition-all duration-500" 
                                 style="width: {{ $row['margin'] > 0 ? min(100, $row['margin']) : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-gray-400 font-medium">No results for "{{ ucfirst(str_replace('_', ' ', $tradePeriod)) }}"</p>
                    <p class="text-xs text-gray-300 mt-1">Select a different period to see trade category summaries.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Summary Cards --}}
    @if($summaryData)
    @php
        $s = $summaryData;
        $isProfit = $s['totalProfit'] >= 0;
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow border border-gray-100 p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Hours</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($s['totalHours'], 1) }}</div>
            <div class="text-xs text-gray-400 mt-1">hrs worked</div>
        </div>
        <div class="bg-white rounded-xl shadow border border-gray-100 p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Revenue</div>
            <div class="text-2xl font-bold text-blue-600">{{ number_format($s['totalRevenue'], 0) }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ \App\Models\Setting::first()->currency ?? 'AED' }} billed</div>
        </div>
        <div class="bg-white rounded-xl shadow border border-gray-100 p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cost</div>
            <div class="text-2xl font-bold text-orange-500">{{ number_format($s['totalCost'], 0) }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ \App\Models\Setting::first()->currency ?? 'AED' }} paid out</div>
        </div>
        <div class="bg-white rounded-xl shadow border border-{{ $isProfit ? 'green' : 'red' }}-100 p-4 {{ $isProfit ? 'bg-green-50' : 'bg-red-50' }}">
            <div class="text-xs font-semibold {{ $isProfit ? 'text-green-600' : 'text-red-600' }} uppercase tracking-wider mb-1">{{ $isProfit ? 'Profit' : 'Loss' }}</div>
            <div class="text-2xl font-bold {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">{{ number_format(abs($s['totalProfit']), 0) }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ \App\Models\Setting::first()->currency ?? 'AED' }} net</div>
        </div>
        <div class="bg-white rounded-xl shadow border border-gray-100 p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Margin</div>
            <div class="text-2xl font-bold {{ $s['margin'] >= 15 ? 'text-green-600' : ($s['margin'] >= 5 ? 'text-yellow-500' : 'text-red-500') }}">{{ $s['margin'] }}%</div>
            <div class="text-xs text-gray-400 mt-1">profit margin</div>
        </div>
    </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <div class="overflow-x-auto flex no-scrollbar" style="-webkit-overflow-scrolling: touch;">
                <nav class="flex flex-nowrap min-w-full">
                    @foreach(['summary' => '📊 Summary', 'project' => '🏗️ By Project', 'worker' => '👷 By Worker', 'category' => '🔧 By Trade'] as $tab => $label)
                    <button wire:click="$set('activeTab', '{{ $tab }}')"
                        class="flex-shrink-0 whitespace-nowrap py-4 px-6 border-b-2 text-sm font-bold transition-all duration-200
                            {{ $activeTab === $tab ? 'border-blue-600 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <style>
            /* Hide scrollbar but keep functionality */
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>

        <div class="p-5">

            {{-- SUMMARY TAB --}}
            @if($activeTab === 'summary')
            <div class="space-y-4">
                <h3 class="text-base font-semibold text-gray-700">Monthly Summary — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</h3>
                @if(count($projectData) === 0)
                    <div class="text-center py-10 text-gray-400">No attendance data found for this period.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3 text-right">Hours</th>
                                <th class="px-4 py-3 text-right">Revenue ({{ \App\Models\Setting::first()->currency ?? 'AED' }})</th>
                                <th class="px-4 py-3 text-right">Cost ({{ \App\Models\Setting::first()->currency ?? 'AED' }})</th>
                                <th class="px-4 py-3 text-right">Profit ({{ \App\Models\Setting::first()->currency ?? 'AED' }})</th>
                                <th class="px-4 py-3 text-right">Margin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($categoryData as $row)
                            @php $isPos = $row['profit'] >= 0; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $row['name'] }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row['hours'], 1) }}</td>
                                <td class="px-4 py-3 text-right text-blue-600 font-medium">{{ number_format($row['revenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-orange-500">{{ number_format($row['cost'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ $isPos ? 'text-green-600' : 'text-red-500' }}">{{ number_format($row['profit'], 0) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['margin'] >= 15 ? 'bg-green-100 text-green-700' : ($row['margin'] >= 5 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                                        {{ $row['margin'] }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-bold border-t-2 border-gray-300">
                                <td class="px-4 py-3">TOTAL</td>
                                <td class="px-4 py-3 text-right">{{ number_format($summaryData['totalHours'], 1) }}</td>
                                <td class="px-4 py-3 text-right text-blue-700">{{ number_format($summaryData['totalRevenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-orange-600">{{ number_format($summaryData['totalCost'], 0) }}</td>
                                <td class="px-4 py-3 text-right {{ $summaryData['totalProfit'] >= 0 ? 'text-green-700' : 'text-red-600' }}">{{ number_format($summaryData['totalProfit'], 0) }}</td>
                                <td class="px-4 py-3 text-right">{{ $summaryData['margin'] }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- PROJECT TAB --}}
            @elseif($activeTab === 'project')
            <div class="space-y-4">
                <h3 class="text-base font-semibold text-gray-700">Profit by Project — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</h3>
                @if(count($projectData) === 0)
                    <div class="text-center py-10 text-gray-400">No data found for this period.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Project</th>
                                <th class="px-4 py-3 text-right">Hours</th>
                                <th class="px-4 py-3 text-right">Revenue ({{ \App\Models\Setting::first()->currency ?? 'AED' }})</th>
                                <th class="px-4 py-3 text-right">Cost ({{ \App\Models\Setting::first()->currency ?? 'AED' }})</th>
                                <th class="px-4 py-3 text-right">Profit ({{ \App\Models\Setting::first()->currency ?? 'AED' }})</th>
                                <th class="px-4 py-3 text-right">Margin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($projectData as $row)
                            @php $isPos = $row['profit'] >= 0; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $row['name'] }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row['hours'], 1) }}</td>
                                <td class="px-4 py-3 text-right text-blue-600 font-medium">{{ number_format($row['revenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-orange-500">{{ number_format($row['cost'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ $isPos ? 'text-green-600' : 'text-red-500' }}">{{ number_format($row['profit'], 0) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['margin'] >= 15 ? 'bg-green-100 text-green-700' : ($row['margin'] >= 5 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                                        {{ $row['margin'] }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- WORKER TAB --}}
            @elseif($activeTab === 'worker')
            <div class="space-y-4">
                <h3 class="text-base font-semibold text-gray-700">Profit by Worker — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</h3>
                @if(count($workerData) === 0)
                    <div class="text-center py-10 text-gray-400">No data found for this period.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Worker</th>
                                <th class="px-4 py-3">Trade</th>
                                <th class="px-4 py-3 text-right">Pay Rate</th>
                                <th class="px-4 py-3 text-right">Bill Rate</th>
                                <th class="px-4 py-3 text-right">Hours</th>
                                <th class="px-4 py-3 text-right">Revenue</th>
                                <th class="px-4 py-3 text-right">Cost</th>
                                <th class="px-4 py-3 text-right">Profit</th>
                                <th class="px-4 py-3 text-right">Margin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($workerData as $row)
                            @php $isPos = $row['profit'] >= 0; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $row['name'] }}</td>
                                <td class="px-4 py-3 text-gray-500"><span class="bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded">{{ $row['trade'] }}</span></td>
                                <td class="px-4 py-3 text-right text-orange-500">{{ number_format($row['pay_rate'], 2) }}</td>
                                <td class="px-4 py-3 text-right text-blue-500">{{ number_format($row['bill_rate'], 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row['hours'], 1) }}</td>
                                <td class="px-4 py-3 text-right text-blue-600 font-medium">{{ number_format($row['revenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-orange-500">{{ number_format($row['cost'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ $isPos ? 'text-green-600' : 'text-red-500' }}">{{ number_format($row['profit'], 0) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['margin'] >= 15 ? 'bg-green-100 text-green-700' : ($row['margin'] >= 5 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                                        {{ $row['margin'] }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- CATEGORY TAB --}}
            @elseif($activeTab === 'category')
            <div class="space-y-4">
                <h3 class="text-base font-semibold text-gray-700">Profit by Trade Category — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</h3>
                @if(count($categoryData) === 0)
                    <div class="text-center py-10 text-gray-400">No data found for this period.</div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                    @foreach($categoryData as $row)
                    @php $isPos = $row['profit'] >= 0; @endphp
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <div class="flex justify-between items-start mb-3">
                            <span class="font-bold text-gray-800 text-base">{{ $row['name'] }}</span>
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['margin'] >= 15 ? 'bg-green-100 text-green-700' : ($row['margin'] >= 5 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                                {{ $row['margin'] }}% margin
                            </span>
                        </div>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Hours</span>
                                <span class="font-medium">{{ number_format($row['hours'], 1) }} hrs</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Revenue</span>
                                <span class="text-blue-600 font-semibold">{{ \App\Models\Setting::first()->currency ?? 'AED' }} {{ number_format($row['revenue'], 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Cost</span>
                                <span class="text-orange-500">{{ \App\Models\Setting::first()->currency ?? 'AED' }} {{ number_format($row['cost'], 0) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-1.5 mt-1">
                                <span class="font-semibold text-gray-700">{{ $isPos ? 'Profit' : 'Loss' }}</span>
                                <span class="font-bold {{ $isPos ? 'text-green-600' : 'text-red-500' }}">{{ \App\Models\Setting::first()->currency ?? 'AED' }} {{ number_format(abs($row['profit']), 0) }}</span>
                            </div>
                        </div>
                        {{-- Progress bar --}}
                        @if($summaryData['totalRevenue'] > 0)
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-blue-500" style="width: {{ min(100, round($row['revenue'] / $summaryData['totalRevenue'] * 100)) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">{{ round($row['revenue'] / $summaryData['totalRevenue'] * 100) }}% of total revenue</div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>
