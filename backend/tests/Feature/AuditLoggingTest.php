<?php

use App\Models\AuditLog;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('creates audit log when company is created', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $company = Company::create([
        'name' => 'Test Company',
        'description' => 'A test company',
        'email' => 'test@company.com',
        'phone' => '+1234567890',
        'address' => '123 Test St',
    ]);
    
    $auditLog = AuditLog::where('auditable_type', Company::class)
        ->where('auditable_id', $company->id)
        ->where('event', 'created')
        ->first();
    
    expect($auditLog)->not->toBeNull();
    expect($auditLog->user_id)->toBe($user->id);
    expect($auditLog->new_values)->toHaveKey('name');
    expect($auditLog->new_values['name'])->toBe('Test Company');
});

it('creates audit log when company is updated', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $company = Company::create([
        'name' => 'Original Company',
        'description' => 'Original description',
        'email' => 'original@company.com',
    ]);
    
    // Clear existing audit logs
    AuditLog::truncate();
    
    $company->update([
        'name' => 'Updated Company',
        'description' => 'Updated description',
    ]);
    
    $auditLog = AuditLog::where('auditable_type', Company::class)
        ->where('auditable_id', $company->id)
        ->where('event', 'updated')
        ->first();
    
    expect($auditLog)->not->toBeNull();
    expect($auditLog->user_id)->toBe($user->id);
    expect($auditLog->old_values)->toHaveKey('name');
    expect($auditLog->old_values['name'])->toBe('Original Company');
    expect($auditLog->new_values)->toHaveKey('name');
    expect($auditLog->new_values['name'])->toBe('Updated Company');
});

it('creates audit log when company is deleted', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $company = Company::create([
        'name' => 'Company to Delete',
        'description' => 'This will be deleted',
        'email' => 'delete@company.com',
    ]);
    
    $companyId = $company->id;
    
    // Clear existing audit logs
    AuditLog::truncate();
    
    $company->delete();
    
    $auditLog = AuditLog::where('auditable_type', Company::class)
        ->where('auditable_id', $companyId)
        ->where('event', 'deleted')
        ->first();
    
    expect($auditLog)->not->toBeNull();
    expect($auditLog->user_id)->toBe($user->id);
    expect($auditLog->old_values)->toHaveKey('name');
    expect($auditLog->old_values['name'])->toBe('Company to Delete');
});

it('can cleanup old audit logs', function () {
    // Create some old audit logs using raw SQL to ensure proper timestamps
    $oldDate = now()->subDays(100)->format('Y-m-d H:i:s');
    $recentDate = now()->subDays(30)->format('Y-m-d H:i:s');
    
    DB::table('audit_logs')->insert([
        'event' => 'created',
        'auditable_type' => Company::class,
        'auditable_id' => 1,
        'new_values' => json_encode(['name' => 'Old Company']),
        'user_id' => null,
        'created_at' => $oldDate,
        'updated_at' => $oldDate,
    ]);
    
    DB::table('audit_logs')->insert([
        'event' => 'created',
        'auditable_type' => Company::class,
        'auditable_id' => 2,
        'new_values' => json_encode(['name' => 'Recent Company']),
        'user_id' => null,
        'created_at' => $recentDate,
        'updated_at' => $recentDate,
    ]);
    
    expect(AuditLog::count())->toBe(2);
    
    // Test the cleanup logic
    $deleted = AuditLog::where('created_at', '<', now()->subDays(90))->delete();
    
    expect($deleted)->toBe(1);
    expect(AuditLog::count())->toBe(1);
    expect(AuditLog::first()->new_values['name'])->toBe('Recent Company');
});

it('tracks request information in audit logs', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    // Simulate a web request by setting request data
    request()->merge([
        'REQUEST_URI' => '/admin/companies/create',
        'REMOTE_ADDR' => '127.0.0.1',
        'HTTP_USER_AGENT' => 'Test Browser'
    ]);
    
    $company = Company::create([
        'name' => 'API Test Company',
        'description' => 'Created via API',
        'email' => 'api@company.com',
    ]);
    
    $auditLog = AuditLog::where('auditable_type', Company::class)
        ->where('auditable_id', $company->id)
        ->where('event', 'created')
        ->first();
    
    expect($auditLog)->not->toBeNull();
    expect($auditLog->user_id)->toBe($user->id);
    expect($auditLog->ip_address)->not->toBeNull();
});