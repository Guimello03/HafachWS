<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css">

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


    <!-- Topbar -->
    <header class="flex items-end justify-between w-full h-16 gap-4 px-6 py-4 bg-white border-b shadow-sm">
        <img src="{{ asset('storage/photos/logo.png') }}" alt="Logo EscolaSegura"
            class="pl-7 h-11 pt-3 max-w-[140px] object-contain" />
        <div class="flex items-center gap-4 ml-auto">
            {{-- Componente: school-switcher --}}
            <x-school-switcher :school="activeSchool()" :client="activeSchool()?->client" :clients="$clients" />
            {{-- Divisória vertical --}}
            <div class="w-px h-6 gap-4 bg-gray-300"></div>
        </div>
        <!-- Botão de perfil com dropdown -->
        <div class="relative">
            <button id="profileMenuBtn" class="flex items-center gap-2 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </button>

            <div id="profileDropdown" class="absolute right-0 z-50 hidden w-56 mt-2 bg-white rounded shadow-lg">
                <div class="px-4 py-3 text-sm text-gray-700 border-b">
                    <div class="font-semibold">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">
                        @if (Auth::user()->hasRole('super_admin'))
                            Super Admin
                        @elseif(Auth::user()->hasRole('client_admin'))
                            Administrador de Rede
                        @elseif(Auth::user()->hasRole('school_director'))
                            Diretor(a)
                        @else
                            Usuário
                        @endif
                    </div>
                </div>

                @if (Auth::user()->hasRole('super_admin'))
                    <a href="{{ route('admin.dashboard') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-gray-100">Administração
                        Interna</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-red-100">Sair</button>
                </form>
            </div>
        </div>

        <script>
            const btn = document.getElementById('profileMenuBtn');
            const dropdown = document.getElementById('profileDropdown');
            document.addEventListener('click', function(e) {
                if (btn.contains(e.target)) {
                    dropdown.classList.toggle('hidden');
                } else {
                    dropdown.classList.add('hidden');
                }
            });
        </script>
    </header>
    <!-- Layout principal -->
    <div class="flex h-[calc(100vh-4rem)] overflow-hidden">
        <!-- Sidebar -->
        <!-- Sidebar -->
        <aside class="hidden w-56 bg-white border-r shadow-md md:block">
            <nav class="flex flex-col gap-4 px-6 mt-6 text-sm">

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-md transition 
                   {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-gray-100' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6 {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                    </svg>
                    Dashboard
                </a>

                <!-- Alunos -->
                <a href="{{ route('students.index') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-md transition 
                   {{ request()->routeIs('students.*') ? 'text-blue-600 bg-gray-100' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6 {{ request()->routeIs('students.*') ? 'text-blue-600' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    Alunos
                </a>

                <!-- Responsáveis -->
                <a href="{{ route('guardians.index') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-md transition 
                   {{ request()->routeIs('guardians.*') ? 'text-blue-600 bg-gray-100' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6 {{ request()->routeIs('guardians.*') ? 'text-blue-600' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Responsáveis
                </a>

                <!-- Funcionários -->
                <a href="{{ route('functionaries.index') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-md transition 
                   {{ request()->routeIs('functionaries.*') ? 'text-blue-600 bg-gray-100' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6 {{ request()->routeIs('functionaries.*') ? 'text-blue-600' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Funcionários
                </a>
                <!-- Escola -->
                <a href="{{ route('school.dashboard') }}"
                class="flex items-center gap-2 px-2 py-2 rounded-md transition 
                     {{ request()->routeIs('school.*') ? 'text-blue-600 bg-gray-100' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }}">                    
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-6 {{ request()->routeIs('school.*') ? 'text-blue-600' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                      </svg>
                      Escola
                </a>
            </nav>
        </aside>



        <!-- Main Content -->

        <div class="flex flex-col flex-1 w-full min-h-full overflow-y-auto bg-gray-50">

            <main class="flex-1 p-2">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1/dist/cleave.min.js"></script>




    @livewireScripts
    @stack('scripts')



</body>

</html>
