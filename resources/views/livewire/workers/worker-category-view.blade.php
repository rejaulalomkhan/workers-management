<div class="space-y-6">
    <div class="flex items-center space-x-4">
        <a href="/worker-categories" class="p-2 bg-white rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200" wire:navigate>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Category: <span class="text-blue-600">{{ $category->name }}</span></h2>
    </div>

    <!-- Top Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 border-t-4 border-blue-500 shadow-sm border-x border-b border-gray-100 flex flex-col items-center justify-center">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-2">Total Workers</h3>
            <span class="text-4xl font-black text-blue-700">{{ $totalWorkers }}</span>
        </div>
        <div class="bg-white rounded-xl p-6 border-t-4 border-green-500 shadow-sm border-x border-b border-gray-100 flex flex-col items-center justify-center">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-2">Active Workers</h3>
            <span class="text-4xl font-black text-green-600">{{ $activeWorkers }}</span>
        </div>
        <div class="bg-white rounded-xl p-6 border-t-4 border-purple-500 shadow-sm border-x border-b border-gray-100 flex flex-col items-center justify-center">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-2">Avg. Internal Rate</h3>
            <span class="text-4xl font-black text-purple-700"><span class="text-xl font-normal text-purple-400">{{ \App\Models\Setting::first()->currency ?? 'AED' }}</span> {{ number_format($averageRate, 2) }}</span>
        </div>
    </div>

    <!-- Workers List -->
    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden mt-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Workers in this Category</h3>
                <p class="text-xs text-gray-500 mt-1">List of all workers currently assigned to the {{ $category->name }} category.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Worker Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Internal Pay Rate</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($workers as $worker)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-bold">#{{ $worker->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/workers/{{ $worker->id }}" wire:navigate class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $worker->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">{{ \App\Models\Setting::first()->currency ?? 'AED' }} {{ number_format($worker->internal_pay_rate, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if($worker->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                No workers are currently assigned to this category.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
