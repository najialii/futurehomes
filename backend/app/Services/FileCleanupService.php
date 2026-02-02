<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileCleanupService
{
    /**
     * Clean up orphaned files (files not referenced in database)
     */
    public function cleanupOrphanedFiles(): int
    {
        $deletedCount = 0;
        $disk = Storage::disk('public');

        // Get all image directories
        $directories = ['images', 'partners', 'services', 'projects', 'testimonials'];

        foreach ($directories as $directory) {
            if (!$disk->exists($directory)) {
                continue;
            }

            $files = $disk->allFiles($directory);
            
            foreach ($files as $file) {
                if ($this->isOrphanedFile($file)) {
                    try {
                        $disk->delete($file);
                        $deletedCount++;
                        Log::info("Deleted orphaned file: {$file}");
                    } catch (\Exception $e) {
                        Log::error("Failed to delete orphaned file {$file}: " . $e->getMessage());
                    }
                }
            }
        }

        return $deletedCount;
    }

    /**
     * Check if file is orphaned (not referenced in any model)
     */
    protected function isOrphanedFile(string $filePath): bool
    {
        // Check in companies table
        if (\App\Models\Company::where('logo_path', $filePath)->exists()) {
            return false;
        }

        // Check in services table
        if (\App\Models\Service::where('icon_path', $filePath)->exists()) {
            return false;
        }

        // Check in partners table
        if (\App\Models\Partner::where('logo_path', $filePath)->exists()) {
            return false;
        }

        // Check in project_images table
        if (\App\Models\ProjectImage::where('image_path', $filePath)->exists()) {
            return false;
        }

        // Check in testimonials table
        if (\App\Models\Testimonial::where('client_photo_path', $filePath)->exists()) {
            return false;
        }

        // Check in media table (Spatie Media Library)
        if (\Spatie\MediaLibrary\MediaCollections\Models\Media::where('file_name', basename($filePath))->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Delete specific file and its thumbnail
     */
    public function deleteFileWithThumbnail(string $filePath): bool
    {
        $disk = Storage::disk('public');
        $deleted = true;

        // Delete original file
        if ($disk->exists($filePath)) {
            try {
                $disk->delete($filePath);
                Log::info("Deleted file: {$filePath}");
            } catch (\Exception $e) {
                Log::error("Failed to delete file {$filePath}: " . $e->getMessage());
                $deleted = false;
            }
        }

        // Delete thumbnail if exists
        $thumbnailPath = $this->getThumbnailPath($filePath);
        if ($disk->exists($thumbnailPath)) {
            try {
                $disk->delete($thumbnailPath);
                Log::info("Deleted thumbnail: {$thumbnailPath}");
            } catch (\Exception $e) {
                Log::error("Failed to delete thumbnail {$thumbnailPath}: " . $e->getMessage());
                $deleted = false;
            }
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
     * Clean up old temporary files
     */
    public function cleanupTempFiles(int $olderThanHours = 24): int
    {
        $deletedCount = 0;
        $disk = Storage::disk('public');
        $tempDirectory = 'temp';

        if (!$disk->exists($tempDirectory)) {
            return 0;
        }

        $files = $disk->allFiles($tempDirectory);
        $cutoffTime = now()->subHours($olderThanHours);

        foreach ($files as $file) {
            $lastModified = $disk->lastModified($file);
            
            if ($lastModified < $cutoffTime->timestamp) {
                try {
                    $disk->delete($file);
                    $deletedCount++;
                    Log::info("Deleted old temp file: {$file}");
                } catch (\Exception $e) {
                    Log::error("Failed to delete temp file {$file}: " . $e->getMessage());
                }
            }
        }

        return $deletedCount;
    }
}