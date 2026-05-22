<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;

class ImageOptimizationService
{
    // Quality settings for JPEG and WebP formats (0-100)
    protected int $jpegQuality = 82;
    protected int $webpQuality = 80;

    // Maximum dimensions for resizing (in pixels)
    protected int $maxWidth  = 1280;
    protected int $maxHeight = 1280;

    // Upload and optimize the image, returning details about the original and optimized sizes
    public function upload(UploadedFile $file, string $folder = 'images', string $disk = 'public'): array
    {
        $filename = Str::uuid()->toString();

        $encoded = Image::decode($file->getRealPath())
            ->scaleDown($this->maxWidth, $this->maxHeight)
            ->encodeUsingFileExtension($file->getClientOriginalExtension(), progressive: true, quality: $this->jpegQuality, strip: true);
            // ->encodeUsingFormat(Format::JPEG, progressive: true, quality: $this->jpegQuality, strip: true);

        $path = "{$folder}/{$filename}.{$file->getClientOriginalExtension()}";
        Storage::disk($disk)->put($path, (string) $encoded);

        return [
            'path'           => $path,
            'size_original'  => $file->getSize(),
            'size_optimized' => strlen((string) $encoded),
        ];
    }

    // Delete an image from storage
    public function delete(string $path, string $disk = 'public'): void
    {
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
