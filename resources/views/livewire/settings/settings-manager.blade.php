<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Settings</h2>
    </div>

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" wire:model="company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                    @error('company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company Name (Arabic)</label>
                    <input type="text" wire:model="company_name_arabic" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">TRN</label>
                    <input type="text" wire:model="trn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" wire:model="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Currency</label>
                    <input type="text" wire:model="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">VAT Rate (%)</label>
                    <input type="number" step="0.01" wire:model="vat_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                    @error('vat_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea wire:model="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"></textarea>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Bank Details
                        <span class="text-gray-400 text-xs font-normal ml-1">(displayed at the bottom of invoices)</span>
                    </label>
                    <textarea wire:model="bank_details" rows="4" placeholder="Bank Name: ...&#10;Account Name: ...&#10;Account No: ...&#10;IBAN: ..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border font-mono"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo (Watermark)</label>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 relative group">
                            @if ($new_logo)
                                <img src="{{ $new_logo->temporaryUrl() }}" class="h-16 rounded border border-gray-200">
                                <button type="button" wire:click="$set('new_logo', null)" class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-sm hover:bg-red-200" title="Remove Preview">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @elseif ($existing_logo)
                                <img src="{{ asset($existing_logo) }}" class="h-16 rounded border border-gray-200" onerror="this.src='https://ui-avatars.com/api/?name=Logo&color=7F9CF5&background=EBF4FF'">
                                <button type="button" wire:click="removeLogo" class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-sm hover:bg-red-200" title="Remove Main Logo">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @else
                                <div class="h-16 w-16 rounded border border-gray-200 bg-gray-50 flex items-center justify-center text-gray-400 text-xs text-center p-2">No Logo</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" wire:model="new_logo" id="new_logo_input" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div wire:loading wire:target="new_logo" class="text-xs text-blue-500 mt-1">Uploading preview...</div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Header Image</label>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 relative group">
                            @if ($new_header_image)
                                <img src="{{ $new_header_image->temporaryUrl() }}" class="h-16 rounded border border-gray-200 object-contain max-w-[150px]">
                                <button type="button" wire:click="$set('new_header_image', null)" class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-sm hover:bg-red-200" title="Remove Preview">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @elseif ($existing_header_image)
                                <img src="{{ asset($existing_header_image) }}" class="h-16 rounded border border-gray-200 object-contain max-w-[150px]" onerror="this.style.display='none'">
                                <button type="button" wire:click="removeHeaderImage" class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-sm hover:bg-red-200" title="Remove Header">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @else
                                <div class="h-16 w-32 rounded border border-gray-200 bg-gray-50 flex items-center justify-center text-gray-400 text-xs text-center">No Header</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" wire:model="new_header_image" id="new_header_img_input" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div wire:loading wire:target="new_header_image" class="text-xs text-blue-500 mt-1">Uploading preview...</div>
                        </div>
                    </div>
                </div>
                {{-- Footer Image --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Footer Image <span class="text-gray-400 text-xs font-normal">(full-width image shown at the bottom of PDF invoices)</span></label>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 relative group">
                            @if ($new_footer_image)
                                <img src="{{ $new_footer_image->temporaryUrl() }}" class="h-16 rounded border border-gray-200 object-contain max-w-[200px]">
                                <button type="button" wire:click="$set('new_footer_image', null)" class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-sm hover:bg-red-200" title="Remove Preview">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @elseif ($existing_footer_image)
                                <img src="{{ asset($existing_footer_image) }}" class="h-16 rounded border border-gray-200 object-contain max-w-[200px]" onerror="this.style.display='none'">
                                <button type="button" wire:click="removeFooterImage" class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-sm hover:bg-red-200" title="Remove Footer Image">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @else
                                <div class="h-16 w-40 rounded border border-gray-200 bg-gray-50 flex items-center justify-center text-gray-400 text-xs text-center">No Footer Image</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" wire:model="new_footer_image" id="new_footer_img_input" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div wire:loading wire:target="new_footer_image" class="text-xs text-blue-500 mt-1">Uploading preview...</div>
                            <p class="text-xs text-gray-400 mt-1">Recommended: wide landscape image (e.g. 1200×150px)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
