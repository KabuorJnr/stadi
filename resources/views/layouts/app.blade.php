<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stadi') — {{ config('stadium.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        tm:      { 50:'#eff6ff', 100:'#dbeafe', 200:'#bfdbfe', 400:'#3b82f6', 500:'#026cdf', 600:'#0256b3', 700:'#024a96', 800:'#1e3a5f', 900:'#0f1b2d' },
                        dark:    { DEFAULT:'#121212', 50:'#1a1a2e', 100:'#16213e', 200:'#1a1a2e', 300:'#252545', 400:'#2d2d4a', 500:'#3a3a5c' },
                        accent:  { DEFAULT:'#026cdf', light:'#3b9eff', dark:'#0256b3' },
                        success: { DEFAULT:'#22c55e', light:'#bbf7d0' },
                        gold:    { 400:'#c9a84c', 500:'#b8941f' },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .tm-gradient { background: linear-gradient(135deg, #0f1b2d 0%, #1a1a2e 40%, #16213e 100%); }
        .card-hover { transition: transform .2s, box-shadow .2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.3); }
        .text-gradient { background: linear-gradient(135deg, #3b9eff, #026cdf); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-dark font-sans text-white antialiased">

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
