<?php

use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 15: Pagination metadata accuracy
 * 
 * For any paginated API endpoint, the pagination metadata should accurately reflect
 * the total count, current page, and navigation information
 */

it('provides accurate pagination metadata for projects', function () {
    // Create a service first
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    // Create a known number of projects
    $totalProjects = fake()->numberBetween(15, 25);
    $projects = collect();
    
    for ($i = 0; $i < $totalProjects; $i++) {
        $projects->push(Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]));
    }

    // Test different page sizes
    $pageSizes = [5, 10, 12, 15];
    
    foreach ($pageSizes as $perPage) {
        $response = $this->withoutMiddleware()->getJson("/api/projects?per_page={$perPage}");
        
        $response->assertStatus(200);
        $responseData = $response->json();
        
        // Verify pagination structure exists
        expect($responseData)->toHaveKeys(['data', 'links', 'meta']);
        
        // Verify meta information accuracy
        $meta = $responseData['meta'];
        expect($meta['total'])->toBe($totalProjects);
        expect($meta['per_page'])->toBe($perPage);
        expect($meta['current_page'])->toBe(1);
        
        // Calculate expected last page
        $expectedLastPage = (int) ceil($totalProjects / $perPage);
        expect($meta['last_page'])->toBe($expectedLastPage);
        
        // Verify data count matches per_page (or remaining items on last page)
        $expectedDataCount = min($perPage, $totalProjects);
        expect(count($responseData['data']))->toBe($expectedDataCount);
        
        // Verify links structure
        $links = $responseData['links'];
        expect($links)->toHaveKeys(['first', 'last', 'prev', 'next']);
        expect($links['prev'])->toBeNull(); // First page should have no previous
        
        if ($expectedLastPage > 1) {
            expect($links['next'])->not->toBeNull();
        } else {
            expect($links['next'])->toBeNull();
        }
    }
})->repeat(5);

it('provides accurate pagination metadata for testimonials', function () {
    // Create a known number of testimonials
    $totalTestimonials = fake()->numberBetween(12, 20);
    $testimonials = collect();
    
    for ($i = 0; $i < $totalTestimonials; $i++) {
        $testimonials->push(Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'status' => 'approved',
        ]));
    }

    // Test pagination with default page size
    $response = $this->withoutMiddleware()->getJson('/api/testimonials');
    
    $response->assertStatus(200);
    $responseData = $response->json();
    
    // Verify pagination structure
    expect($responseData)->toHaveKeys(['data', 'links', 'meta']);
    
    // Verify meta accuracy
    $meta = $responseData['meta'];
    expect($meta['total'])->toBe($totalTestimonials);
    expect($meta['current_page'])->toBe(1);
    
    // Test specific page navigation
    if ($meta['last_page'] > 1) {
        $lastPage = $meta['last_page'];
        $response = $this->withoutMiddleware()->getJson("/api/testimonials?page={$lastPage}");
        
        $response->assertStatus(200);
        $lastPageData = $response->json();
        
        expect($lastPageData['meta']['current_page'])->toBe($lastPage);
        expect($lastPageData['meta']['total'])->toBe($totalTestimonials);
        
        // Last page should have correct number of items
        $expectedItemsOnLastPage = $totalTestimonials % $meta['per_page'];
        if ($expectedItemsOnLastPage === 0) {
            $expectedItemsOnLastPage = $meta['per_page'];
        }
        
        expect(count($lastPageData['data']))->toBe($expectedItemsOnLastPage);
        
        // Navigation links should be correct
        expect($lastPageData['links']['next'])->toBeNull(); // Last page has no next
        expect($lastPageData['links']['prev'])->not->toBeNull(); // Last page has previous
    }
})->repeat(5);

