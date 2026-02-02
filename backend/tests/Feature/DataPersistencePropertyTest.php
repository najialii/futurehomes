<?php

use App\Models\Company;
use App\Models\Service;
use App\Models\Project;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 1: Data persistence consistency
 * 
 * For any content type (company, service, project, partner, testimonial, page, contact submission), 
 * when data is created or updated through the CMS, the changes should be immediately reflected 
 * in both the database and API responses
 */

it('persists company data consistently', function () {
    // Test data creation
    $companyData = [
        'name' => fake()->company(),
        'description' => fake()->text(),
        'email' => fake()->email(),
        'phone' => fake()->phoneNumber(),
        'address' => fake()->address(),
        'website_url' => fake()->url(),
        'social_media' => [
            'facebook' => fake()->url(),
            'instagram' => fake()->url(),
        ],
    ];

    $company = Company::create($companyData);
    
    // Verify data is persisted in database
    $this->assertDatabaseHas('companies', [
        'id' => $company->id,
        'name' => $companyData['name'],
        'email' => $companyData['email'],
    ]);

    // Test data update
    $updatedData = [
        'name' => fake()->company(),
        'email' => fake()->email(),
    ];

    $company->update($updatedData);
    $company->refresh();

    // Verify updates are persisted
    expect($company->name)->toBe($updatedData['name']);
    expect($company->email)->toBe($updatedData['email']);
    
    $this->assertDatabaseHas('companies', [
        'id' => $company->id,
        'name' => $updatedData['name'],
        'email' => $updatedData['email'],
    ]);
})->repeat(10);

it('persists service data consistently', function () {
    $serviceData = [
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => fake()->numberBetween(1, 100),
        'is_active' => fake()->boolean(),
    ];

    $service = Service::create($serviceData);
    
    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'title' => $serviceData['title'],
        'is_active' => $serviceData['is_active'],
    ]);

    // Test update
    $updatedData = [
        'title' => fake()->sentence(3),
        'is_active' => !$serviceData['is_active'],
    ];

    $service->update($updatedData);
    $service->refresh();

    expect($service->title)->toBe($updatedData['title']);
    expect($service->is_active)->toBe($updatedData['is_active']);
})->repeat(10);

it('persists project data consistently', function () {
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => fake()->numberBetween(1, 100),
        'is_active' => true,
    ]);
    
    $projectData = [
        'name' => fake()->sentence(4),
        'description' => fake()->paragraph(),
        'service_id' => $service->id,
        'status' => fake()->randomElement(['draft', 'published']),
        'display_order' => fake()->numberBetween(1, 100),
    ];

    $project = Project::create($projectData);
    
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => $projectData['name'],
        'service_id' => $projectData['service_id'],
        'status' => $projectData['status'],
    ]);

    // Test update
    $updatedData = [
        'name' => fake()->sentence(4),
        'status' => $projectData['status'] === 'draft' ? 'published' : 'draft',
    ];

    $project->update($updatedData);
    $project->refresh();

    expect($project->name)->toBe($updatedData['name']);
    expect($project->status)->toBe($updatedData['status']);
})->repeat(10);