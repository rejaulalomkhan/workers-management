<div class="fixed inset-0 min-h-screen w-full flex items-center justify-center overflow-y-auto bg-slate-50">
    {{-- Decorative Background Elements (Simple and Premium) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[100px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-500/5 blur-[100px]"></div>
    </div>

    {{-- Main Container --}}
    <div class="relative w-full min-h-screen sm:min-h-0 sm:max-w-[420px] flex flex-col justify-center sm:block">
        
        {{-- Card / Branding Section --}}
        <div class="bg-white/80 backdrop-blur-xl sm:rounded-3xl sm:shadow-[0_20px_50px_rgba(15,37,90,0.08)] sm:border sm:border-white/50 overflow-hidden transition-all duration-300">
            
            <div class="p-8 sm:p-10">
                {{-- Header --}}
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white shadow-xl shadow-slate-200/50 mb-6 group transition-transform hover:scale-105 border border-slate-50">
                        @php 
                            $branding = \App\Models\Setting::first(); 
                            $shortName = $branding->short_name ?? 'LaborLog';
                            $companyName = $branding->company_name ?? 'LaborLog Software';
                            $initials = substr($shortName, 0, 2);
                        @endphp
                        @if($branding && $branding->logo_path)
                            <img src="{{ asset($branding->logo_path) }}" alt="Logo" class="w-14 h-14 object-contain">
                        @else
                            <span class="text-blue-600 font-black text-2xl tracking-tighter">{{ strtoupper($initials) }}</span>
                        @endif
                    </div>
                    <h4 class="text-[13px] sm:text-base font-bold text-slate-800 tracking-tight mb-1 px-1 uppercase leading-tight">
                        {{ $companyName }}
                    </h4>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[1px]">Software Login</p>
                </div>

                {{-- Form --}}
                <form wire:submit="login" class="space-y-5">
                    <div class="group">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                            </span>
                            <input type="email" wire:model="email" 
                                   class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-transparent rounded-2xl text-slate-800 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm font-medium border" 
                                   placeholder="name@example.com"
                                   required autofocus>
                        </div>
                        @error('email') <span class="text-rose-500 text-[11px] mt-1.5 block font-bold ml-1 uppercase tracking-wider">{{ $message }}</span> @enderror
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" wire:model="password" 
                                   class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-transparent rounded-2xl text-slate-800 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm font-medium border" 
                                   placeholder="••••••••"
                                   required>
                        </div>
                        @error('password') <span class="text-rose-500 text-[11px] mt-1.5 block font-bold ml-1 uppercase tracking-wider">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between py-1">
                        <label for="remember" class="inline-flex items-center group cursor-pointer">
                            <input id="remember" wire:model="remember" type="checkbox" 
                                   class="w-4.5 h-4.5 text-blue-600 bg-slate-50 border-slate-200 rounded-lg focus:ring-blue-500 focus:ring-offset-0 transition cursor-pointer">
                            <span class="ml-2.5 text-slate-600 text-sm font-semibold select-none group-hover:text-slate-800 transition">প্রবেশের তথ্য মনে রাখুন</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="relative w-full group overflow-hidden bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-[2px] text-xs shadow-xl shadow-slate-900/10 hover:shadow-slate-900/20 transition-all active:scale-[0.98]">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <span wire:loading.remove wire:target="login" class="relative z-10 flex items-center justify-center gap-2">
                                Sign In
                                <svg class="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                            <span wire:loading.inline-flex wire:target="login" class="relative z-10 items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Please wait...
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Footer / Credits --}}
                <div class="mt-12 text-center">
                    <div class="flex items-center justify-center gap-4 mb-4 opacity-30">
                        <div class="h-px w-8 bg-slate-400"></div>
                        <span class="text-[10px] font-black uppercase tracking-[3px] text-slate-500">Developed by</span>
                        <div class="h-px w-8 bg-slate-400"></div>
                    </div>
                    
                    <a href="https://wa.me/+8801916628339" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-100 bg-slate-50/50 text-slate-500 hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all duration-300">
                        <span class="text-xs font-black tracking-wide">Arman Azij</span>
                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Mobile Contact Bar --}}
        <div class="sm:hidden mt-auto pb-8 pt-4 px-8 text-center">
            <a href="tel:+8801916628339" class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition tracking-tighter uppercase whitespace-nowrap">
                Contact Technical Support: +880 1916-628339
            </a>
        </div>
    </div>
</div>
