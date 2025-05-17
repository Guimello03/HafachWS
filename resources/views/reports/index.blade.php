<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Relatórios Disponíveis') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">

            <!-- Frequência de Alunos -->
            <div class="flex flex-col justify-between p-6 bg-white border rounded-lg shadow-md">
                <div>
                    <div class="mb-4 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347M4.26 10.147A50.636 50.636 0 0 0 1.602 9.334 59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84 50.717 50.717 0 0 1-2.658.814M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold">Frequência de Alunos</h3>
                    <p class="text-sm text-gray-500">Relatório de entrada e saída de alunos por período.</p>
                </div>
               <a href="{{ route('reports.student_attendance.view', ['school_id' => session('school_id')]) }}" class="mt-4 inline-block text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2.5 text-center">
                    Abrir
                </a>
            </div>

            <!-- Frequência de Responsáveis -->
            <div class="flex flex-col justify-between p-6 bg-white border rounded-lg shadow-md">
                <div>
                    <div class="mb-4 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold">Frequência de Responsáveis</h3>
                    <p class="text-sm text-gray-500">Relatório de entrada e saída de responsáveis por período.</p>
                </div>
                <a href="{{ route('reports.guardian_attendance.view', ['school_id' => session('school_id')]) }}" class="mt-4 inline-block text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2.5 text-center">
                    Abrir
                </a>
            </div>

            <!-- Frequência de Funcionários -->
            <div class="flex flex-col justify-between p-6 bg-white border rounded-lg shadow-md">
                <div>
                    <div class="mb-4 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold">Frequência de Funcionários</h3>
                    <p class="text-sm text-gray-500">Relatório de entrada e saída de funcionários por período.</p>
                </div>
                <a href="{{ route('reports.functionary_attendance.view', ['school_id' => session('school_id')]) }}" class="mt-4 inline-block text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2.5 text-center">
                    Abrir
                </a>
            </div>

            <!-- Usuários sem Foto -->
            <div class="flex flex-col justify-between p-6 bg-white border rounded-lg shadow-md">
                <div>
                    <div class="mb-4 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold">Usuários sem Foto</h3>
                    <p class="text-sm text-gray-500">Relatório de usuários sem foto (alunos, responsáveis e funcionários).</p>
                </div>
                <a href="{{ route('reports.users_without_photo.view', ['school_id' => session('school_id')]) }}" class="mt-4 inline-block text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2.5 text-center">
                    Abrir
                </a>
            </div>

        </div>
    </div>
</x-admin-layout>
