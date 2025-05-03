<?php 
namespace App\Enums;

enum CommandStatus: string
{
    case Pending = 'pending';
    case Success = 'success';
    case Failed  = 'failed';
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Success => 'Sucesso',
            self::Failed  => 'Falha',
        };
    }
}