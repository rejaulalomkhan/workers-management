<div class="space-y-5">

    {{-- ══ Header + All Filters in one flex-wrap bar ══ --}}
    <div class="flex flex-wrap items-center gap-2">

        {{-- Title badge --}}
        <span class="text-base font-bold text-gray-800 whitespace-nowrap mr-1">Attendance</span>

        {{-- Project --}}
        <select wire:model.live="project_id"
                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500
                       text-sm p-1.5 border max-w-[130px] sm:max-w-[200px] min-w-0 truncate">
            @foreach($projects as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        {{-- Month --}}
        <select wire:model.live="month"
                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 text-sm p-1.5 border">
            @for($m=1; $m<=12; $m++)
                <option value="{{ $m }}">{{ date('M', mktime(0,0,0,$m,1)) }}</option>
            @endfor
        </select>

        {{-- Year --}}
        <select wire:model.live="year"
                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 text-sm p-1.5 border">
            @for($y=date('Y')-2; $y<=date('Y')+1; $y++)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>

        {{-- Trade filter --}}
        <select wire:model.live="tradeFilter"
                class="rounded-md border-orange-300 shadow-sm focus:border-orange-500
                       text-sm p-1.5 border bg-orange-50 text-orange-700 max-w-[100px] truncate">
            <option value="">🔧 All</option>
            @foreach($trades as $t)
                <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
        </select>

        {{-- Worker filter --}}
        <select wire:model.live="workerFilter"
                class="rounded-md border-blue-300 shadow-sm focus:border-blue-500
                       text-sm p-1.5 border bg-blue-50 text-blue-700 max-w-[120px] sm:max-w-none truncate">
            <option value="all">👷 All</option>
            @foreach($allWorkers as $w)
                <option value="{{ $w->id }}">{{ $w->name }}</option>
            @endforeach
        </select>

        <span class="text-xs text-gray-300 hidden sm:inline">• Auto-saves</span>
    </div>

    @if(!$project_id)
        <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
            <h3 class="text-sm font-semibold text-gray-900">Please select a project</h3>
            <p class="mt-1 text-sm text-gray-500">Pick a project from the top menu to view or enter attendance.</p>
        </div>
    @else
        {{-- Saving global indicator --}}
        <div wire:loading.flex class="items-center gap-2 text-xs text-blue-600 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg w-fit">
            <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            Saving...
        </div>

        <div class="space-y-4">
            @foreach($workers as $worker)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition hover:shadow-md">
                {{-- Worker Header --}}
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100 flex-wrap gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($worker->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">{{ $worker->name }}</div>
                            <span class="text-xs text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $worker->trade }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        {{-- Quick Fill Buttons --}}
                        <button wire:click="fillAllPresent({{ $worker->id }}, 8)"
                            wire:loading.attr="disabled"
                            wire:target="fillAllPresent({{ $worker->id }}, 8)"
                            class="text-xs px-2.5 py-1 rounded-md bg-green-100 text-green-700 hover:bg-green-200 font-medium transition flex items-center gap-1"
                            title="Fill all empty days with 8 hrs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            8 hrs
                        </button>
                        <button wire:click="fillAllPresent({{ $worker->id }}, 10)"
                            wire:loading.attr="disabled"
                            wire:target="fillAllPresent({{ $worker->id }}, 10)"
                            class="text-xs px-2.5 py-1 rounded-md bg-blue-100 text-blue-700 hover:bg-blue-200 font-medium transition flex items-center gap-1"
                            title="Fill all empty days with 10 hrs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            10 hrs
                        </button>
                        <button wire:click="clearWorker({{ $worker->id }})"
                            wire:loading.attr="disabled"
                            wire:target="clearWorker({{ $worker->id }})"
                            onclick="confirm('Clear all attendance for {{ addslashes($worker->name) }} this month?') || event.stopImmediatePropagation()"
                            class="text-xs px-2.5 py-1 rounded-md bg-red-100 text-red-600 hover:bg-red-200 font-medium transition flex items-center gap-1"
                            title="Clear all entries for this worker this month">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Clear
                        </button>
                        {{-- Monthly total --}}
                        @php
                            $workerTotal = collect($attendances[$worker->id] ?? [])->map(fn($v) => is_numeric($v) ? (float)$v : 0)->sum();
                        @endphp
                        <div class="text-right ml-2 pl-2 border-l border-gray-200">
                            <div class="text-base font-bold text-gray-800 leading-none">{{ number_format($workerTotal, 1) }}</div>
                            <div class="text-[10px] text-gray-400">hrs</div>
                        </div>
                    </div>
                </div>

                {{-- Day Inputs Grid --}}
                <div class="p-3">
                    <div class="flex flex-wrap gap-1.5">
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $cellKey = $worker->id . '.' . $day;
                                $val = $attendances[$worker->id][$day] ?? '';
                                $isAbsent = strtoupper($val) === 'A';
                                $hasHours = is_numeric($val) && $val > 0;
                            @endphp
                            <div class="flex flex-col items-center relative" style="min-width: 2.4rem;">
                                <span class="text-[9px] text-gray-400 mb-0.5 font-medium leading-none">{{ $day }}</span>
                                <div class="relative group">
                                    <input
                                        type="text"
                                        wire:model.lazy="attendances.{{ $worker->id }}.{{ $day }}"
                                        wire:loading.class="opacity-50 cursor-wait"
                                        wire:target="updatedAttendances"
                                        id="cell-{{ $worker->id }}-{{ $day }}"
                                        maxlength="4"
                                        placeholder="·"
                                        class="w-9 h-9 text-center rounded-lg text-xs font-bold uppercase border transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500
                                            @if($isAbsent) bg-red-50 border-red-300 text-red-600
                                            @elseif($hasHours) bg-green-50 border-green-300 text-green-700
                                            @else border-gray-200 text-gray-600 hover:border-blue-300 bg-white @endif"
                                    >
                                    {{-- Per-cell Loading Spinner --}}
                                    <div wire:loading wire:target="updatedAttendances"
                                        class="absolute inset-0 flex items-center justify-center bg-white/70 rounded-lg pointer-events-none">
                                        <svg class="animate-spin w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                        </svg>
                                    </div>
                                    {{-- Saved Checkmark (JS controlled) --}}
                                    <div id="saved-{{ $worker->id }}-{{ $day }}" class="absolute inset-0 flex items-center justify-center bg-green-500/90 rounded-lg pointer-events-none opacity-0 transition-opacity duration-300">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            @endforeach

            @if($workers->isEmpty())
                <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                    <h3 class="text-sm font-semibold text-gray-900">No active workers found</h3>
                    <p class="mt-1 text-sm text-gray-500">Add active workers or change the filter.</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Tip + Legend --}}
    <div class="p-3 bg-blue-50 rounded-lg text-xs text-blue-700 flex flex-wrap gap-4 items-center">
        <span><strong>Tip:</strong> Type hours (e.g. <code class="bg-blue-100 px-1 rounded">8</code>) or <code class="bg-blue-100 px-1 rounded">A</code> for absent. Saves instantly.</span>
        <div class="flex items-center gap-3 ml-auto">
            <span class="flex items-center gap-1"><span class="inline-block w-4 h-4 rounded bg-green-100 border border-green-300"></span> Hours entered</span>
            <span class="flex items-center gap-1"><span class="inline-block w-4 h-4 rounded bg-red-100 border border-red-300"></span> Absent</span>
            <span class="flex items-center gap-1"><span class="inline-block w-4 h-4 rounded bg-white border border-gray-200"></span> Empty</span>
        </div>
    </div>
</div>

{{-- Cell-saved flash JS handler --}}
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('cell-saved', ({ cell }) => {
        const [workerId, day] = cell.split('.');
        const el = document.getElementById(`saved-${workerId}-${day}`);
        if (!el) return;
        el.style.opacity = '1';
        setTimeout(() => { el.style.opacity = '0'; }, 1200);
    });
});
</script>
