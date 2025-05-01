<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center relative overflow-hidden">

        {{-- Imagem decorativa no fundo --}}
        

        {{-- Conteúdo principal --}}
        <div class="w-full max-w-md px-6 py-10  z-10 text-center shadow-md rounded-lg">
            {{-- Logo --}}
            <img src="{{ asset('storage/photos/logo-login.png') }}
" alt="Logo" class="mx-auto mb-6 h-24 ">

            <h1 class="text-2xl font-bold text-gray-900">Acesse sua conta</h1>

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4 text-left">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Seu Email</label>
                    <input type="email" name="email" required autofocus
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Sua Senha</label>
                    <div class="flex justify-between items-center">
                        <input type="password" name="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                        Lembrar-me neste dispositivo
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-indigo-500 transition">
                    Continuar
                </button>
            </form>

            <p class="text-xs text-gray-500 mt-8">
                2025 Ws escola segura – controle de acesso Escolar.<br>Todos os direitos reservados.
            </p>
        </div>
    </div>
</x-guest-layout>
