<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDaoEventRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permite sempre
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'device_id' => 'required',
            'time' => 'required|integer',
        ];
    }
}
