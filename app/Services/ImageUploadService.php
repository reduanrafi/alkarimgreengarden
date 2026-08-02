<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function upload(UploadedFile $file, ?string $existing = null, string $path = 'products'): string
    {
        $newPath = $file->store($path, 'public');

        if ($existing && $newPath) {
            $this->delete($existing);
        }

        return $newPath;
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function deleteMultiple(?array $paths): void
    {
        if ($paths) {
            foreach ($paths as $path) {
                $this->delete($path);
            }
        }
    }

    public function uploadMultiple(array $files, string $path = 'products'): array
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $file->store($path, 'public');
        }

        return $paths;
    }
}
