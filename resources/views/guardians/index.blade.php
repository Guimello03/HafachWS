<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />
    <div x-data="photoModal()">

        @push('styles')
            <style>
                [x-cloak] {
                    display: none !important;
                }
            </style>
        @endpush

        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Lista de Responsáveis') }}
            </h2>
        </x-slot>

        {{-- Mensagens --}}
        @if (session('success'))
            <div class="p-4 text-green-800 bg-green-100 border border-green-300 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mt-4 text-red-800 bg-red-100 border border-red-300 rounded shadow">
                <ul class="pl-5 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="px-1 pt-2">
            <h2 class="px-6 mb-4 text-xl font-bold text-gray-900 pd-6">Listagem de Responsáveis</h2>

            {{-- Filtro e botão --}}
            <div class="px-6">
                <div class="p-4 bg-white border-b border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <form method="GET" action="{{ route('guardians.index') }}">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por nome ou CPF"
                                class="h-10 px-4 py-2  shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-[400px] border rounded-lg" />
                        </form>
                        <a href="{{ route('guardians.create') }}"
                            class="flex items-center justify-center h-10 px-4 py-2 font-semibold text-white bg-blue-500 border rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                            Cadastrar
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tabela --}}
            <div class="px-6 py-6">
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <table class="min-w-full bg-white divide-y divide-gray-200 rounded shadow">
                        <thead class="text-gray-600 bg-gray-100 ">
                            <tr>
                                <th class="px-4 py-3 text-left">Nome</th>
                                <th class="px-4 py-3 text-center">CPF</th>
                                <th class="px-4 py-3 text-left">Nascimento</th>
                                <th class="px-4 py-3 text-center">Email</th>
                                <th class="px-4 py-3 text-left">Telefone</th>
                                <th class="px-4 py-3 text-center">Foto</th>
                                <th class="px-4 py-3 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            {{-- Linha por responsável --}}
                            @foreach ($guardians as $index => $guardian)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-4">{{ $guardian->name }}</td>
                                    <td class="px-4 py-4 text-center">{{ $guardian->cpf }}</td>
                                    <td class="px-4 py-4">
                                        {{ \Carbon\Carbon::parse($guardian->birth_date)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-4 text-center">{{ $guardian->email }}</td>
                                    <td class="px-4 py-4">{{ $guardian->phone }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <button
                                            @click="openModal(@js($guardian->uuid), @js($guardian->photo_path ? asset('storage/' . $guardian->photo_path) : ''))"
                                            class="focus:outline-none">
                                            @if ($guardian->photo_path)
                                                <img src="{{ asset('storage/' . $guardian->photo_path) }}"
                                                    class="object-cover w-10 h-10 transition rounded-full hover:scale-110">
                                            @else
                                                <div
                                                    class="flex items-center justify-center w-10 h-10 text-red-600 transition bg-red-100 rounded-full hover:scale-110">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 7h2l2-3h10l2 3h2a1 1 0 011 1v11a1 1 0 01-1 1H3a1 1 0 01-1-1V8a1 1 0 011-1zm9 3a4 4 0 100 8 4 4 0 000-8z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </button>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('guardians.edit', $guardian) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 text-blue-600 transition rounded-full hover:text-white hover:bg-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('guardians.destroy', $guardian) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir este responsável?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 transition rounded-full hover:text-white hover:bg-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex justify-end ">
                        {{ $guardians->links() }}
                    </div>
                </div>
            </div>
        </div>

       <!-- Modal de Foto -->
<div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.away="closeModal" class="relative p-6 bg-white rounded-lg shadow-lg w-96">
        <button x-show="photoPreview && guardianId" @click="deletePhoto"
            class="absolute flex items-center justify-center w-8 h-8 text-red-600 transition-colors duration-200 rounded-full top-4 right-4 hover:text-white hover:bg-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <h3 class="mb-4 text-lg font-semibold text-gray-800">Foto do Responsável</h3>

        <template x-if="photoPreview">
            <img :src="photoPreview" class="object-cover w-full mb-4 border rounded h-60">
        </template>

        <template x-if="!photoPreview">
            <div
                class="flex items-center justify-center w-full mb-4 text-gray-500 bg-gray-100 border rounded h-60">
                Sem Foto
            </div>
        </template>

        <!-- FORMULÁRIO CORRETO -->
        <form :action="`${updatePhotoUrl}/${guardianId}/photo`" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="photo"
                class="flex items-center justify-center w-full px-4 py-2 mb-2 font-semibold text-white bg-blue-600 rounded cursor-pointer hover:bg-blue-700">
                Adicionar Foto
                <input type="file" name="photo" id="photo" class="hidden" accept="image/*"
                    @change="previewPhoto" required>
            </label>

            <div class="flex justify-between mt-4">
                <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                    Salvar Foto
                </button>
                <button type="button" @click="closeModal"
                    class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

        {{-- Alpine Controller --}}
        <script>
            function photoModal() {
                return {
                    isOpen: false,
                    guardianId: null,
                    updatePhotoUrl: '{{ url('/guardians') }}',
                    removePhotoUrl: '{{ url('/guardians') }}',
                    photoPreview: null,
                    isDeleting: false,
        
                    openModal(uuid, url) {
                        this.guardianId = uuid;
                        this.photoPreview = url || null;
                        this.isOpen = true;
                    },
        
                    previewPhoto(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.photoPreview = URL.createObjectURL(file);
                        }
                    },
        
                    closeModal() {
                        this.isOpen = false;
                        this.guardianId = null;
                        this.photoPreview = null;
                        const fileInput = document.getElementById('photo');
                        if (fileInput) fileInput.value = '';
                    },
        
                    async deletePhoto() {
                        if (!this.guardianId) return;
        
                        const confirmed = confirm('Deseja realmente excluir a foto?');
                        if (!confirmed) return;
        
                        try {
                            const response = await fetch(`${this.removePhotoUrl}/${this.guardianId}/remove-photo`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                }
                            });
        
                            if (!response.ok) throw new Error();
        
                            window.location.href = '{{ route('guardians.index') }}';
                        } catch {
                            alert('Erro ao excluir a foto.');
                        }
                    }
                }
            }
        </script>
    </div>

</x-admin-layout>
