<?php

use App\Models\Service;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 10: Service-based project filtering
 * 
 * For any service, when projects are filtered by service_id, only projects
 * belonging to that specific service should be returned
 */

it('filters projects by service correctly', function () {
    // Create multiple services
    $services = collect();
    for ($i = 0; $i < fake()->numberBetween(3, 5); $i++) {
        $services->push(Service::create([
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => $i + 1,
            'is_active' => true,
        ]));
    }

    // Create projects for each service
    $projectsByService = collect();
    foreach ($services as $service) {
        $projectCount = fake()->numberBetween(2, 6);
        $serviceProjects = collect();
        
        for ($j = 0; $j < $projectCount; $j++) {
            $serviceProjects->push(Project::create([
                'name' => fake()->sentence(4),
                'description' => fake()->paragraph(),
                'service_id' => $service->id,
                'status' => 'published',
                'display_order' => $j + 1,
            ]));
        }
        
        $projectsByService->put($service->id, $serviceProjects);
    }

    // Test filtering for each service
    foreach ($services as $service) {
        $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$service->id}");
        
        $response->assertStatus(200);
        $responseData = $response->json('data');
        
        // All returned projects should belong to this service
        foreach ($responseData as $project) {
            expect($project['service_id'])->toBe($service->id);
            expect($project['service']['id'])->toBe($service->id);
        }
        
        // Count should match expected projects for this service
        $expectedCount = $projectsByService->get($service->id)->count();
        expect(count($responseData))->toBe($expectedCount);
    }
})->repeat(5);

it('returns empty results for non-existent service', function () {
    // Create some services and projects
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    Project::create([
        'name' => fake()->sentence(4),
        'description' => fake()->paragraph(),
        'service_id' => $service->id,
        'status' => 'published',
        'display_order' => 1,
    ]);

    // Test with non-existent service ID
    $nonExistentServiceId = $service->id + 999;
    $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$nonExistentServiceId}");
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    expect(count($responseData))->toBe(0);
})->repeat(5);

it('filters projects by service using the byService endpoint', function () {
    // Create services
    $service1 = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    $service2 = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 2,
        'is_active' => true,
    ]);

    // Create projects for service1
    $service1Projects = collect();
    for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
        $service1Projects->push(Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service1->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]));
    }

    // Create projects for service2
    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
        Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service2->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]);
    }

    // Test the byService endpoint
    $response = $this->withoutMiddleware()->getJson("/api/services/{$service1->id}/projects");
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // All returned projects should belong to service1
    foreach ($responseData as $project) {
        expect($project['service_id'])->toBe($service1->id);
    }
    
    // Count should match service1 projects
    expect(count($responseData))->toBe($service1Projects->count());
})->repeat(5);

it('maintains service filtering with pagination', function () {
    // Create a service
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    // Create another service to ensure filtering works
    $otherService = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 2,
        'is_active' => true,
    ]);

    // Create many projects for the main service
    $projectCount = 15;
    for ($i = 0; $i < $projectCount; $i++) {
        Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]);
    }

    // Create projects for other service
    for ($i = 0; $i < 5; $i++) {
        Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $otherService->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]);
    }

    // Test first page
    $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$service->id}&per_page=5&page=1");
    
    $response->assertStatus(200);
    $responseData = $response->json();
    
    // Verify pagination metadata
    expect($responseData['meta']['total'])->toBe($projectCount);
    expect(count($responseData['data']))->toBe(5);
    
    // All projects should belong to the specified service
    foreach ($responseData['data'] as $project) {
        expect($project['service_id'])->toBe($service->id);
    }

    // Test second page
    $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$service->id}&per_page=5&page=2");
    
    $response->assertStatus(200);
    $responseData = $response->json();
    
    expect(count($responseData['data']))->toBe(5);
    
    // All projects should still belong to the specified service
    foreach ($responseData['data'] as $project) {
        expect($project['service_id'])->toBe($service->id);
    }
})->repeat(3);

it('combines service filtering with status filtering', function () {
    // Create a service
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    // Create published projects
    $publishedCount = fake()->numberBetween(3, 6);
    for ($i = 0; $i < $publishedCount; $i++) {
        Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]);
    }

    // Create draft projects
    $draftCount = fake()->numberBetween(2, 4);
    for ($i = 0; $i < $draftCount; $i++) {
        Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'draft',
            'display_order' => $i + 100,
        ]);
    }

    // Test default behavior (should return only published)
    $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$service->id}");
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Should only return published projects for this service
    expect(count($responseData))->toBe($publishedCount);
    
    foreach ($responseData as $project) {
        expect($project['service_id'])->toBe($service->id);
        expect($project['status'])->toBe('published');
    }

    // Test explicit draft filtering
    $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$service->id}&status=draft");
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Should only return draft projects for this service
    expect(count($responseData))->toBe($draftCount);
    
    foreach ($responseData as $project) {
        expect($project['service_id'])->toBe($service->id);
        expect($project['status'])->toBe('draft');
    }
})->repeat(5);

it('maintains ordering when filtering by service', function () {
    // Create a service
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    // Create projects with specific display orders
    $displayOrders = [5, 1, 3, 2, 4];
    $projects = collect();
    
    foreach ($displayOrders as $order) {
        $projects->push(Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => $order,
        ]));
    }

    // Test service filtering with ordering
    $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$service->id}");
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Verify all projects belong to the service
    foreach ($responseData as $project) {
        expect($project['service_id'])->toBe($service->id);
    }
    
    // Verify ordering (should be ascending by display_order)
    $returnedOrders = collect($responseData)->pluck('display_order')->toArray();
    $expectedOrders = collect($displayOrders)->sort()->values()->toArray();
    
    expect($returnedOrders)->toBe($expectedOrders);
})->repeat(5);

it('handles invalid service_id parameters gracefully', function () {
    // Create some test data
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    Project::create([
        'name' => fake()->sentence(4),
        'description' => fake()->paragraph(),
        'service_id' => $service->id,
        'status' => 'published',
        'display_order' => 1,
    ]);

    // Test with invalid service_id values
    $invalidServiceIds = ['abc', '', 0, -1, 'null'];
    
    foreach ($invalidServiceIds as $invalidId) {
        $response = $this->withoutMiddleware()->getJson("/api/projects?service_id={$invalidId}");
        
        $response->assertStatus(200); // Should not crash
        $responseData = $response->json('data');
        
        // Should return empty results for invalid service IDs
        expect(count($responseData))->toBe(0);
    }
})->repeat(3);