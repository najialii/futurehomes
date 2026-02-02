<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\StatController;
use App\Http\Controllers\Api\DesignController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

// Public API routes
Route::prefix('v1')->middleware(['throttle:api', 'cache.headers'])->group(function () {
    // Company information
    Route::get('/company', [CompanyController::class, 'index']);
    
    // Services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{service}', [ServiceController::class, 'show']);
    
    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/services/{serviceId}/projects', [ProjectController::class, 'byService']);
    
    // Partners
    Route::get('/partners', [PartnerController::class, 'index']);
    
    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    
    // Stats
    Route::get('/stats', [StatController::class, 'index']);
    
    // Designs
    Route::get('/designs', [DesignController::class, 'index']);
    Route::get('/designs/featured', [DesignController::class, 'featured']);
    Route::get('/designs/categories', [DesignController::class, 'categories']);
    Route::get('/designs/tags', [DesignController::class, 'tags']);
    Route::get('/designs/category/{category}', [DesignController::class, 'byCategory']);
    Route::get('/designs/{design}', [DesignController::class, 'show']);
    
    // Pages
    Route::get('/pages', [PageController::class, 'index']);
    Route::get('/pages/{slug}', [PageController::class, 'show']);
    
    // Contact form (more restrictive rate limiting)
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact');
});

// Backward compatibility routes (without v1 prefix)
Route::middleware(['throttle:api', 'cache.headers'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{service}', [ServiceController::class, 'show']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/services/{serviceId}/projects', [ProjectController::class, 'byService']);
    Route::get('/partners', [PartnerController::class, 'index']);
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::get('/stats', [StatController::class, 'index']);
    Route::get('/designs', [DesignController::class, 'index']);
    Route::get('/designs/featured', [DesignController::class, 'featured']);
    Route::get('/designs/categories', [DesignController::class, 'categories']);
    Route::get('/designs/tags', [DesignController::class, 'tags']);
    Route::get('/designs/category/{category}', [DesignController::class, 'byCategory']);
    Route::get('/designs/{design}', [DesignController::class, 'show']);
    Route::get('/pages', [PageController::class, 'index']);
    Route::get('/pages/{slug}', [PageController::class, 'show']);
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact');
});

// Authenticated routes with permission-based access control
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Admin API routes with permission checks
    Route::middleware('permission:companies.create')->post('/companies', [CompanyController::class, 'store']);
    Route::middleware('permission:companies.read')->get('/companies/{company}', [CompanyController::class, 'show']);
    Route::middleware('permission:companies.update')->put('/companies/{company}', [CompanyController::class, 'update']);
    Route::middleware('permission:companies.update')->patch('/companies/{company}', [CompanyController::class, 'update']);
    Route::middleware('permission:companies.delete')->delete('/companies/{company}', [CompanyController::class, 'destroy']);
    
    Route::middleware('permission:services.create')->post('/services', [ServiceController::class, 'store']);
    Route::middleware('permission:services.update')->put('/services/{service}', [ServiceController::class, 'update']);
    Route::middleware('permission:services.update')->patch('/services/{service}', [ServiceController::class, 'update']);
    Route::middleware('permission:services.delete')->delete('/services/{service}', [ServiceController::class, 'destroy']);
    
    Route::middleware('permission:projects.create')->post('/projects', [ProjectController::class, 'store']);
    Route::middleware('permission:projects.update')->put('/projects/{project}', [ProjectController::class, 'update']);
    Route::middleware('permission:projects.update')->patch('/projects/{project}', [ProjectController::class, 'update']);
    Route::middleware('permission:projects.delete')->delete('/projects/{project}', [ProjectController::class, 'destroy']);
    
    Route::middleware('permission:partners.create')->post('/partners', [PartnerController::class, 'store']);
    Route::middleware('permission:partners.update')->put('/partners/{partner}', [PartnerController::class, 'update']);
    Route::middleware('permission:partners.update')->patch('/partners/{partner}', [PartnerController::class, 'update']);
    Route::middleware('permission:partners.delete')->delete('/partners/{partner}', [PartnerController::class, 'destroy']);
    
    Route::middleware('permission:testimonials.create')->post('/testimonials', [TestimonialController::class, 'store']);
    Route::middleware('permission:testimonials.update')->put('/testimonials/{testimonial}', [TestimonialController::class, 'update']);
    Route::middleware('permission:testimonials.update')->patch('/testimonials/{testimonial}', [TestimonialController::class, 'update']);
    Route::middleware('permission:testimonials.delete')->delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy']);
    
    Route::middleware('permission:pages.create')->post('/pages', [PageController::class, 'store']);
    Route::middleware('permission:pages.update')->put('/pages/{page}', [PageController::class, 'update']);
    Route::middleware('permission:pages.update')->patch('/pages/{page}', [PageController::class, 'update']);
    Route::middleware('permission:pages.delete')->delete('/pages/{page}', [PageController::class, 'destroy']);
    
    // Stats management
    Route::middleware('permission:stats.create')->post('/stats', [StatController::class, 'store']);
    Route::middleware('permission:stats.read')->get('/stats/{stat}', [StatController::class, 'show']);
    Route::middleware('permission:stats.update')->put('/stats/{stat}', [StatController::class, 'update']);
    Route::middleware('permission:stats.update')->patch('/stats/{stat}', [StatController::class, 'update']);
    Route::middleware('permission:stats.delete')->delete('/stats/{stat}', [StatController::class, 'destroy']);
    
    // Designs management
    Route::middleware('permission:designs.create')->post('/designs', [DesignController::class, 'store']);
    Route::middleware('permission:designs.update')->put('/designs/{design}', [DesignController::class, 'update']);
    Route::middleware('permission:designs.update')->patch('/designs/{design}', [DesignController::class, 'update']);
    Route::middleware('permission:designs.delete')->delete('/designs/{design}', [DesignController::class, 'destroy']);
    
    // Contact submissions management
    Route::middleware('permission:contact_submissions.read')->get('/contact-submissions', [ContactController::class, 'index']);
    Route::middleware('permission:contact_submissions.read')->get('/contact-submissions/{submission}', [ContactController::class, 'show']);
    Route::middleware('permission:contact_submissions.update')->put('/contact-submissions/{submission}', [ContactController::class, 'update']);
    Route::middleware('permission:contact_submissions.delete')->delete('/contact-submissions/{submission}', [ContactController::class, 'destroy']);
});