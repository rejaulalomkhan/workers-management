<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-4">
            <h2 class="text-2xl font-bold text-gray-800">Monthly Attendance Report</h2>
        </div>
        <div>
            <button wire:click="downloadPdf" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span wire:loading.remove wire:target="downloadPdf">Download PDF</span>
                <span wire:loading wire:target="downloadPdf">Generating...</span>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Project</label>
                <select wire:model.live="projectId" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2 px-3">
                    <option value="">All Projects / Overall</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                <select wire:model.live="filterMonth" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2 px-3">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                <select wire:model.live="filterYear" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2 px-3">
                    @for($y=date('Y')-2; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                Attendance for {{ date('F Y', mktime(0,0,0,$filterMonth, 1, $filterYear)) }}
                @if($projectId)
                    <span class="text-sm font-normal text-gray-500 ml-2">({{ $projects->firstWhere('id', $projectId)->name ?? '' }})</span>
                @endif
            </h3>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-max min-w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-4 py-3 font-semibold sticky left-0 bg-gray-50 border-r border-gray-200 z-10 w-48">Worker Name</th>
                        <th class="px-4 py-3 font-semibold border-r border-gray-200 text-center">Trade</th>
                        @for($d=1; $d<=$daysInMonth; $d++)
                            <th class="px-2 py-3 font-semibold border-r border-gray-200 text-center w-8">{{ $d }}</th>
                        @endfor
                        <th class="px-4 py-3 font-semibold text-center bg-blue-50 text-blue-800 border-l border-gray-200">Total Hrs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reportData as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white group-hover:bg-gray-50 border-r border-gray-200 truncate max-w-[12rem]">{{ $row['worker']->name }}</td>
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
                            <td colspan="{{ $daysInMonth + 3 }}" class="px-6 py-10 text-center text-gray-500">
                                No attendance records found for the selected criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
