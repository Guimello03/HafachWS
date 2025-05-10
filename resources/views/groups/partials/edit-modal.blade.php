<div 
    :key="window.alpineKey"
    x-data="editGroupModal(window.schoolDevices)" 
    x-init="init();
             window.addEventListener('open-edit-modal', e => openEdit(e.detail));
             window.addEventListener('close-modal', () => editOpen = false);"
    x-show="editOpen" 
    x-cloak 
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <div class="w-full max-w-2xl overflow-hidden bg-white rounded-lg shadow-lg">

        <form :action="`/device_groups/${editingGroupId}`" method="POST" @submit="handleEditSubmit($event)"
            class="flex flex-col max-h-[90vh]">
            @csrf
            @method('PUT')

            {{-- Conteúdo com rolagem --}}
            <div class="px-6 py-4 space-y-6 overflow-y-auto">

                {{-- Título --}}
                <h2 class="mb-4 text-lg font-semibold text-gray-800">Editar Grupo</h2>

                {{-- Nome do Grupo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nome do Grupo</label>
                    <input type="text" x-model="editGroupName" name="name"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <hr class="my-4">

                {{-- Criar novo equipamento --}}
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-700">Novo Equipamento</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Modelo</label>
                        <select x-model="editNewDevice.model"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione o modelo...</option>
                            <option value="controlid">ControlId</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2 mt-4" x-show="editNewDevice.model" x-cloak>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Device ID</label>
                            <input type="text" x-model="editNewDevice.device_id" maxlength="16"
                                placeholder="Digite o Device ID"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <button type="button" @click="addNewEditDevice()" :disabled="!editNewDevice.device_id"
                            class="px-3 py-2 mt-5 text-sm text-white bg-green-600 rounded hover:bg-green-700 disabled:opacity-50">
                            +
                        </button>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Vincular Equipamento Existente --}}
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-700">Vincular Equipamento Existente</h3>
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Equipamento</label>
                            <select x-model="selectedToAdd"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecione...</option>
                                <template x-for="device in availableDevices" :key="device.uuid">
                                    <option :value="device.uuid" x-text="device.label"></option>
                                </template>
                            </select>
                        </div>

                        <button type="button" @click="addToEditDevices()" :disabled="!selectedToAdd"
                            class="px-3 py-2 mt-5 text-sm text-white bg-blue-600 rounded hover:bg-blue-700 disabled:opacity-50">
                            +
                        </button>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Lista de Equipamentos Vinculados --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Equipamentos Vinculados</h3>
                    <ul class="mt-2 space-y-2">
                        <template x-for="(device, index) in editDevices" :key="index">
                            <li class="flex items-center justify-between px-3 py-2 bg-gray-100 rounded">
                                <span x-text="device.label"></span>
                                <input type="hidden" name="devices[]" :value="JSON.stringify(device)">
                                <button type="button" @click="removeEditDevice(index)">
                                    <svg class="w-4 h-4 text-red-500 hover:text-red-700" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- Botões fixos --}}
            <div class="flex justify-end px-6 py-4 pt-4 mt-6 bg-white border-t">
                <button type="button" @click="editOpen = false"
                    class="px-4 py-2 text-gray-700 bg-gray-100 border rounded hover:bg-gray-200">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex items-center justify-center h-10 px-4 py-2 font-semibold text-white bg-blue-500 border rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
</div>




<script>
    function editGroupModal(devicesFromSchool = []) {
        return {
            editOpen: false,
            editingGroupId: null,
            editGroupName: '',
            editDevices: [],
            availableDevices: [],
            selectedToAdd: '',
            schoolDevices: [],
            editNewDevice: {
                model: '',
                device_id: ''
            },

            init() {
                this.schoolDevices = devicesFromSchool.map(device => ({
                    ...device,
                    label: device.label ?? `${device.model} - ${device.serial_number}`
                }));


                // Ouvindo evento do botão de editar
                window.addEventListener('open-edit-modal', (event) => {
                    this.openEdit(event.detail);
                });
            },

            openEdit(group) {
                this.editingGroupId = group.uuid;
                this.editGroupName = group.name;

                // 1. Dispositivos já vinculados ao grupo
                this.editDevices = (group.devices || []).map(d => ({
                    uuid: d.uuid,
                    label: `${d.model} - ${d.serial_number}`,
                    type: 'existing'
                }));

                // 2. Filtra apenas os que não estão vinculados
                this.availableDevices = devicesFromSchool.filter(dev => {
                    const devUuid = String(dev.uuid);
                    return !this.editDevices.some(used => String(used.uuid) === devUuid);
                });

                this.selectedToAdd = '';
                this.editNewDevice = {
                    model: '',
                    device_id: ''
                };

                this.editOpen = true;

                // 🔍 DEBUG opcional
                console.log('[EDIT] editDevices:', this.editDevices);
                console.log('[EDIT] availableDevices:', this.availableDevices);
                console.log('[EDIT] total from school:', devicesFromSchool);
            },

            addToEditDevices() {
                if (!this.selectedToAdd) return;

                const device = this.availableDevices.find(d => String(d.uuid) === String(this.selectedToAdd));
                if (!device) return;

                this.editDevices.push({
                    uuid: device.uuid,
                    label: device.label,
                    type: 'existing'
                });

                this.availableDevices = this.availableDevices.filter(d => d.uuid !== device.uuid);
                this.selectedToAdd = '';
            },

            addNewEditDevice() {
                if (!this.editNewDevice.device_id || this.editNewDevice.device_id.length > 16) return;

                const id = 'new-' + Date.now();
                const label = `${this.editNewDevice.model} - ${this.editNewDevice.device_id}`.trim();

                this.editDevices.push({
                    label: label,
                    type: 'new',
                    model: this.editNewDevice.model,
                    device_id: this.editNewDevice.device_id
                });

                this.editNewDevice.device_id = '';
                this.editNewDevice.model = '';
            },

            handleEditSubmit(event) {
                this.editDevices.forEach((device, index) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `devices[${index}]`;
                    input.value = JSON.stringify(device);
                    event.target.appendChild(input);
                });
            },
            removeEditDevice(index) {
                const removed = this.editDevices.splice(index, 1)[0];

                if (removed && removed.type === 'existing') {
                    const found = this.schoolDevices.find(d => d.uuid === removed.uuid);
                    if (found) {
                        this.availableDevices.push(found);
                    }
                }
            }
        };
    }
</script>
