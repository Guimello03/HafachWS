<x-admin-layout>
    
<div x-data="photoModal()">

    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush


    <x-breadcrumb :items="$breadcrumbs" />





    <x-slot name="header">


        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Lista de Alunos') }}
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
        <h2 class="px-6 mb-4 text-xl font-bold text-gray-900 pd-6">Listagem de Alunos</h2>

        {{-- Filtro e botão --}}
        <div class="px-6 pd-6">
            <div class="p-4 bg-white border-b border-gray-200 rounded-lg shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <form method="GET" action="{{ route('students.index') }}">
                        <input type="text" id="alunoSearch" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nome ou matrícula"
                            class="h-10 px-4 py-2  shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-[400px] border rounded-lg" />
                    </form>

                    <a href="{{ route('students.create') }}"
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
                            <th class="px-4 py-3 text-left">Matrícula</th>
                            <th class="px-4 py-3 text-left">Nascimento</th>
                            <th class="px-4 py-3 text-center ">Foto</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($students as $index => $student)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-4">{{ $student->name }}</td>
                                <td class="px-4 py-4">{{ $student->registration_number }}</td>
                                <td class="px-4 py-4">
                                    {{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <button
                                        @click="openModal(@js($student->uuid), @js($student->photo_path ? asset('storage/' . $student->photo_path) : ''))"
                                        class="rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        title="Ver Foto">

                                        @if ($student->photo_path)
                                            <img src="{{ asset('storage/' . $student->photo_path) }}"
                                                alt="Foto de {{ $student->name }}"
                                                class="object-cover w-10 h-10 text-center transition-transform rounded-full hover:scale-110">
                                        @else
                                            <div
                                                class="flex items-center justify-center w-10 h-10 text-red-600 transition-transform bg-red-100 rounded-full hover:scale-110">
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
                                        <a href="{{ route('students.edit', $student) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 transition rounded-full hover:text-white hover:bg-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');">
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
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>


    {{-- Modal --}}
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="closeModal" class="relative p-6 bg-white rounded-lg shadow-lg w-96">

            <!-- Botão X flutuante para excluir a foto -->
            <button x-show="photoPreview && studentUuid" x-bind:disabled="!studentUuid || !photoPreview || isDeleting"
                @click="deletePhoto" title="Excluir foto"
                class="absolute flex items-center justify-center w-8 h-8 text-red-600 transition-colors duration-200 rounded-full top-4 right-4 hover:text-white hover:bg-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="mb-4 text-lg font-semibold text-gray-800">Foto do Aluno</h3>

            <template x-if="photoPreview">
                <img :src="photoPreview" alt="Foto do aluno"
                    class="object-cover w-full mb-4 border rounded h-60">
            </template>

            <template x-if="!photoPreview">
                <div
                    class="flex items-center justify-center w-full mb-4 text-gray-500 bg-gray-100 border rounded h-60">
                    <span class="text-lg font-medium">Sem Foto</span>
                </div>
            </template>

            <form x-ref="form" @submit.prevent="submitForm" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <label for="photo"
                    class="flex items-center justify-center w-full px-4 py-2 mb-2 font-semibold text-white bg-blue-600 rounded cursor-pointer hover:bg-blue-700">
                    Adicionar Foto
                    <input type="file" name="photo" id="photo" class="hidden" accept="image/*"
                        @change="previewPhoto" required>
                </label>
                <div x-show="showToast" x-cloak
                    class="fixed z-50 px-4 py-2 text-sm font-medium text-white transition-opacity bg-green-600 rounded shadow-md bottom-6 right-6"
                    x-transition>
                    <span x-text="toastMessage"></span>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Salvar
                        Foto</button>
                    <button type="button" @click="closeModal"
                        class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">Cancelar</button>
                    <div x-show="showToast" x-cloak
                        class="fixed z-50 px-4 py-2 text-sm font-medium text-white transition-opacity bg-green-600 rounded shadow-md bottom-6 right-6"
                        x-transition>
                        <span x-text="toastMessage"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Alpine Controller --}}
    <script>
        function photoModal() {
            return {
                isOpen: false,
                studentUuid: null,
    
                updatePhotoUrl: '{{ url('/students') }}',
                removePhotoUrl: '{{ url('/students') }}',
    
                photoUrl: null,
                photoPreview: null,
    
                isDeleting: false,
                showToast: false,
                toastMessage: '',
    
                openModal(uuid, url) {
                    this.isOpen = true;
    
                    setTimeout(() => {
                        this.studentUuid = uuid;
                        this.photoUrl = url || null;
                        this.photoPreview = url || null;
                    }, 50);
                },
    
                previewPhoto(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.photoPreview = URL.createObjectURL(file);
                    }
                },
    
                closeModal() {
                    this.isOpen = false;
                    this.studentUuid = null;
                    this.photoUrl = null;
                    this.photoPreview = null;
                    this.clearFileInput();
                },
    
                clearFileInput() {
                    const input = document.getElementById('photo');
                    if (input) input.value = '';
                },
    
                async submitForm(event) {
                    event.preventDefault();
    
                    const fileInput = document.getElementById('photo');
                    const file = fileInput.files[0];
    
                    if (!file || !this.studentUuid) {
                        alert("Imagem ou aluno inválido.");
                        return;
                    }
    
                    const formData = new FormData();
                    formData.append('photo', file);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PUT');
    
                    const url = `${this.updatePhotoUrl}/${this.studentUuid}/photo`;
    
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData
                        });
    
                        if (!response.ok) throw new Error('Erro ao enviar imagem');
    
                        this.showToast = true;
                        this.toastMessage = 'Foto enviada com sucesso!';
    
                        setTimeout(() => this.showToast = false, 3000);
                        this.closeModal();
                    } catch (error) {
                        alert('Erro ao enviar a foto.');
                    }
                },
    
                async deletePhoto() {
                    if (!this.studentUuid) {
                        alert("ID do aluno não encontrado.");
                        return;
                    }
    
                    const confirmed = confirm('Tem certeza que deseja excluir a foto?');
                    if (!confirmed) return;
    
                    this.isDeleting = true;
    
                    try {
                        const response = await fetch(`${this.removePhotoUrl}/${this.studentUuid}/remove-photo`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
    
                        if (!response.ok) throw new Error('Erro ao excluir a foto');
    
                        window.location.href = '{{ route('students.index') }}';
    
                    } catch (e) {
                        alert('Erro ao excluir a foto.');
                    } finally {
                        this.isDeleting = false;
                    }
                }
            }
        }
    </script>
    
    
</div>
</x-admin-layout>

