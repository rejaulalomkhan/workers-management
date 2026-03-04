<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Projects</h2>
        <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Project
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
            <div class="bg-white rounded-lg shadow border border-gray-100 flex flex-col hover:shadow-md transition">
                <div class="p-5 flex-1 block">
                    <a href="/projects/{{ $project->id }}" class="text-lg font-bold text-gray-900 mb-1 hover:text-blue-600 transition block relative">{{ $project->name }}<span class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></span></a>
                    <p class="text-sm text-gray-500 mb-4">{{ $project->location ?? 'No location' }}</p>
                    
                    <div class="text-sm mb-4 bg-gray-50 p-3 rounded">
                        <div class="font-semibold text-gray-700">{{ $project->customer_name ?? 'Client info N/A' }}</div>
                        @if($project->customer_trn) <div class="text-xs text-gray-500 mt-1">TRN: {{ $project->customer_trn }}</div> @endif
                    </div>
                    
                    <div class="space-y-2">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Labor Categories</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->categories as $c)
                                <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded border border-blue-200">
                                    {{ $c->name }} ({{ $c->billing_rate }})
                                </span>
                            @endforeach
                            @if($project->categories->isEmpty())
                                <span class="text-xs text-gray-400">None defined</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3 border-t flex justify-end gap-3 rounded-b-lg">
                    <button wire:click="edit({{ $project->id }})" class="text-blue-600 hover:text-blue-900 font-medium text-sm">Edit</button>
                    <button wire:click="delete({{ $project->id }})" class="text-red-600 hover:text-red-900 font-medium text-sm" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                </div>
            </div>
        @endforeach
    </div>
    
    @if($projects->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No projects</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
        </div>
    @endif

    <!-- Modal Form -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                    <form wire:submit="store">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                {{ $project_id ? 'Edit Project' : 'Add Project' }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Project Name *</label>
                                    <textarea wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" rows="3"></textarea>
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Location</label>
                                    <input type="text" wire:model="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg space-y-3">
                                    <h4 class="text-sm font-semibold text-gray-700">Customer Details</h4>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                                        <input type="text" wire:model="customer_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Customer Address</label>
                                        <textarea wire:model="customer_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" rows="3"></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Customer TRN</label>
                                            <input type="text" wire:model="customer_trn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Customer Phone (TEL)</label>
                                            <input type="text" wire:model="customer_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" placeholder="+971...">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Subject / Sub <span class="text-gray-400 text-xs">(e.g. Labor Supply)</span></label>
                                        <input type="text" wire:model="customer_subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" placeholder="Labor Supply">
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Labor Categories</label>
                                        <button type="button" wire:click="addCategory" class="text-xs bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded">Add Category</button>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($categories as $index => $category)
                                            <div class="flex items-center gap-2">
                                                <input type="text" wire:model="categories.{{ $index }}.name" placeholder="Category Name" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2 border">
                                                <input type="number" step="0.01" wire:model="categories.{{ $index }}.billing_rate" placeholder="Rate/Hr" class="block w-24 rounded-md border-gray-300 shadow-sm sm:text-sm p-2 border">
                                                <button type="button" wire:click="removeCategory({{ $index }})" class="text-red-500 p-2">&times;</button>
                                            </div>
                                            @error('categories.'.$index.'.name') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                                        @endforeach
                                    </div>
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
