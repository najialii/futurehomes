<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

// Test route
Route::get('/test-storage', function () {
    \Log::info('Test storage route hit');
    return response()->json(['message' => 'Test route works']);
});

// Serve images with CORS headers
Route::get('/images/{path}', function ($path) {
    \Log::info('Custom images route hit for path: ' . $path);
    
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        \Log::error('File not found: ' . $filePath);
        abort(404);
    }
    
    $mimeType = mime_content_type($filePath);
    
    $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        'Content-Type' => $mimeType,
    ];
    
    \Log::info('Serving file with headers: ', $headers);
    
    return Response::file($filePath, $headers);
})->where('path', '.*');