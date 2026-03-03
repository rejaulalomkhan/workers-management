<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Workers</h2>
        <div class="flex w-full sm:w-auto gap-2">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search workers..." class="w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow whitespace-nowrap">
                Add Worker
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($workers as $worker)
            <div class="bg-white rounded-lg shadow border border-gray-100 flex flex-col hover:shadow-md transition">
                <a href="/workers/{{ $worker->id }}" class="p-4 flex-1 cursor-pointer block">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600 transition">{{ $worker->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $worker->worker_id_number ?? 'No ID' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $worker->trade }}
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                        <div class="text-sm text-gray-500">Pay Rate</div>
                        <div class="font-bold text-gray-900">{{ number_format($worker->internal_pay_rate, 2) }} /hr</div>
                    </div>
                </a>
                <div class="bg-gray-50 px-4 py-3 border-t flex justify-end gap-3 rounded-b-lg">
                    <button wire:click="edit({{ $worker->id }})" class="text-blue-600 hover:text-blue-900 font-medium text-sm">Edit</button>
                    <button wire:click="delete({{ $worker->id }})" class="text-red-600 hover:text-red-900 font-medium text-sm" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $workers->links() }}
    </div>

    @if($workers->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No workers found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or add a new worker.</p>
        </div>
    @endif

    <!-- Modal Form -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                    <form wire:submit="store">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                {{ $worker_id ? 'Edit Worker' : 'Add Worker' }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name *</label>
                                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Worker ID Number</label>
                                    <input type="text" wire:model="worker_id_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                    @error('worker_id_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Trade / Category *</label>
                                    <input type="text" wire:model="trade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                    @error('trade') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Internal Pay Rate (per hour) *</label>
                                    <input type="number" step="0.01" wire:model="internal_pay_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                    @error('internal_pay_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Save
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
