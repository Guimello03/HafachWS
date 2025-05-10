<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\DeviceCommandLogs;

class StoreCommandResultRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'deviceId' => ['required', 'string'],
            'uuid' => ['required', 'string', 'min:4'], // ⚠️ esse é o uuid do EQUIPAMENTO (ex: 21a1d011)
            'status' => ['nullable', 'in:' . implode(',', array_column(DeviceCommandLogs::cases(), 'value'))],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getStatusEnum(): DeviceCommandLogs
{
    return DeviceCommandLogs::from($this->input('status', 'success'));
}

    public function getEquipmentCommandId(): string
    {
        return $this->input('uuid');
    }

    public function getDeviceId(): string
    {
        return $this->input('deviceId');
    }
}
