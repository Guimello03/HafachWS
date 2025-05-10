<?php
namespace App\Enums;

enum DeviceCommandLogs: string
{
    case Pending = 'pending';
    case Success = 'success';
    case Error = 'error';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Success => 'Sucesso',
            self::Error => 'Erro',
        };
    }
}