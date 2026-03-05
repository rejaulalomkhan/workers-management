<div class="space-y-6" x-data="{ tab: 'overview' }">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="/projects" class="p-2 bg-white rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Project Details</h2>
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
                <div class="bg-gradient-to-r from-blue-700 to-indigo-800 h-24 relative">
                    <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+CjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0ibm9uZSIvPgo8Y2lyY2xlIGN4PSIyIiBjeT0iMiIgcj0iMiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+')]"></div>
                </div>
                <div class="flex justify-center -mt-12 relative z-10">
                    <div class="w-24 h-24 bg-white rounded-xl border-4 border-white flex items-center justify-center shadow-md overflow-hidden text-blue-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $project->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4 mt-1">{{ $project->location ?? 'No Location Setup' }}</p>
                    
                    <div class="text-left mt-4 pt-4 border-t border-gray-100 space-y-3">
                        <div>
                            <span class="block text-xs uppercase text-gray-400 font-bold tracking-wider">Client</span>
                            <span class="font-semibold text-gray-800">{{ $project->customer_name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400 font-bold tracking-wider">TRN</span>
                            <span class="font-medium text-gray-600">{{ $project->customer_trn ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100 pb-2">
                        <span class="block text-xs uppercase text-gray-400 font-bold tracking-wider text-left mb-2">Required Labor Categories</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($project->categories as $cat)
                            <span class="bg-blue-50 text-blue-700 text-[10px] px-2 py-1 rounded border border-blue-200 uppercase font-semibold">
                                {{ $cat->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="w-full lg:w-2/3 xl:w-3/4 space-y-6">
            
            <!-- Navigation Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-1 flex overflow-x-auto gap-1">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="shrink-0 px-4 sm:px-6 py-2.5 rounded-md text-sm transition whitespace-nowrap">Analytics Overview</button>
                <button @click="tab = 'today'" :class="tab === 'today' ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="shrink-0 px-4 sm:px-6 py-2.5 rounded-md text-sm transition whitespace-nowrap">Daily Attendance</button>
                <button @click="tab = 'workers'" :class="tab === 'workers' ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="shrink-0 px-4 sm:px-6 py-2.5 rounded-md text-sm transition whitespace-nowrap">Project Workers</button>
                <button @click="tab = 'datewise'" :class="tab === 'datewise' ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="shrink-0 px-4 sm:px-6 py-2.5 rounded-md text-sm transition whitespace-nowrap">Date Wise List</button>
            </div>

            <!-- TAB: Overview -->
            <div x-show="tab === 'overview'" x-transition.opacity>
                
                <!-- Lifetime Metrics -->
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Lifetime Metrics</h3>
                <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-8">
                    <div class="bg-white p-4 sm:p-5 rounded-xl border-t-4 border-green-500 shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
                        <p class="text-xs sm:text-sm text-gray-500 font-medium mb-1">Total Project Hours</p>
                        <h4 class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($totalHours, 1) }} <span class="text-xs sm:text-sm font-normal text-gray-400">hr</span></h4>
                        <p class="text-[10px] sm:text-xs text-gray-400 mt-2">Lifetime Working</p>
                    </div>
                    <div class="bg-white p-4 sm:p-5 rounded-xl border-t-4 border-red-500 shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
                        <p class="text-xs sm:text-sm text-gray-500 font-medium mb-1">Total Receivable</p>
                        <h4 class="text-xl sm:text-2xl font-bold text-red-600"><span class="text-xs sm:text-sm font-normal text-red-400">AED</span> {{ number_format($totalReceivable, 2) }}</h4>
                        <p class="text-[10px] sm:text-xs text-gray-400 mt-2">Lifetime Total</p>
                    </div>
                </div>

                <!-- Monthly Filtering -->
                <div class="flex items-center justify-between mb-4 mt-2">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest ml-1">Monthly Snapshot</h3>
                    <div class="flex gap-2 bg-white p-1 rounded-md border border-gray-200 shadow-sm">
                        <select wire:model.live="filterMonth" class="text-sm border-none bg-transparent focus:ring-0 text-gray-700 font-medium cursor-pointer">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i, 1)) }}</option>
                            @endfor
                        </select>
                        <select wire:model.live="filterYear" class="text-sm border-none bg-transparent focus:ring-0 text-gray-700 font-medium cursor-pointer">
                            @for($y=date('Y')-2; $y<=date('Y')+1; $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6">
                    <div class="bg-white p-4 sm:p-5 rounded-xl border-t-4 border-yellow-400 shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
                        <p class="text-xs sm:text-sm text-gray-500 font-medium mb-1">This Month's Hours</p>
                        <h4 class="text-xl sm:text-2xl font-bold text-yellow-600">{{ number_format($monthHours, 1) }} <span class="text-xs sm:text-sm font-normal text-gray-400">hr</span></h4>
                        <p class="text-[10px] sm:text-xs text-gray-400 mt-2">Selected Month</p>
                    </div>
                    <div class="bg-white p-4 sm:p-5 rounded-xl border-t-4 border-blue-500 shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
                        <p class="text-xs sm:text-sm text-gray-500 font-medium mb-1">This Month's Receivable</p>
                        <h4 class="text-xl sm:text-2xl font-bold text-blue-600"><span class="text-xs sm:text-sm font-normal text-blue-400">AED</span> {{ number_format($monthReceivable, 2) }}</h4>
                        <p class="text-[10px] sm:text-xs text-gray-400 mt-2">Selected Month</p>
                    </div>

                    <!-- MASON Card -->
                    <div class="bg-white p-4 sm:p-5 rounded-xl border-t-4 border-indigo-500 shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="bg-indigo-50 p-1.5 rounded-lg text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-500 font-bold uppercase tracking-tight">MASON Overview</p>
                            </div>
                            <div class="flex flex-col gap-1">
                                <h4 class="text-xl sm:text-2xl font-black text-indigo-700">{{ number_format($masonHours, 1) }} <span class="text-xs font-normal text-indigo-400">Hours</span></h4>
                                <div class="flex items-center gap-1.5 mt-1 border-t border-indigo-50 pt-2">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Receivable:</span>
                                    <span class="text-xs sm:text-sm font-bold text-gray-700"><span class="text-[10px] font-normal text-gray-400">AED</span> {{ number_format($masonAmount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- HELPER Card -->
                    <div class="bg-white p-4 sm:p-5 rounded-xl border-t-4 border-purple-500 shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="bg-purple-50 p-1.5 rounded-lg text-purple-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-500 font-bold uppercase tracking-tight">HELPER Overview</p>
                            </div>
                            <div class="flex flex-col gap-1">
                                <h4 class="text-xl sm:text-2xl font-black text-purple-700">{{ number_format($helperHours, 1) }} <span class="text-xs font-normal text-purple-400">Hours</span></h4>
                                <div class="flex items-center gap-1.5 mt-1 border-t border-purple-50 pt-2">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Receivable:</span>
                                    <span class="text-xs sm:text-sm font-bold text-gray-700"><span class="text-[10px] font-normal text-gray-400">AED</span> {{ number_format($helperAmount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB: Today's Workers -->
            <div x-show="tab === 'today'" style="display: none;" x-transition.opacity>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center sm:flex-row flex-col gap-3">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center flex-wrap gap-2">
                            Daily Attendance 
                            <div class="relative inline-block" x-data="{ 
                                date: @entangle('todayDate'),
                                get fmt() {
                                    if(!this.date) return '';
                                    let parts = this.date.split('-');
                                    if(parts.length < 3) return this.date;
                                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                                }
                            }">
                                <div class="flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-800 text-sm px-3 py-1.5 rounded-md font-bold transition hover:bg-blue-100 min-w-[120px]">
                                    <span x-text="fmt"></span>
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="date" wire:model.live="todayDate" x-model="date" 
                                       class="absolute inset-0 opacity-0 w-full h-full cursor-pointer"
                                       onclick="this.showPicker()">
                            </div>
                        </h3>
                        
                        <div class="flex gap-2">
                            <select wire:model="newWorkerId" class="text-sm rounded border border-gray-300 py-1.5 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">+ Assign Worker...</option>
                                @foreach($availableWorkersForTodaySelect as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->trade }})</option>
                                @endforeach
                            </select>
                            <button wire:click="addNewWorkerToProject" class="bg-gray-800 text-white px-3 py-1.5 rounded text-sm font-semibold hover:bg-gray-700">Add</button>
                        </div>
                    </div>
                    
                    <form wire:submit="saveTodayAttendance">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                        <th class="px-6 py-3 font-medium text-center w-8">ID</th>
                                        <th class="px-6 py-3 font-medium">Worker Name</th>
                                        <th class="px-6 py-3 font-medium">Trade</th>
                                        <th class="px-6 py-3 font-medium text-right">Hours/Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($todaysWorkersList as $wId => $wModel)
                                        <tr wire:key="worker-{{ $wId }}" class="hover:bg-blue-50/50 transition">
                                            <td class="px-6 py-3 text-center font-bold text-gray-400">#{{ $wId }}</td>
                                            <td class="px-6 py-3 font-medium text-gray-900">{{ $wModel->name }}</td>
                                            <td class="px-6 py-3 text-sm text-gray-500">{{ $wModel->trade }}</td>
                                            <td class="px-6 py-3 text-right flex justify-end items-center gap-2">
                                                <input type="text" id="att-{{ $wId }}" name="att-[{{ $wId }}]" wire:key="att-in-{{ $wId }}" wire:model="todayAttendances.{{ $wId }}" placeholder="'A' or hrs" 
                                                    class="w-24 rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 text-center text-lg font-bold">
                                                <button type="button" wire:click="removeWorkerFromToday({{ $wId }})" title="Remove Worker from Today" class="text-red-400 hover:text-red-600 p-1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400">No workers assigned to this project's daily tracker. Select a worker above to begin logging.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow flex items-center transition">
                                <span wire:loading.remove wire:target="saveTodayAttendance">Save Records</span>
                                <span wire:loading wire:target="saveTodayAttendance">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB: Project Workers -->
            <div x-show="tab === 'workers'" style="display: none;" x-transition.opacity>
                <!-- Permanent Workers Assignments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center sm:flex-row flex-col gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Permanently Assigned Workers</h3>
                            <p class="text-xs text-gray-500 mt-1">These workers will automatically appear in Daily Attendance every day.</p>
                        </div>
                        <div class="flex gap-2">
                            <select wire:model="newPermanentWorkerId" class="text-sm rounded border border-gray-300 py-1.5 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">+ Assign Permanent Worker...</option>
                                @foreach($availableWorkersForPermanentSelect as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->trade }})</option>
                                @endforeach
                            </select>
                            <button wire:click="assignPermanentWorker" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm font-semibold hover:bg-blue-700">Assign</button>
                        </div>
                    </div>

                    @if (session()->has('permanent_message'))
                        <div class="p-3 text-sm text-blue-800 bg-blue-50 border-b border-blue-100">
                            {{ session('permanent_message') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-3 font-medium text-center w-8">ID</th>
                                    <th class="px-6 py-3 font-medium">Worker Name</th>
                                    <th class="px-6 py-3 font-medium">Trade</th>
                                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($permanentWorkers as $pw)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 text-center font-bold text-gray-400">#{{ $pw->id }}</td>
                                        <td class="px-6 py-3 font-medium text-gray-900">{{ $pw->name }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-500">{{ $pw->trade }}</td>
                                        <td class="px-6 py-3 text-right">
                                            <button wire:click="removePermanentWorker({{ $pw->id }})" class="text-xs text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1 rounded border border-red-200 font-semibold transition">
                                                Unassign
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400">No workers permanently assigned yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Monthly Roster -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Monthly Details Roster ({{ date('F Y', mktime(0,0,0,$filterMonth, 1, $filterYear)) }})</h3>
                        <p class="text-xs text-gray-500 mt-1">Workers who logged hours this month on this project</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-3 font-medium">Worker Name</th>
                                    <th class="px-6 py-3 font-medium">Trade</th>
                                    <th class="px-6 py-3 font-medium text-center">Month Hours</th>
                                    <th class="px-6 py-3 font-medium text-right">Est. Receivable</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($currentMonthWorkers as $cw)
                                    <tr class="hover:bg-gray-50 transition cursor-pointer" onclick="window.location.href='/workers/{{ $cw['worker']->id }}'">
                                        <td class="px-6 py-4 font-bold text-blue-600">{{ $cw['worker']->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $cw['worker']->trade }}</td>
                                        <td class="px-6 py-4 text-center font-bold text-gray-700">{{ $cw['hours'] }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-green-700">AED {{ number_format($cw['amount'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No hours logged yet for this month.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB: Date Wise List -->
            <div x-show="tab === 'datewise'" style="display: none;" x-transition.opacity>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center sm:flex-row flex-col gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Date Wise Workers List ({{ date('F Y', mktime(0,0,0,$filterMonth, 1, $filterYear)) }})</h3>
                            <p class="text-xs text-gray-500 mt-1">Summary of daily attendance collapsed by date</p>
                        </div>
                        <div class="flex gap-2">
                            <select wire:model.live="filterMonth" class="text-sm rounded border border-gray-300 py-1.5 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500">
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i, 1)) }}</option>
                                @endfor
                            </select>
                            <select wire:model.live="filterYear" class="text-sm rounded border border-gray-300 py-1.5 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500">
                                @for($y=date('Y')-2; $y<=date('Y')+1; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto p-4 sm:p-6 space-y-4 bg-gray-50/50">
                        @forelse($dateWiseWorkers as $dateString => $dateData)
                            <div x-data="{ expanded: false }" class="bg-white border text-left border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <!-- Summary Row -->
                                <button @click="expanded = !expanded" class="w-full px-4 py-4 sm:px-6 flex items-center justify-between hover:bg-gray-50 transition focus:outline-none">
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        <div class="bg-blue-100 text-blue-800 p-2 rounded-lg font-bold flex flex-col items-center justify-center min-w-[3rem] leading-none text-center">
                                            <span class="text-base sm:text-lg font-black">{{ \Carbon\Carbon::parse($dateString)->format('d') }}</span>
                                            <span class="text-[9px] sm:text-[10px] uppercase mt-0.5">{{ \Carbon\Carbon::parse($dateString)->format('M') }}</span>
                                        </div>
                                        <div class="text-left leading-tight">
                                            <div class="font-bold text-gray-900 border-none text-sm sm:text-base">{{ $dateData['date_display'] }}</div>
                                            <div class="text-xs text-gray-500 mt-1"><span class="font-bold text-gray-700">{{ $dateData['total_workers'] }}</span> Present</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 sm:gap-6">
                                        <div class="text-right">
                                            <div class="font-black text-blue-600 text-base sm:text-xl border-none">{{ $dateData['total_hours'] }} <span class="text-[10px] sm:text-xs font-normal text-gray-400">hr</span></div>
                                            <div class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Total Hours</div>
                                        </div>
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                            <svg class="w-5 h-5 transform transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </button>
                                
                                <!-- Detailed Workers List (Collapsible) -->
                                <div x-show="expanded" style="display: none;" x-transition>
                                    <div class="border-t border-gray-100 bg-gray-50/80 px-4 py-4 sm:px-6">
                                        <table class="w-full text-left border-collapse text-sm">
                                            <thead>
                                                <tr class="text-gray-400 text-[10px] sm:text-xs uppercase tracking-wider border-b border-gray-200">
                                                    <th class="py-2 px-2 sm:px-3 font-semibold">Worker Name</th>
                                                    <th class="py-2 px-2 sm:px-3 font-semibold">Trade</th>
                                                    <th class="py-2 px-2 sm:px-3 font-semibold text-right">Hours Logged</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach($dateData['workers'] as $wd)
                                                    <tr class="hover:bg-white transition cursor-pointer" onclick="window.location.href='/workers/{{ $wd['worker']->id }}'">
                                                        <td class="py-3 px-2 sm:px-3 font-bold text-blue-600">{{ $wd['worker']->name }}</td>
                                                        <td class="py-3 px-2 sm:px-3 text-gray-500 text-xs sm:text-sm">{{ $wd['worker']->trade }}</td>
                                                        <td class="py-3 px-2 sm:px-3 text-right font-bold text-gray-700">{{ $wd['hours'] }} <span class="text-xs font-normal text-gray-400">hr</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-white rounded-lg border border-gray-200">
                                <p class="text-gray-500 font-medium">No attendance records documented for this month.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
