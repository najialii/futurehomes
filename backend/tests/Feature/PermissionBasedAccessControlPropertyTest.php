<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 11: Permission-based access control
 * 
 * For any user attempting to access restricted functionality, the system should 
 * enforce role-based permissions and deny access to unauthorized operations
 */

beforeEach(function () {
    // Seed roles and permissions
    $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
});

it('enforces permission-based access control for protected routes', function () {
    // Create users with different roles
    $adminUser = User::factory()->create();
    $adminUser->assignRole('admin');
    
    $editorUser = User::factory()->create();
    $editorUser->assignRole('editor');
    
    $viewerUser = User::factory()->create();
    $viewerUser->assignRole('viewer');
    
    $unauthorizedUser = User::factory()->create();
    // No role assigned
    
    // Test admin access to companies.create
    Sanctum::actingAs($adminUser);
    $response = $this->postJson('/api/companies', [
        'name' => fake()->company(),
        'description' => fake()->paragraph(),
        'email' => fake()->companyEmail(),
        'phone' => fake()->phoneNumber(),
        'address' => fake()->address(),
    ]);
    expect($response->status())->not->toBe(403, 'Admin should have access to companies.create');
    
    // Test editor access to companies.create (should have permission)
    Sanctum::actingAs($editorUser);
    $response = $this->postJson('/api/companies', [
        'name' => fake()->company(),
        'description' => fake()->paragraph(),
        'email' => fake()->companyEmail(),
        'phone' => fake()->phoneNumber(),
        'address' => fake()->address(),
    ]);
    expect($response->status())->not->toBe(403, 'Editor should have access to companies.create');
    
    // Test viewer access to companies.create (should NOT have permission)
    Sanctum::actingAs($viewerUser);
    $response = $this->postJson('/api/companies', [
        'name' => fake()->company(),
        'description' => fake()->paragraph(),
        'email' => fake()->companyEmail(),
        'phone' => fake()->phoneNumber(),
        'address' => fake()->address(),
    ]);
    expect($response->status())->toBe(403, 'Viewer should NOT have access to companies.create');
    
    // Test unauthorized user access (should NOT have permission)
    Sanctum::actingAs($unauthorizedUser);
    $response = $this->postJson('/api/companies', [
        'name' => fake()->company(),
        'description' => fake()->paragraph(),
        'email' => fake()->companyEmail(),
        'phone' => fake()->phoneNumber(),
        'address' => fake()->address(),
    ]);
    expect($response->status())->toBe(403, 'Unauthorized user should NOT have access to companies.create');
})->repeat(5);

it('enforces permission-based access control for delete operations', function () {
    $adminUser = User::factory()->create();
    $adminUser->assignRole('admin');
    
    $editorUser = User::factory()->create();
    $editorUser->assignRole('editor');
    
    $viewerUser = User::factory()->create();
    $viewerUser->assignRole('viewer');
    
    // Test admin access to delete operations (should have permission)
    Sanctum::actingAs($adminUser);
    $response = $this->deleteJson('/api/companies/1');
    expect($response->status())->not->toBe(403, 'Admin should have access to companies.delete');
    
    // Test editor access to delete operations (should NOT have permission)
    Sanctum::actingAs($editorUser);
    $response = $this->deleteJson('/api/companies/1');
    expect($response->status())->toBe(403, 'Editor should NOT have access to companies.delete');
    
    // Test viewer access to delete operations (should NOT have permission)
    Sanctum::actingAs($viewerUser);
    $response = $this->deleteJson('/api/companies/1');
    expect($response->status())->toBe(403, 'Viewer should NOT have access to companies.delete');
})->repeat(5);

it('denies access to unauthenticated users for protected operations', function () {
    // Test various operations without authentication
    $operations = [
        ['POST', '/api/companies', ['name' => 'Test Company']],
        ['PUT', '/api/companies/1', ['name' => 'Updated Company']],
        ['DELETE', '/api/companies/1'],
        ['POST', '/api/services', ['title' => 'Test Service']],
        ['PUT', '/api/services/1', ['title' => 'Updated Service']],
        ['DELETE', '/api/services/1'],
        ['POST', '/api/projects', ['name' => 'Test Project']],
        ['PUT', '/api/projects/1', ['name' => 'Updated Project']],
        ['DELETE', '/api/projects/1'],
        ['POST', '/api/partners', ['name' => 'Test Partner']],
        ['PUT', '/api/partners/1', ['name' => 'Updated Partner']],
        ['DELETE', '/api/partners/1'],
        ['POST', '/api/testimonials', ['client_name' => 'Test Client']],
        ['PUT', '/api/testimonials/1', ['client_name' => 'Updated Client']],
        ['DELETE', '/api/testimonials/1'],
        ['POST', '/api/pages', ['title' => 'Test Page']],
        ['PUT', '/api/pages/1', ['title' => 'Updated Page']],
        ['DELETE', '/api/pages/1'],
    ];
    
    foreach ($operations as $operation) {
        $method = $operation[0];
        $url = $operation[1];
        $data = $operation[2] ?? [];
        
        $response = match($method) {
            'POST' => $this->postJson($url, $data),
            'PUT' => $this->putJson($url, $data),
            'DELETE' => $this->deleteJson($url),
            default => $this->getJson($url)
        };
        
        expect($response->status())->toBe(401, "Unauthenticated access should be denied for {$method} {$url}");
    }
})->repeat(3);

