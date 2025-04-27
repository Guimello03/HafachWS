<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
  
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">

    <!-- Topbar -->
    <header class="flex items-center justify-between w-full h-16 px-6 py-4 bg-white border-b shadow-sm">
        <img src="{{ asset('storage/photos/logo.png') }}" alt="Logo EscolaSegura" class="  pl-7 h-11 max-w-[140px] object-contain" />
        
        <div class="text-sm text-gray-600">
            Logado como: <span class="font-medium">{{ Auth::user()->name }}</span>
        </div>
    </header>

    <!-- Layout principal -->
    <div class="flex h-[calc(100vh-4rem)] overflow-hidden">
        <!-- Sidebar -->
        <aside class="hidden w-56 bg-white border-r shadow-md md:block">

            <nav class="flex flex-col gap-4 px-6 mt-6 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-700 transition hover:text-blue-600">Dashboard</a>
                <a href="{{ route('students.index') }}" class="text-gray-700 transition hover:text-blue-600">Alunos</a>
                <a href="{{ route('guardians.index') }}" class="text-gray-700 transition hover:text-blue-600">Responsáveis</a>
                <a href="{{ route('functionaries.index') }}" class="text-gray-700 transition hover:text-blue-600">Funcionários</a>
                
            </nav>
        </aside>

        <!-- Main Content -->

        <div class="flex flex-col flex-1 w-full min-h-full overflow-y-auto bg-gray-50">

            <main class="flex-1 p-2">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>
