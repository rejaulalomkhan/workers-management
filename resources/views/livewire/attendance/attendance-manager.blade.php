<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Attendance Tracker</h2>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
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
        </div>
    </div>

    @if(!$project_id)
        <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
            <h3 class="mt-2 text-sm font-semibold text-gray-900">Please select a project</h3>
            <p class="mt-1 text-sm text-gray-500">Pick a project from the top menu to view or enter attendance.</p>
        </div>
    @else
        <div class="space-y-4">
            <!-- Mobile/Desktop worker cards -->
            @foreach($workers as $worker)
            <div class="bg-white rounded-lg shadow border border-gray-100 p-4 transition duration-150 hover:shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $worker->name }}</h4>
                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ $worker->trade }}</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-7 sm:grid-cols-10 md:grid-cols-15 lg:grid-cols-auto-fit gap-2">
                    @for($day=1; $day<=$daysInMonth; $day++)
                        <div class="flex flex-col items-center">
                            <span class="text-[10px] text-gray-400 mb-1 font-medium">{{ $day }}</span>
                            <input 
                                type="text" 
                                wire:model.lazy="attendances.{{ $worker->id }}.{{ $day }}" 
                                class="w-8 h-8 md:w-10 md:h-10 text-center rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs font-medium uppercase border"
                                placeholder=""
                            >
                        </div>
                    @endfor
                </div>
            </div>
            @endforeach
            
            @if($workers->isEmpty())
                <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No active workers</h3>
                    <p class="mt-1 text-sm text-gray-500">Add active workers to see them here.</p>
                </div>
            @endif
        </div>
    @endif
    
    <div class="mt-4 p-4 bg-blue-50 rounded-lg text-sm text-blue-800">
        <strong>Tip:</strong> Enter 'A' for absent, or enter the number of hours worked (e.g. '8' or '10'). Values save automatically.
    </div>
</div>

<style>
.grid-cols-auto-fit {
    grid-template-columns: repeat(auto-fit, minmax(2.5rem, 1fr));
}
</style>
