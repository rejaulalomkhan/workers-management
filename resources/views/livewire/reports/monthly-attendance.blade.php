<div class="space-y-6" x-data="{ showDateModal: false }">

    {{-- ══ Date Range Modal ══ --}}
    <div x-show="showDateModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         style="display:none;">
        <div @click.outside="showDateModal = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="text-white font-bold text-base">Select Date Range</h3>
                    <p class="text-blue-200 text-xs mt-0.5">PDF will cover the selected period</p>
                </div>
                <button @click="showDateModal = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        📅 From Date
                    </label>
                    <div class="relative" x-data="{ 
                        date: @entangle('pdfFromDate'),
                        get fmt() {
                            if(!this.date) return '';
                            let parts = this.date.split('-');
                            if(parts.length < 3) return this.date;
                            return `${parts[2]}-${parts[1]}-${parts[0]}`;
                        }
                    }">
                        <div class="w-full rounded-lg border border-gray-300 shadow-sm text-sm px-3 py-2 bg-white flex justify-between items-center">
                            <span x-text="fmt" class="text-gray-700 font-medium"></span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="date" wire:model="pdfFromDate" x-model="date" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer" onclick="this.showPicker()">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        📅 To Date
                    </label>
                    <div class="relative" x-data="{ 
                        date: @entangle('pdfToDate'),
                        get fmt() {
                            if(!this.date) return '';
                            let parts = this.date.split('-');
                            if(parts.length < 3) return this.date;
                            return `${parts[2]}-${parts[1]}-${parts[0]}`;
                        }
                    }">
                        <div class="w-full rounded-lg border border-gray-300 shadow-sm text-sm px-3 py-2 bg-white flex justify-between items-center">
                            <span x-text="fmt" class="text-gray-700 font-medium"></span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="date" wire:model="pdfToDate" x-model="date" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer" onclick="this.showPicker()">
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 pb-5 flex gap-3">
                <button @click="showDateModal = false"
                        class="flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button wire:click="downloadPdf" @click="showDateModal = false"
                        class="flex-1 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span wire:loading.remove wire:target="downloadPdf">Download PDF</span>
                    <span wire:loading wire:target="downloadPdf">Generating...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ Page Header ══ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-4">
            <h2 class="text-2xl font-bold text-gray-800">Monthly Attendance Report</h2>
        </div>
        <div>
            <button @click="showDateModal = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </button>
        </div>
    </div>


    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Project</label>
                <select wire:model.live="projectId" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2 px-3 text-sm">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Trade / Category</label>
                <select wire:model.live="tradeFilter" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2 px-3 text-sm">
                    <option value="">All Trades</option>
                    @foreach($trades as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                <select wire:model.live="filterMonth" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2 px-3 text-sm">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                <select wire:model.live="filterYear" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2 px-3 text-sm">
                    @for($y=date('Y')-2; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        {{-- Active filters summary --}}
        @if($tradeFilter || $projectId)
        <div class="mt-3 flex flex-wrap gap-2">
            @if($tradeFilter)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-50 border border-orange-200 text-orange-700 text-xs font-semibold rounded-full">
                🔧 {{ $tradeFilter }}
                <button wire:click="$set('tradeFilter', '')" class="hover:text-orange-900 ml-0.5">✕</button>
            </span>
            @endif
            @if($projectId)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold rounded-full">
                🏗️ {{ $projects->firstWhere('id', $projectId)?->name }}
                <button wire:click="$set('projectId', '')" class="hover:text-blue-900 ml-0.5">✕</button>
            </span>
            @endif
        </div>
        @endif
    </div>


    <!-- Report Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                Attendance for {{ date('m-Y', mktime(0,0,0,$filterMonth, 1, $filterYear)) }}
                @if($projectId)
                    <span class="text-sm font-normal text-gray-500 ml-2">({{ $projects->firstWhere('id', $projectId)->name ?? '' }})</span>
                @endif
            </h3>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-max min-w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-2 py-3 font-semibold sticky left-0 bg-gray-50 border-r border-gray-200 z-10 w-10 text-center">SN</th>
                        <th class="px-4 py-3 font-semibold sticky left-10 bg-gray-50 border-r border-gray-200 z-10 w-48">Worker Name</th>
                        <th class="px-4 py-3 font-semibold border-r border-gray-200 text-center">Trade</th>
                        @for($d=1; $d<=$daysInMonth; $d++)
                            <th class="px-2 py-3 font-semibold border-r border-gray-200 text-center w-8">{{ $d }}</th>
                        @endfor
                        <th class="px-4 py-3 font-semibold text-center bg-blue-50 text-blue-800 border-l border-gray-200">Total Hrs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reportData as $index => $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-2 py-3 text-xs font-semibold text-gray-600 text-center sticky left-0 bg-white group-hover:bg-gray-50 border-r border-gray-200 z-10">{{ $row['worker']->id }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 sticky left-10 bg-white group-hover:bg-gray-50 border-r border-gray-200 truncate max-w-[12rem] z-10">{{ $row['worker']->name }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500 border-r border-gray-200 text-center">{{ $row['worker']->trade }}</td>
                            @foreach($row['days'] as $day => $val)
                                <td class="px-1 py-3 text-center border-r border-gray-200 {{ $val === 'A' ? 'text-red-500 font-bold bg-red-50/50' : ($val !== '-' ? 'text-green-700 font-bold' : 'text-gray-300') }}">
                                    {{ $val }}
                                </td>
                            @endforeach
                            <td class="px-4 py-3 font-bold text-center bg-blue-50/50 text-blue-700 border-l border-gray-200">
                                {{ $row['totalHours'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 4 }}" class="px-6 py-10 text-center text-gray-500">
                                No attendance records found for the selected criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
