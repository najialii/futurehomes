<?php

use App\Services\ImageProcessingService;
use App\Services\FileValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 2: File upload and URL generation
 * 
 * For any file upload (logos, icons, images, photos), when a file is uploaded through the CMS, 
 * it should be stored in the file system and a valid public URL should be generated and accessible
 */

beforeEach(function () {
    Storage::fake('public');
});

it('generates valid URLs for uploaded images', function () {
    $imageService = new ImageProcessingService();
    
    // Create a fake image file
    $file = UploadedFile::fake()->image('test-image.jpg', 800, 600);
    
    // Process and store the image
    $result = $imageService->processAndStore($file, 'test-images');
    
    // Verify the result contains required keys
    expect($result)->toHaveKeys([
        'original_path',
        'thumbnail_path', 
        'original_url',
        'thumbnail_url',
        'filename'
    ]);
    
    // Verify files exist in storage
    expect(Storage::disk('public')->exists($result['original_path']))->toBeTrue();
    expect(Storage::disk('public')->exists($result['thumbnail_path']))->toBeTrue();
    
    // Verify URLs are valid
    expect($result['original_url'])->toBeString();
    expect($result['thumbnail_url'])->toBeString();
    expect($result['original_url'])->toContain($result['filename']);
    expect($result['thumbnail_url'])->toContain($result['filename']);
    
    // Verify URLs are accessible (contain storage path)
    expect($result['original_url'])->toContain('/storage/');
    expect($result['thumbnail_url'])->toContain('/storage/');
})->repeat(10);

it('validates file upload security', function () {
    $validationService = new FileValidationService();
    
    // Test with valid image
    $validImage = UploadedFile::fake()->image('valid.jpg', 400, 300);
    expect(fn() => $validationService->validateImage($validImage))->not->toThrow(\Exception::class);
    expect($validationService->isFileSafe($validImage))->toBeTrue();
    
    // Test filename sanitization
    $dangerousFilename = '../../../etc/passwd.jpg';
    $safeFilename = $validationService->sanitizeFilename($dangerousFilename);
    expect($safeFilename)->not->toContain('../');
    expect($safeFilename)->not->toContain('/');
})->repeat(10);

it('handles file deletion correctly', function () {
    $imageService = new ImageProcessingService();
    
    // Upload an image
    $file = UploadedFile::fake()->image('delete-test.png', 600, 400);
    $result = $imageService->processAndStore($file, 'delete-test');
    
    // Verify files exist
    expect(Storage::disk('public')->exists($result['original_path']))->toBeTrue();
    expect(Storage::disk('public')->exists($result['thumbnail_path']))->toBeTrue();
    
    // Delete the image
    $deleted = $imageService->deleteImage($result['original_path']);
    
    // Verify deletion was successful
    expect($deleted)->toBeTrue();
    expect(Storage::disk('public')->exists($result['original_path']))->toBeFalse();
    expect(Storage::disk('public')->exists($result['thumbnail_path']))->toBeFalse();
})->repeat(10);

it('processes different image formats consistently', function () {
    $imageService = new ImageProcessingService();
    
    $formats = ['jpg', 'png', 'gif'];
    $format = fake()->randomElement($formats);
    
    // Create image with random format
    $file = UploadedFile::fake()->image("test-image.{$format}", 
        fake()->numberBetween(200, 1000), 
        fake()->numberBetween(200, 1000)
    );
    
    // Process the image
    $result = $imageService->processAndStore($file, 'format-test');
    
    // Verify processing worked regardless of format
    expect($result['filename'])->toEndWith(".{$format}");
    expect(Storage::disk('public')->exists($result['original_path']))->toBeTrue();
    expect(Storage::disk('public')->exists($result['thumbnail_path']))->toBeTrue();
    
    // Verify URLs are generated
    expect($result['original_url'])->toBeString();
    expect($result['thumbnail_url'])->toBeString();
})->repeat(10);