<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DeviceEvent;

class DeviceEventController extends Controller
{
    public function receive(Request $request){

        $eventData = $request->all();

        log::info('Evento recebido do equipamento', $eventData);

        return response()->json(['message' => 'Evento recebido com sucesso'], 200);
    }
    public function result(Request $request)
{
    \Log::info('Resultado recebido do equipamento:', $request->all());

    return response()->json(['message' => 'Resultado recebido com sucesso'], 200);
}
}
