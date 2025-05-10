<?php 
namespace App\Enums;

enum CommandStatus: string
{
    case Pending   = 'pending';
    case Sent      = 'sent';       // 👈 adicionado
    case Completed = 'completed';
    case Failed    = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Pendente',
            self::Sent      => 'Enviado',
            self::Completed => 'Sucesso',
            self::Failed    => 'Falha',
        };
    }
}