it('validates role hierarchy and permission inheritance', function () {
    $adminUser = User::factory()->create();
    $adminUser->assignRole('admin');
    
    $editorUser = User::factory()->create();
    $editorUser->assignRole('editor');
    
    $viewerUser = User::factory()->create();
    $viewerUser->assignRole('viewer');
    
    // Test that admin has all permissions
    expect($adminUser->hasPermission('companies.create'))->toBeTrue();
    expect($adminUser->hasPermission('companies.delete'))->toBeTrue();
    expect($adminUser->hasPermission('users.create'))->toBeTrue();
    
    // Test that editor has content permissions but not user management
    expect($editorUser->hasPermission('companies.create'))->toBeTrue();
    expect($editorUser->hasPermission('companies.update'))->toBeTrue();
    expect($editorUser->hasPermission('companies.delete'))->toBeFalse();
    expect($editorUser->hasPermission('users.create'))->toBeFalse();
    
    // Test that viewer has only read permissions
    expect($viewerUser->hasPermission('companies.read'))->toBeTrue();
    expect($viewerUser->hasPermission('companies.create'))->toBeFalse();
    expect($viewerUser->hasPermission('companies.update'))->toBeFalse();
    expect($viewerUser->hasPermission('companies.delete'))->toBeFalse();
})->repeat(5);

it('handles permission checks correctly for different resource types', function () {
    $resources = ['companies', 'services', 'projects', 'partners', 'testimonials', 'pages'];
    $actions = ['create', 'read', 'update', 'delete'];
    
    $adminUser = User::factory()->create();
    $adminUser->assignRole('admin');
    
    $viewerUser = User::factory()->create();
    $viewerUser->assignRole('viewer');
    
    foreach ($resources as $resource) {
        foreach ($actions as $action) {
            $permission = "{$resource}.{$action}";
            
            // Admin should have all permissions
            expect($adminUser->hasPermission($permission))->toBeTrue(
                "Admin should have {$permission} permission"
            );
            
            // Viewer should only have read permissions
            if ($action === 'read') {
                expect($viewerUser->hasPermission($permission))->toBeTrue(
                    "Viewer should have {$permission} permission"
                );
            } else {
                expect($viewerUser->hasPermission($permission))->toBeFalse(
                    "Viewer should NOT have {$permission} permission"
                );
            }
        }
    }
})->repeat(3);

it('correctly enforces middleware permission checks on API routes', function () {
    $editorUser = User::factory()->create();
    $editorUser->assignRole('editor');
    
    $viewerUser = User::factory()->create();
    $viewerUser->assignRole('viewer');
    
    // Test that editor can access create endpoints
    Sanctum::actingAs($editorUser);
    $createRoutes = [
        '/api/companies',
        '/api/services', 
        '/api/projects',
        '/api/partners',
        '/api/testimonials',
        '/api/pages'
    ];
    
    foreach ($createRoutes as $route) {
        $response = $this->postJson($route, ['test' => 'data']);
        expect($response->status())->not->toBe(403, "Editor should have access to POST {$route}");
    }
    
    // Test that viewer cannot access create endpoints
    Sanctum::actingAs($viewerUser);
    foreach ($createRoutes as $route) {
        $response = $this->postJson($route, ['test' => 'data']);
        expect($response->status())->toBe(403, "Viewer should NOT have access to POST {$route}");
    }
    
    // Test permission enforcement by checking user permissions directly
    expect($editorUser->hasPermission('companies.create'))->toBeTrue();
    expect($editorUser->hasPermission('companies.delete'))->toBeFalse();
    expect($viewerUser->hasPermission('companies.create'))->toBeFalse();
    expect($viewerUser->hasPermission('companies.delete'))->toBeFalse();
})->repeat(3);