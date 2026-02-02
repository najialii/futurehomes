<?php

use App\Models\Service;
use App\Models\Project;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 5: Status-based filtering consistency
 * 
 * For any content type with status fields, when filtering by status is applied,
 * only content matching the specified status should be returned
 */

it('filters services by active status correctly', function () {
    // Create services with mixed active status
    $activeServices = collect();
    $inactiveServices = collect();
    
    // Create active services
    for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
        $activeServices->push(Service::create([
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ]));
    }
    
    // Create inactive services
    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
        $inactiveServices->push(Service::create([
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => fake()->numberBetween(1, 100),
            'is_active' => false,
        ]));
    }

    // Test default behavior (should return only active)
    $response = $this->withoutMiddleware()->getJson('/api/services');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // All returned services should be active
    foreach ($responseData as $service) {
        expect($service['is_active'])->toBe(true);
    }
    
    // Count should match active services
    expect(count($responseData))->toBe($activeServices->count());
    
    // Test explicit active filter
    $response = $this->withoutMiddleware()->getJson('/api/services?active=1');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $service) {
        expect($service['is_active'])->toBe(true);
    }
    
    // Test inactive filter
    $response = $this->withoutMiddleware()->getJson('/api/services?active=0');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $service) {
        expect($service['is_active'])->toBe(false);
    }
    
    expect(count($responseData))->toBe($inactiveServices->count());
})->repeat(10);

it('filters projects by status correctly', function () {
    // Create a service first
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    // Create projects with mixed status
    $publishedProjects = collect();
    $draftProjects = collect();
    
    // Create published projects
    for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
        $publishedProjects->push(Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => fake()->numberBetween(1, 100),
        ]));
    }
    
    // Create draft projects
    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
        $draftProjects->push(Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'draft',
            'display_order' => fake()->numberBetween(1, 100),
        ]));
    }

    // Test default behavior (should return only published)
    $response = $this->withoutMiddleware()->getJson('/api/projects');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // All returned projects should be published
    foreach ($responseData as $project) {
        expect($project['status'])->toBe('published');
    }
    
    // Count should match published projects
    expect(count($responseData))->toBe($publishedProjects->count());
    
    // Test explicit published filter
    $response = $this->withoutMiddleware()->getJson('/api/projects?status=published');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $project) {
        expect($project['status'])->toBe('published');
    }
    
    // Test draft filter
    $response = $this->withoutMiddleware()->getJson('/api/projects?status=draft');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $project) {
        expect($project['status'])->toBe('draft');
    }
    
    expect(count($responseData))->toBe($draftProjects->count());
})->repeat(10);

it('filters partners by active status correctly', function () {
    // Create partners with mixed active status
    $activePartners = collect();
    $inactivePartners = collect();
    
    // Create active partners
    for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
        $activePartners->push(Partner::create([
            'name' => fake()->company(),
            'logo_path' => 'partners/' . fake()->uuid() . '.png',
            'website_url' => fake()->url(),
            'display_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ]));
    }
    
    // Create inactive partners
    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
        $inactivePartners->push(Partner::create([
            'name' => fake()->company(),
            'logo_path' => 'partners/' . fake()->uuid() . '.png',
            'website_url' => fake()->url(),
            'display_order' => fake()->numberBetween(1, 100),
            'is_active' => false,
        ]));
    }

    // Test default behavior (should return only active)
    $response = $this->withoutMiddleware()->getJson('/api/partners');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // All returned partners should be active
    foreach ($responseData as $partner) {
        expect($partner['is_active'])->toBe(true);
    }
    
    // Count should match active partners
    expect(count($responseData))->toBe($activePartners->count());
    
    // Test explicit active filter
    $response = $this->withoutMiddleware()->getJson('/api/partners?active=1');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $partner) {
        expect($partner['is_active'])->toBe(true);
    }
    
    // Test inactive filter
    $response = $this->withoutMiddleware()->getJson('/api/partners?active=0');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $partner) {
        expect($partner['is_active'])->toBe(false);
    }
    
    expect(count($responseData))->toBe($inactivePartners->count());
})->repeat(10);

