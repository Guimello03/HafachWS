<x-guest-layout>

    <div class="relative flex flex-col items-center justify-center min-h-screen overflow-hidden bg-gray-50">

        {{-- Imagem decorativa no fundo --}}
        

        {{-- Conteúdo principal --}}
        <div class="z-10 w-full max-w-md px-6 py-10 text-center rounded-lg shadow-md">
            {{-- Logo --}}
            <img src="{{ asset('storage/photos/logo-login.png') }}
" alt="Logo" class="h-24 mx-auto mb-6 ">

            <h1 class="text-2xl font-bold text-gray-900">Acesse sua conta</h1>

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4 text-left">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Seu Email</label>
                    <input type="email" name="email" required autofocus
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Sua Senha</label>
                    <div class="flex items-center justify-between">
                        <input type="password" name="password" required
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    <label for="remember_me" class="block ml-2 text-sm text-gray-700">
                        Lembrar-me neste dispositivo
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-2 text-white transition bg-blue-500 rounded-md hover:bg-indigo-500">
                    Continuar
                </button>
            </form>

            <p class="mt-8 text-xs text-gray-500">
                2025 Ws escola segura – controle de acesso Escolar.<br>Todos os direitos reservados.
            </p>
        </div>
    </div>
</x-guest-layout>
