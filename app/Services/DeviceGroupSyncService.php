<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    DeviceGroup,
    ExternalDeviceId,
    DeviceGroupCommand
};
use App\Enums\CommandStatus;

class DeviceGroupSyncService
{
    /**
     * Adiciona pessoas a um grupo e cria comandos para todos os dispositivos.
     */
    public static function addPersonsToGroup(DeviceGroup $group, Collection $people): void
    {
        
        foreach ($group->devices as $device) {
            // 1. Registra as pessoas na pivot usando UUID
            $people->each(function ($person) use ($group) {
                $group->{$person['relation']}()->syncWithoutDetaching([$person['uuid']]);
            });
            

            // 2. Gera comandos em chunks de 100
            $people->chunk(100)->each(function ($chunk) use ($group, $device) {
                $payload = self::buildCreatePayload($chunk);
               
                DeviceGroupCommand::createAndDispatch([
                    'device_group_id' => $group->uuid,
                    'payload' => $payload,
                    'status' => CommandStatus::Pending,
                ], $group->school_id);
            });
        }
    }

    /**
     * Remove pessoas de um grupo e dispara comando de exclusão para todos os equipamentos.
     */
    public static function removePersonsFromGroup(DeviceGroup $group, Collection $people): void
    {
        foreach ($group->devices as $device) {
            // 1. Busca os external_ids antes de remover
            $modelClass = $people->first()['type'];
    
            $externalIds = ExternalDeviceId::whereIn('person_id', $people->pluck('uuid'))
                ->where('person_type', $modelClass)
                ->where('device_id', $device->uuid)
                ->pluck('external_id')
                ->filter()
                ->values()
                ->toArray();
    
            // 2. Cria o comando de remoção SE houver external_ids
            if (!empty($externalIds)) {
                $payload = [
                    'verb' => 'POST',
                    'endpoint' => 'destroy_objects',
                    'contentType' => 'application/json',
                    'body' => [
                        'object' => 'users',
                        'where' => [
                            'users' => [
                                'id' => $externalIds
                            ],
                        ],
                    ],
                ];
    
                DeviceGroupCommand::createAndDispatch([
                    'device_group_id' => $group->uuid,
                    'payload' => $payload,
                    'status' => CommandStatus::Pending,
                ], $group->school_id);
            }
    
            // 3. Remove da pivot
            $people->each(function ($person) use ($group) {
                $relation = self::relationMethodFromType($person['type']);
                $group->{$relation}()->detach($person['uuid']);
            });
    
            // 4. Remove os registros external_device_ids
            if (!empty($externalIds)) {
                ExternalDeviceId::whereIn('external_id', $externalIds)
                    ->where('device_id', $device->uuid)
                    ->delete();
            }
        }
    }
    

    /**
     * Gera o payload de criação de usuários.
     */
    private static function buildCreatePayload(Collection $chunk): array
    {
        $values = $chunk->map(function ($person) {
            $model = self::resolvePerson($person['uuid'], $person['type']);

            $registration = match ($person['type']) {
                \App\Models\Student::class => $model->registration_number,
                \App\Models\Guardian::class,
                \App\Models\Functionary::class => $model->cpf,
                default => throw new \Exception("Tipo não suportado: {$person['type']}"),
            };

            return [
                'name' => $model->name,
                'registration' => $registration,
                'begin_time' => 0,
                'end_time' => 0,
                
            ];
        })->toArray();

        return [
            'verb' => 'POST',
            'endpoint' => 'create_objects',
            'contentType' => 'application/json',
            'body' => [
                'object' => 'users',
                'values' => $values
            ]
        ];
    }

    /**
     * Resolve o model completo com base no UUID e tipo.
     */
    private static function resolvePerson(string $uuid, string $type): Model
    {
        return $type::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Retorna o nome do relacionamento (ex: 'students') com base na classe.
     */
    public static function relationMethodFromType(string $type): string
    {
        return match ($type) {
            \App\Models\Student::class => 'students',
            \App\Models\Guardian::class => 'guardians',
            \App\Models\Functionary::class => 'functionaries',
            default => throw new \InvalidArgumentException("Tipo inválido: {$type}"),
        };
    }
}