it('filters testimonials by status correctly', function () {
    // Create testimonials with mixed status
    $approvedTestimonials = collect();
    $pendingTestimonials = collect();
    $rejectedTestimonials = collect();
    
    // Create approved testimonials
    for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
        $approvedTestimonials->push(Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'status' => 'approved',
        ]));
    }
    
    // Create pending testimonials
    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
        $pendingTestimonials->push(Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'status' => 'pending',
        ]));
    }
    
    // Create rejected testimonials
    for ($i = 0; $i < fake()->numberBetween(1, 3); $i++) {
        $rejectedTestimonials->push(Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'status' => 'rejected',
        ]));
    }

    // Test default behavior (should return only approved)
    $response = $this->withoutMiddleware()->getJson('/api/testimonials');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // All returned testimonials should be approved
    foreach ($responseData as $testimonial) {
        expect($testimonial['status'])->toBe('approved');
    }
    
    // Test explicit approved filter
    $response = $this->withoutMiddleware()->getJson('/api/testimonials?status=approved');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $testimonial) {
        expect($testimonial['status'])->toBe('approved');
    }
    
    // Test pending filter
    $response = $this->withoutMiddleware()->getJson('/api/testimonials?status=pending');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $testimonial) {
        expect($testimonial['status'])->toBe('pending');
    }
    
    // Test rejected filter
    $response = $this->withoutMiddleware()->getJson('/api/testimonials?status=rejected');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $testimonial) {
        expect($testimonial['status'])->toBe('rejected');
    }
})->repeat(10);

it('filters pages by published status correctly', function () {
    // Create pages with mixed published status
    $publishedPages = collect();
    $draftPages = collect();
    
    // Create published pages
    for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
        $publishedPages->push(Page::create([
            'slug' => fake()->unique()->slug(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'meta_description' => fake()->sentence(),
            'is_published' => true,
        ]));
    }
    
    // Create draft pages
    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
        $draftPages->push(Page::create([
            'slug' => fake()->unique()->slug(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'meta_description' => fake()->sentence(),
            'is_published' => false,
        ]));
    }

    // Test default behavior (should return only published)
    $response = $this->withoutMiddleware()->getJson('/api/pages');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // All returned pages should be published
    foreach ($responseData as $page) {
        expect($page['is_published'])->toBe(true);
    }
    
    // Count should match published pages
    expect(count($responseData))->toBe($publishedPages->count());
    
    // Test explicit published filter
    $response = $this->withoutMiddleware()->getJson('/api/pages?published=1');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $page) {
        expect($page['is_published'])->toBe(true);
    }
    
    // Test draft filter
    $response = $this->withoutMiddleware()->getJson('/api/pages?published=0');
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $page) {
        expect($page['is_published'])->toBe(false);
    }
    
    expect(count($responseData))->toBe($draftPages->count());
})->repeat(10);

it('handles rating-based filtering for testimonials', function () {
    // Create testimonials with different ratings
    $highRatingTestimonials = collect();
    $lowRatingTestimonials = collect();
    
    // Create high rating testimonials (4-5 stars)
    for ($i = 0; $i < fake()->numberBetween(3, 5); $i++) {
        $rating = fake()->numberBetween(4, 5);
        $highRatingTestimonials->push(Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => $rating,
            'status' => 'approved',
        ]));
    }
    
    // Create low rating testimonials (1-3 stars)
    for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
        $rating = fake()->numberBetween(1, 3);
        $lowRatingTestimonials->push(Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => $rating,
            'status' => 'approved',
        ]));
    }

    // Test minimum rating filter
    $minRating = 4;
    $response = $this->withoutMiddleware()->getJson("/api/testimonials?min_rating={$minRating}");
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // All returned testimonials should have rating >= min_rating
    foreach ($responseData as $testimonial) {
        expect($testimonial['rating'])->toBeGreaterThanOrEqual($minRating);
        expect($testimonial['status'])->toBe('approved');
    }
    
    // Test different minimum rating
    $minRating = 3;
    $response = $this->withoutMiddleware()->getJson("/api/testimonials?min_rating={$minRating}");
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    foreach ($responseData as $testimonial) {
        expect($testimonial['rating'])->toBeGreaterThanOrEqual($minRating);
    }
})->repeat(10);

it('maintains filter consistency across multiple requests', function () {
    // Create mixed content
    Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);
    
    Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 2,
        'is_active' => false,
    ]);

    // Make multiple requests with same filter
    $responses = collect();
    for ($i = 0; $i < 3; $i++) {
        $response = $this->withoutMiddleware()->getJson('/api/services?active=1');
        $response->assertStatus(200);
        $responses->push($response->json('data'));
    }

    // All responses should be identical
    $firstResponse = $responses->first();
    foreach ($responses as $responseData) {
        expect($responseData)->toBe($firstResponse);
        
        // Verify all items match filter
        foreach ($responseData as $service) {
            expect($service['is_active'])->toBe(true);
        }
    }
})->repeat(5);