it('handles pagination boundaries correctly', function () {
    // Create exactly enough items to test boundary conditions
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    // Create exactly 10 projects (to test with per_page=5, should be exactly 2 pages)
    for ($i = 0; $i < 10; $i++) {
        Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]);
    }

    // Test first page
    $response = $this->withoutMiddleware()->getJson('/api/projects?per_page=5&page=1');
    $response->assertStatus(200);
    $firstPageData = $response->json();
    
    expect($firstPageData['meta']['current_page'])->toBe(1);
    expect($firstPageData['meta']['last_page'])->toBe(2);
    expect($firstPageData['links']['prev'])->toBeNull();
    expect($firstPageData['links']['next'])->not->toBeNull();
    expect(count($firstPageData['data']))->toBe(5);

    // Test second (last) page
    $response = $this->withoutMiddleware()->getJson('/api/projects?per_page=5&page=2');
    $response->assertStatus(200);
    $secondPageData = $response->json();
    
    expect($secondPageData['meta']['current_page'])->toBe(2);
    expect($secondPageData['meta']['last_page'])->toBe(2);
    expect($secondPageData['links']['prev'])->not->toBeNull();
    expect($secondPageData['links']['next'])->toBeNull();
    expect(count($secondPageData['data']))->toBe(5);

    // Test invalid page (beyond last page)
    $response = $this->withoutMiddleware()->getJson('/api/projects?per_page=5&page=3');
    $response->assertStatus(200);
    $invalidPageData = $response->json();
    
    expect($invalidPageData['meta']['current_page'])->toBe(3);
    expect(count($invalidPageData['data']))->toBe(0); // No data on invalid page
})->repeat(5);

it('maintains consistent pagination metadata across requests', function () {
    // Create testimonials
    $totalTestimonials = 15;
    for ($i = 0; $i < $totalTestimonials; $i++) {
        Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'status' => 'approved',
        ]);
    }

    // Make multiple requests to the same endpoint
    $responses = collect();
    for ($i = 0; $i < 3; $i++) {
        $response = $this->withoutMiddleware()->getJson('/api/testimonials?per_page=5');
        $response->assertStatus(200);
        $responses->push($response->json());
    }

    // All responses should have identical metadata
    $firstMeta = $responses->first()['meta'];
    foreach ($responses as $responseData) {
        expect($responseData['meta'])->toBe($firstMeta);
        expect($responseData['meta']['total'])->toBe($totalTestimonials);
        expect($responseData['meta']['per_page'])->toBe(5);
        expect($responseData['meta']['current_page'])->toBe(1);
    }
})->repeat(5);

it('handles edge cases in pagination', function () {
    // Test with no data
    $response = $this->withoutMiddleware()->getJson('/api/projects');
    $response->assertStatus(200);
    $emptyData = $response->json();
    
    expect($emptyData['meta']['total'])->toBe(0);
    expect($emptyData['meta']['current_page'])->toBe(1);
    expect($emptyData['meta']['last_page'])->toBe(1);
    expect(count($emptyData['data']))->toBe(0);
    expect($emptyData['links']['prev'])->toBeNull();
    expect($emptyData['links']['next'])->toBeNull();

    // Test with exactly one item
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

    $response = $this->withoutMiddleware()->getJson('/api/projects');
    $response->assertStatus(200);
    $singleItemData = $response->json();
    
    expect($singleItemData['meta']['total'])->toBe(1);
    expect($singleItemData['meta']['current_page'])->toBe(1);
    expect($singleItemData['meta']['last_page'])->toBe(1);
    expect(count($singleItemData['data']))->toBe(1);
    expect($singleItemData['links']['prev'])->toBeNull();
    expect($singleItemData['links']['next'])->toBeNull();
})->repeat(5);

it('validates pagination parameters correctly', function () {
    // Create some test data
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    for ($i = 0; $i < 5; $i++) {
        Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => $i + 1,
        ]);
    }

    // Test with invalid per_page values (should use defaults or handle gracefully)
    $invalidPerPageValues = [0, -1, 'abc', 1000];
    
    foreach ($invalidPerPageValues as $perPage) {
        $response = $this->withoutMiddleware()->getJson("/api/projects?per_page={$perPage}");
        $response->assertStatus(200); // Should not crash
        
        $responseData = $response->json();
        expect($responseData)->toHaveKeys(['data', 'links', 'meta']);
        expect($responseData['meta']['total'])->toBe(5);
    }

    // Test with invalid page values
    $invalidPageValues = [0, -1, 'abc'];
    
    foreach ($invalidPageValues as $page) {
        $response = $this->withoutMiddleware()->getJson("/api/projects?page={$page}");
        $response->assertStatus(200); // Should not crash
        
        $responseData = $response->json();
        expect($responseData)->toHaveKeys(['data', 'links', 'meta']);
    }
})->repeat(3);