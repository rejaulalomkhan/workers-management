<div class="w-full max-w-md bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] p-10 mx-4 border border-gray-100">
    <div class="text-center mb-8">
        @php $branding = \App\Models\Setting::first(); @endphp
        <div class="flex items-center justify-center gap-3 mb-2">
            @if($branding && $branding->logo_path)
                <img src="{{ asset($branding->logo_path) }}" alt="Logo" class="h-10 w-auto object-contain filter drop-shadow-sm">
            @endif
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-blue-500">FHTS Software</h2>
        </div>
        <p class="text-gray-500 text-sm font-medium tracking-wide">Enter your credentials to access</p>
    </div>

    <form wire:submit="login" class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
            <input type="email" wire:model="email" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border !outline-none transition" required autofocus>
            @error('email') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input type="password" wire:model="password" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border !outline-none transition" required>
            @error('password') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" wire:model="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                    Remember me
                </label>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <span wire:loading.remove wire:target="login">Sign In</span>
                <span wire:loading.inline-flex wire:target="login" class="items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                </span>
            </button>
        </div>
    </form>

    {{-- Developer Credit --}}
    <div class="mt-8 pt-5 border-t border-gray-100 text-center">
        <p class="text-[11px] text-gray-400 leading-relaxed">
            Developed by <span class="text-red-400">♥</span>
            <a href="https://wa.me/+8801916628339" target="_blank"
               class="font-semibold text-blue-500 hover:text-blue-700 transition-colors">
               Arman Azij
            </a>
        </p>
        <a href="https://wa.me/+8801916628339" target="_blank"
           class="inline-flex items-center gap-1.5 mt-1.5 text-[11px] text-gray-400 hover:text-green-600 transition-colors">
            <svg class="w-3.5 h-3.5 text-green-500" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            +880 1916-628339
        </a>
    </div>
</div>
