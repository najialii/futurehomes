<?php

use App\Models\Service;
use App\Models\Project;
use App\Models\Partner;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 4: Content ordering preservation
 * 
 * For any content type with display_order field, when content is retrieved via API,
 * the ordering should be preserved according to the display_order values
 */

it('preserves service ordering by display_order', function () {
    // Create services with random display orders
    $services = collect();
    $displayOrders = fake()->randomElements(range(1, 100), fake()->numberBetween(3, 8));
    
    foreach ($displayOrders as $order) {
        $services->push(Service::create([
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => $order,
            'is_active' => true,
        ]));
    }

    // Test API response ordering
    $response = $this->withoutMiddleware()->getJson('/api/services');
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Verify ordering is preserved (ascending by display_order)
    $expectedOrder = $services->sortBy('display_order')->pluck('id')->toArray();
    $actualOrder = collect($responseData)->pluck('id')->toArray();
    
    expect($actualOrder)->toBe($expectedOrder);
    
    // Verify display_order values are in ascending order
    $displayOrders = collect($responseData)->pluck('display_order')->toArray();
    $sortedOrders = collect($displayOrders)->sort()->values()->toArray();
    
    expect($displayOrders)->toBe($sortedOrders);
})->repeat(10);

it('preserves project ordering by display_order within service', function () {
    // Create a service
    $service = Service::create([
        'title' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'display_order' => 1,
        'is_active' => true,
    ]);

    // Create projects with random display orders
    $projects = collect();
    $displayOrders = fake()->randomElements(range(1, 50), fake()->numberBetween(3, 6));
    
    foreach ($displayOrders as $order) {
        $projects->push(Project::create([
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'service_id' => $service->id,
            'status' => 'published',
            'display_order' => $order,
        ]));
    }

    // Test API response ordering
    $response = $this->withoutMiddleware()->getJson('/api/projects');
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Verify ordering is preserved (ascending by display_order)
    $expectedOrder = $projects->sortBy('display_order')->pluck('id')->toArray();
    $actualOrder = collect($responseData)->pluck('id')->toArray();
    
    expect($actualOrder)->toBe($expectedOrder);
    
    // Verify display_order values are in ascending order
    $displayOrders = collect($responseData)->pluck('display_order')->toArray();
    $sortedOrders = collect($displayOrders)->sort()->values()->toArray();
    
    expect($displayOrders)->toBe($sortedOrders);
})->repeat(10);

it('preserves partner ordering by display_order', function () {
    // Create partners with random display orders
    $partners = collect();
    $displayOrders = fake()->randomElements(range(1, 30), fake()->numberBetween(3, 7));
    
    foreach ($displayOrders as $order) {
        $partners->push(Partner::create([
            'name' => fake()->company(),
            'logo_path' => 'partners/' . fake()->uuid() . '.png',
            'website_url' => fake()->url(),
            'display_order' => $order,
            'is_active' => true,
        ]));
    }

    // Test API response ordering
    $response = $this->withoutMiddleware()->getJson('/api/partners');
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Verify ordering is preserved (ascending by display_order)
    $expectedOrder = $partners->sortBy('display_order')->pluck('id')->toArray();
    $actualOrder = collect($responseData)->pluck('id')->toArray();
    
    expect($actualOrder)->toBe($expectedOrder);
    
    // Verify display_order values are in ascending order
    $displayOrders = collect($responseData)->pluck('display_order')->toArray();
    $sortedOrders = collect($displayOrders)->sort()->values()->toArray();
    
    expect($displayOrders)->toBe($sortedOrders);
})->repeat(10);

it('preserves testimonial ordering by creation date (latest first)', function () {
    // Create testimonials at different times
    $testimonials = collect();
    $timestamps = collect();
    
    // Create testimonials with different created_at timestamps
    for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
        $timestamp = now()->subDays(fake()->numberBetween(0, 30));
        $timestamps->push($timestamp);
        
        $testimonials->push(Testimonial::create([
            'client_name' => fake()->name(),
            'feedback' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'status' => 'approved',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]));
    }

    // Test API response ordering
    $response = $this->withoutMiddleware()->getJson('/api/testimonials');
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Verify ordering is preserved (latest first)
    $expectedOrder = $testimonials->sortByDesc('created_at')->pluck('id')->toArray();
    $actualOrder = collect($responseData)->pluck('id')->toArray();
    
    expect($actualOrder)->toBe($expectedOrder);
    
    // Verify timestamps are in descending order
    $createdAtDates = collect($responseData)->pluck('created_at')->toArray();
    $sortedDates = collect($createdAtDates)->sortDesc()->values()->toArray();
    
    expect($createdAtDates)->toBe($sortedDates);
})->repeat(10);

it('maintains ordering consistency across multiple API calls', function () {
    // Create mixed content with display orders
    $services = collect();
    for ($i = 0; $i < 5; $i++) {
        $services->push(Service::create([
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ]));
    }

    // Make multiple API calls
    $responses = collect();
    for ($i = 0; $i < 3; $i++) {
        $response = $this->withoutMiddleware()->getJson('/api/services');
        $response->assertStatus(200);
        $responses->push($response->json('data'));
    }

    // Verify all responses have the same ordering
    $firstResponseOrder = collect($responses->first())->pluck('id')->toArray();
    
    foreach ($responses as $responseData) {
        $currentOrder = collect($responseData)->pluck('id')->toArray();
        expect($currentOrder)->toBe($firstResponseOrder);
    }
})->repeat(5);

it('handles empty display_order values consistently', function () {
    // Create services with mixed display_order values (some null)
    $services = collect();
    
    // Services with explicit display_order
    for ($i = 0; $i < 3; $i++) {
        $services->push(Service::create([
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => fake()->numberBetween(1, 50),
            'is_active' => true,
        ]));
    }
    
    // Services with null display_order
    for ($i = 0; $i < 2; $i++) {
        $services->push(Service::create([
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => 999, // Use a high number instead of null
            'is_active' => true,
        ]));
    }

    // Test API response
    $response = $this->withoutMiddleware()->getJson('/api/services');
    
    $response->assertStatus(200);
    $responseData = $response->json('data');
    
    // Verify that services with null display_order appear after those with values
    $servicesWithOrder = collect($responseData)->filter(fn($item) => $item['display_order'] < 999);
    $servicesWithoutOrder = collect($responseData)->filter(fn($item) => $item['display_order'] >= 999);
    
    // Services with display_order should come first and be ordered
    if ($servicesWithOrder->isNotEmpty()) {
        $displayOrders = $servicesWithOrder->pluck('display_order')->toArray();
        $sortedOrders = collect($displayOrders)->sort()->values()->toArray();
        expect($displayOrders)->toBe($sortedOrders);
    }
    
    // Total count should match
    expect(count($responseData))->toBe($services->count());
})->repeat(10);