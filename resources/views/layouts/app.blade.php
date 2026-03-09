<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stadi') — {{ config('stadium.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand:   { 50:'#eef9ee', 100:'#d5f0d5', 400:'#4caf50', 500:'#2e7d32', 600:'#1b5e20', 700:'#0d3d12' },
                        gold:    { 400:'#c9a84c', 500:'#b8941f' },
                        pitch:   { DEFAULT:'#1a472a', light:'#2d6a3e' }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-800 antialiased">

    @hasSection('no-nav')
    @else
        @include('components.nav')
    @endif

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
