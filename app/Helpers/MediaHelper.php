<?php

namespace App\Helpers;

class MediaHelper
{
    /**
     * Retorna a foto do usuário em base64 se existir.
     */
    public static function getBase64UserPhoto(string $uuid): ?string
    {
        $path = storage_path("app/public/users/photos/{$uuid}.jpg");

        return file_exists($path) ? base64_encode(file_get_contents($path)) : null;
        logger()->info('[MediaHelper] Foto carregada e codificada com sucesso', [
            'uuid' => $uuid,
            'path' => $path
        ]);
    }
}