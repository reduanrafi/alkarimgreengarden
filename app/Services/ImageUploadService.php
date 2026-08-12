<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ImageUploadService
{
    protected const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/avif',
        'image/svg+xml',
        'image/x-icon',
        'image/vnd.microsoft.icon',
    ];

    protected const MAX_BYTES = 10 * 1024 * 1024;

    public function upload(UploadedFile $file, ?string $existing = null, string $path = 'products'): string
    {
        $this->validate($file);

        $newPath = $file->store($path, 'public');

        if ($existing && $newPath) {
            $this->delete($existing);
        }

        return $newPath;
    }

    protected function validate(UploadedFile $file): void
    {
        $mime = strtolower((string) $file->getMimeType());

        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            throw ValidationException::withMessages(['image' => 'The file must be a valid image.']);
        }

        if ($file->getSize() > self::MAX_BYTES) {
            throw ValidationException::withMessages(['image' => 'The image must not be larger than 10MB.']);
        }
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
            $paths[] = $this->upload($file, null, $path);
        }

        return $paths;
    }
}
