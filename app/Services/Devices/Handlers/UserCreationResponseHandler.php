<?php

namespace App\Services\Devices\Handlers;

use App\Models\DeviceGroupCommand;
use App\Models\ExternalDeviceId;
use App\Jobs\SendDeviceGroupCommandJob;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon; // para gerar timestamp
use App\Helpers\MediaHelper;



class UserCreationResponseHandler
{
    public static function handle(array $response): void
    {
        logger()->info('[UserCreationResponseHandler] Executando handler...', $response);

        $externalIds = $response['ids'] ?? [];
        $deviceId = $response['deviceId'] ?? null;
        $commandUuid = $response['uuid'] ?? null;

        if (!$externalIds || !$deviceId || !$commandUuid) {
            return;
        }

        $command = DeviceGroupCommand::where('uuid', $commandUuid)->firstOrFail();
        $deviceGroup = $command->deviceGroup;
        $payloadUsers = collect($command->payload['body']['values']);

       $userGroupPayloads = [];
$photoPayloads = [];
$qrPayloads = [];

foreach ($externalIds as $index => $externalId) {
    $userData = $payloadUsers[$index] ?? null;
    if (!$userData || empty($userData['registration'])) continue;

    $person = self::findPersonByRegistration($userData['registration']);
    if (!$person) continue;

    ExternalDeviceId::updateOrCreate([
        'person_id' => $person->uuid,
        'person_type' => get_class($person),
        'device_id' => $deviceId,
    ], [
        'external_id' => $externalId,
        'uuid' => (string) Str::uuid(),
    ]);

    // ✅ user_groups
    $userGroupPayloads[] = [
        'user_id' => $externalId,
        'group_id' => 1,
    ];

    // ✅ Foto (facial image)
    $photoBase64 = MediaHelper::getBase64UserPhoto($person->uuid);
    if ($photoBase64) {
        $photoPayloads[] = [
            'user_id' => $externalId,
            'timestamp' => Carbon::now()->timestamp,
            'image' => $photoBase64,
        ];
    }

    // ✅ QR Code (UUID como identificador)
    $qrPayloads[] = [
        'user_id' => $externalId,
        
        'value' => $person->uuid,
    ];
}

        // 🔁 Envio dos grupos
        collect($userGroupPayloads)->chunk(100)->each(function ($chunk) use ($deviceGroup) {
            $payload = [
                'verb' => 'POST',
                'endpoint' => 'create_objects',
                'contentType' => 'application/json',
                'body' => [
                    'object' => 'user_groups',
                    'values' => $chunk->values()->all(),
                ],
            ];

            DeviceGroupCommand::createAndDispatch([
                'device_group_id' => $deviceGroup->uuid,
                'payload' => $payload,
                'status' => \App\Enums\CommandStatus::Pending,
            ], $deviceGroup->school_id);
        });

        // 🔁 Envio das fotos (base64)
        collect($photoPayloads)->chunk(100)->each(function ($chunk) use ($deviceGroup) {
            $payload = [
                'verb' => 'POST',
                'endpoint' => 'user_set_image_list',
                'contentType' => 'application/json',
                'body' => [
                    'match' => false,
                    'user_images' => $chunk->values()->all(),
                ],
            ];

            DeviceGroupCommand::createAndDispatch([
                'device_group_id' => $deviceGroup->uuid,
                'payload' => $payload,
                'status' => \App\Enums\CommandStatus::Pending,
            ], $deviceGroup->school_id);
        });
        collect($qrPayloads)->chunk(100)->each(function ($chunk) use ($deviceGroup) {
    $payload = [
        'verb' => 'POST',
        'endpoint' => 'create_objects',
        'contentType' => 'application/json',
        'body' => [
            'object' => 'qrcodes',
            'values' => $chunk->values()->all(),
        ],
    ];

    DeviceGroupCommand::createAndDispatch([
        'device_group_id' => $deviceGroup->uuid,
        'payload' => $payload,
        'status' => \App\Enums\CommandStatus::Pending,
    ], $deviceGroup->school_id);
});
    }

    private static function findPersonByRegistration(string $registration): ?\Illuminate\Database\Eloquent\Model
    {
        return \App\Models\Student::where('registration_number', $registration)->first()
            ?? \App\Models\Guardian::where('cpf', $registration)->first()
            ?? \App\Models\Functionary::where('cpf', $registration)->first();
    }
}
