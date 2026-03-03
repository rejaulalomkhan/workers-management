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
</div>
