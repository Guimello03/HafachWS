<div 
    :key="window.alpineKey"
    x-data="deviceGroupModal(window.schoolDevices)" 
    x-init="init();
             window.addEventListener('open-create-modal', () => open = true);
             window.addEventListener('close-modal', () => open = false);"
    x-show="open"
    x-transition
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <div @click.away="open = false" class="w-full max-w-2xl overflow-hidden bg-white rounded-lg shadow-lg">
        <div x-data='deviceGroupModal(@json($schoolDevices))' x-init="init()">
            <form method="POST" action="{{ route('device_groups.store') }}" class="flex flex-col max-h-[90vh]">
                @csrf

                {{-- Área com rolagem --}}
                <div class="px-6 py-4 space-y-6 overflow-y-auto">

                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Criar Grupo e Equipamentos</h2>

                    {{-- Nome do grupo --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome do Grupo</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr>

                    {{-- Novo equipamento --}}
                    <div>
                        <h3 class="mb-2 text-sm font-semibold text-gray-700">Novo Equipamento</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Modelo</label>
                            <select x-model="selectedModel"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecione o modelo...</option>
                                <option value="controlid">ControlId</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2 mt-4" x-show="selectedModel" x-cloak>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Device ID</label>
                                <input type="text" x-model="newDevice.device_id" maxlength="16"
                                    placeholder="Digite o Device ID"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <button type="button" @click="addNewDevice()" :disabled="!newDevice.device_id"
                                class="px-3 py-2 mt-5 text-sm text-white bg-green-600 rounded hover:bg-green-700 disabled:opacity-50">
                                +
                            </button>
                        </div>
                    </div>

                    <hr>

                    {{-- Equipamento existente --}}
                    <div x-show="availableDevices.length" x-cloak>
                        <h3 class="mb-2 text-sm font-semibold text-gray-700">Vincular Equipamento Existente</h3>

                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Equipamento</label>
                                <select x-model="selectedExistingDeviceId"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione um equipamento</option>
                                    <template x-for="device in availableDevices" :key="device.uuid">
                                        <option :value="device.uuid" x-text="device.label"></option>
                                    </template>
                                </select>
                            </div>

                            <button type="button" @click="addExistingDevice()" :disabled="!selectedExistingDeviceId"
                                class="px-3 py-2 mt-5 text-sm text-white bg-blue-600 rounded hover:bg-blue-700 disabled:opacity-50">
                                +
                            </button>
                        </div>
                    </div>

                    <hr>

                    {{-- Lista vinculada --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Equipamentos Vinculados</h3>
                        <ul class="mt-2 space-y-2">
                            <template x-for="(device, index) in selectedDevices" :key="index">
                                <li class="flex items-center justify-between px-3 py-2 bg-gray-100 rounded">
                                    <span x-text="device.label"></span>
                                    <input type="hidden" name="devices" :value="JSON.stringify(selectedDevices)">

                                    <button type="button" @click="removeDevice(index)">
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
                <div class="flex justify-between px-6 py-4 pt-4 bg-white border-t">
                    <button type="button" @click="$dispatch('close-modal')"
                        class="px-4 py-2 text-gray-700 bg-gray-100 border rounded hover:bg-gray-200">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                        Criar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function deviceGroupModal(devicesFromSchool = []) {
        return {
            open: false,
            selectedModel: '',
            newDevice: {
                device_id: ''
            },
            schoolDevices: [],
            availableDevices: [],
            selectedExistingDeviceId: '',
            selectedDevices: [],

            init() {


                this.schoolDevices = devicesFromSchool.map(device => ({
                    ...device,
                    label: device.label ?? `${device.model} - ${device.serial_number}`
                }));
                this.availableDevices = [...this.schoolDevices];
            },

            addNewDevice() {
                if (!this.newDevice.device_id || this.newDevice.device_id.length > 16) return;

                const id = 'new-' + Date.now();
                const label = `${this.selectedModel} - ${this.newDevice.device_id}`.trim();

                this.selectedDevices.push({
                    label: label,
                    type: 'new',
                    model: this.selectedModel,
                    device_id: this.newDevice.device_id,
                });

                this.newDevice.device_id = '';
                this.selectedModel = '';
            },

            addExistingDevice() {
                const device = this.availableDevices.find(d => d.uuid === this.selectedExistingDeviceId);
                if (device) {
                    this.selectedDevices.push({
                        uuid: device.uuid, // ✅ Envia uuid
                        label: device.label,
                        type: 'existing',
                    });
                    this.availableDevices = this.availableDevices.filter(d => d.uuid !== device.uuid);
                    this.selectedExistingDeviceId = '';
                }
            },

            removeDevice(index) {
                this.selectedDevices.splice(index, 1);

                // Recalcular os disponíveis: todos da escola menos os selecionados
                const selectedUUIDs = this.selectedDevices
                    .filter(d => d.type === 'existing')
                    .map(d => d.uuid);

                this.availableDevices = this.schoolDevices.filter(d => !selectedUUIDs.includes(d.uuid));
            }
        };
    }
</script>
