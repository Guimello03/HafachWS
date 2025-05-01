<x-guest-layout>

    
    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
        <div class="w-full max-w-xl bg-white rounded-lg shadow p-8 space-y-6">
            <h2 class="text-2xl font-bold text-center text-gray-800">Selecione uma Escola</h2>
    
            <form method="POST" action="{{ route('select.school.store') }}" class="space-y-4">
                @csrf
    
                <div class="grid gap-4">
                    @foreach($schools as $school)
                        <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="radio" name="school_id" value="{{ $school->id }}"
                                   class="text-indigo-600 focus:ring-indigo-500" required>
    
                            <div class="flex items-start gap-3">
                                {{-- Ícone SVG --}}
                                <div class="text-indigo-600 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                </div>
    
                                {{-- Dados da escola --}}
                                <div>
                                    <div class="text-gray-800 font-medium">{{ $school->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $school->cnpj ?? 'Cidade não informada' }}
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
    
                @error('school_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
    
                <button type="submit"
                        class="w-full mt-4 bg-blue-500 text-white py-2 rounded-md hover:bg-indigo-500 transition">
                    Entrar na Escola
                </button>
            </form>
        </div>
    </div>
    
</x-guest-layout>