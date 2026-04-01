<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Login - ' . (\App\Models\Setting::first()->short_name ?? 'LaborLog') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $branding = \App\Models\Setting::first(); @endphp
    @if($branding && $branding->logo_path)
        <link rel="icon" href="{{ asset($branding->logo_path) }}">
    @endif
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased flex flex-col items-center justify-center min-h-screen">
    {{ $slot }}
    @livewireScripts
</body>
</html>
