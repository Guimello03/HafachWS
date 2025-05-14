<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Storage;

class ImageProcessingService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new GdDriver());
    }

    public function processUploadedImage($uploadedFile, ?string $forcedName = null): array
{
    $image = $this->manager->read($uploadedFile->getRealPath())
        ->scaleDown(width: 800)
        ->toJpeg(quality: 85);

    $fileName = ($forcedName ?? uniqid('foto_')) . '.jpg';
    $path = "users/photos/{$fileName}";

    Storage::disk('public')->put($path, (string) $image);

    return [
        'path' => $path, // usado para acessar via asset()
        'absolute_path' => storage_path("app/public/{$path}"), // usado pelo Control iD
    ];
}

}
