<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProcessingService
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Process and store an uploaded image with thumbnail generation
     */
    public function processAndStore(UploadedFile $file, string $directory = 'images'): array
    {
        // Validate file
        $this->validateImage($file);

        // Generate unique filename
        $filename = $this->generateFilename($file);
        $path = $directory . '/' . $filename;
        $thumbnailPath = $directory . '/thumbnails/' . $filename;

        // Process original image
        $image = $this->imageManager->read($file->getPathname());
        
        // Optimize original image (max 1920px width)
        if ($image->width() > 1920) {
            $image->scale(width: 1920);
        }

        // Store original image
        Storage::disk('public')->put($path, $image->encode());

        // Create and store thumbnail (300px width)
        $thumbnail = clone $image;
        $thumbnail->scale(width: 300);
        Storage::disk('public')->put($thumbnailPath, $thumbnail->encode());

        return [
            'original_path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'original_url' => Storage::disk('public')->url($path),
            'thumbnail_url' => Storage::disk('public')->url($thumbnailPath),
            'filename' => $filename,
        ];
    }

    /**
     * Delete image and its thumbnail
     */
    public function deleteImage(string $path): bool
    {
        $deleted = true;

        // Delete original image
        if (Storage::disk('public')->exists($path)) {
            $deleted = Storage::disk('public')->delete($path) && $deleted;
        }

        // Delete thumbnail
        $thumbnailPath = $this->getThumbnailPath($path);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            $deleted = Storage::disk('public')->delete($thumbnailPath) && $deleted;
        }

        return $deleted;
    }

    /**
     * Get thumbnail path from original path
     */
    protected function getThumbnailPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
    }

    /**
     * Validate uploaded image
     */
    protected function validateImage(UploadedFile $file): void
    {
        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \InvalidArgumentException('File size too large. Maximum 10MB allowed.');
        }

        // Check mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Get public URL for stored image
     */
    public function getImageUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Check if image exists
     */
    public function imageExists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }
}