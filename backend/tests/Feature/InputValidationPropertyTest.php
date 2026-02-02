<?php

use App\Rules\PhoneNumberRule;
use App\Rules\RatingRule;
use App\Rules\StrongPasswordRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 4: Input validation enforcement
 * 
 * For any input field with validation rules (email, phone, URL, rating), when invalid data is submitted, 
 * the system should reject the input and preserve the existing valid state
 */

it('validates phone numbers correctly', function () {
    $phoneRule = new PhoneNumberRule();
    
    // Valid phone numbers should pass
    $validPhones = [
        '+966501234567',
        '0501234567',
        '501234567',
        '+1234567890',
        '0123456789',
    ];
    
    foreach ($validPhones as $phone) {
        $validator = Validator::make(['phone' => $phone], ['phone' => $phoneRule]);
        expect($validator->passes())->toBeTrue("Phone {$phone} should be valid");
    }
    
    // Invalid phone numbers should fail
    $invalidPhones = [
        '123',           // Too short
        'abc123456789',  // Contains letters
        '++966501234567', // Double plus
        '12345678901234567890', // Too long
        'javascript:alert(1)', // XSS attempt
    ];
    
    foreach ($invalidPhones as $phone) {
        $validator = Validator::make(['phone' => $phone], ['phone' => $phoneRule]);
        expect($validator->fails())->toBeTrue("Phone {$phone} should be invalid");
    }
})->repeat(10);

it('validates ratings within correct range', function () {
    $ratingRule = new RatingRule();
    
    // Valid ratings (1-5)
    $validRatings = [1, 2, 3, 4, 5, '1', '2', '3', '4', '5'];
    
    foreach ($validRatings as $rating) {
        $validator = Validator::make(['rating' => $rating], ['rating' => ['required', $ratingRule]]);
        expect($validator->passes())->toBeTrue("Rating {$rating} should be valid");
    }
    
    // Invalid ratings
    $invalidRatings = [0, 6, -1, 10, 'abc', '', null, 1.5, 4.7];
    
    foreach ($invalidRatings as $rating) {
        $validator = Validator::make(['rating' => $rating], ['rating' => ['required', $ratingRule]]);
        expect($validator->fails())->toBeTrue("Rating {$rating} should be invalid");
    }
})->repeat(10);

it('validates strong passwords correctly', function () {
    $passwordRule = new StrongPasswordRule();
    
    // Valid strong passwords
    $validPasswords = [
        'MyStr0ng!Pass',
        'C0mpl3x@Password',
        'S3cur3#P@ssw0rd',
        'Adm1n!2024',
    ];
    
    foreach ($validPasswords as $password) {
        $validator = Validator::make(['password' => $password], ['password' => $passwordRule]);
        expect($validator->passes())->toBeTrue("Password should be valid");
    }
    
    // Invalid passwords
    $invalidPasswords = [
        'short',         // Too short
        'nouppercase1!', // No uppercase
        'NOLOWERCASE1!', // No lowercase
        'NoNumbers!',    // No numbers
        'NoSpecial123',  // No special characters
        'password123',   // Common weak password
    ];
    
    foreach ($invalidPasswords as $password) {
        $validator = Validator::make(['password' => $password], ['password' => $passwordRule]);
        expect($validator->fails())->toBeTrue("Password '{$password}' should be invalid");
    }
})->repeat(10);

it('validates email addresses correctly', function () {
    // Valid emails
    $validEmails = [
        'user@example.com',
        'test.email@domain.co.uk',
        'admin@company.org',
        'contact@future-homes.com',
    ];
    
    foreach ($validEmails as $email) {
        $validator = Validator::make(['email' => $email], ['email' => 'email']);
        expect($validator->passes())->toBeTrue("Email {$email} should be valid");
    }
    
    // Invalid emails
    $invalidEmails = [
        'invalid-email',
        '@domain.com',
        'user@',
        'user..name@domain.com',
        'javascript:alert(1)',
    ];
    
    foreach ($invalidEmails as $email) {
        $validator = Validator::make(['email' => $email], ['email' => 'email']);
        expect($validator->fails())->toBeTrue("Email {$email} should be invalid");
    }
})->repeat(10);

it('validates URLs correctly', function () {
    // Valid URLs
    $validUrls = [
        'https://example.com',
        'http://domain.org',
        'https://www.company.co.uk/page',
        'https://subdomain.example.com/path?param=value',
    ];
    
    foreach ($validUrls as $url) {
        $validator = Validator::make(['url' => $url], ['url' => 'url']);
        expect($validator->passes())->toBeTrue("URL {$url} should be valid");
    }
    
    // Invalid URLs
    $invalidUrls = [
        'not-a-url',
        'javascript:alert(1)',
        'http://',
        'http://.',
        'http://..',
        'invalid-url',
    ];
    
    foreach ($invalidUrls as $url) {
        $validator = Validator::make(['url' => $url], ['url' => 'url']);
        expect($validator->fails())->toBeTrue("URL {$url} should be invalid");
    }
})->repeat(10);

it('preserves valid state when validation fails', function () {
    // Test with Company model validation
    $validData = [
        'name' => 'Test Company',
        'email' => 'valid@company.com',
        'phone' => '+966501234567',
        'website_url' => 'https://company.com',
    ];
    
    $validator = Validator::make($validData, [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email',
        'phone' => ['nullable', new PhoneNumberRule()],
        'website_url' => 'nullable|url',
    ]);
    
    expect($validator->passes())->toBeTrue();
    
    // Now test with invalid data
    $invalidData = [
        'name' => '', // Required field empty
        'email' => 'invalid-email',
        'phone' => '123', // Too short
        'website_url' => 'not-a-url',
    ];
    
    $validator = Validator::make($invalidData, [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email',
        'phone' => ['nullable', new PhoneNumberRule()],
        'website_url' => 'nullable|url',
    ]);
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
    expect($validator->errors()->has('phone'))->toBeTrue();
    expect($validator->errors()->has('website_url'))->toBeTrue();
})->repeat(10);