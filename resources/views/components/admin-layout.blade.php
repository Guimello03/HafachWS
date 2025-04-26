<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="font-sans antialiased bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="hidden w-64 bg-white border-r shadow-md md:block">
            <div class="p-6 text-xl font-bold border-b">📘 EscolaSegura</div>
            <nav class="flex flex-col gap-4 px-6 mt-6 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-700 transition hover:text-blue-600">Dashboard</a>
                <a href="{{ route('students.index') }}" class="text-gray-700 transition hover:text-blue-600">Alunos</a>
                <a href="{{ route('guardians.index') }}"
                    class="text-gray-700 transition hover:text-blue-600">Responsáveis</a>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex flex-col flex-1 w-full min-h-screen">
            <!-- Topbar -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm">
                <h1 class="text-lg font-semibold text-gray-800">Sistema Escolar</h1>
                <div class="text-sm text-gray-600">
                    Logado como: <span class="font-medium">{{ Auth::user()->name }}</span>
                </div>
            </header>

            <!-- Conteúdo -->
            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}


            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>
