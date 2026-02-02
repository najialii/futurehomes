<?php

use App\Services\HtmlSanitizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature: laravel-filament-cms, Property 9: HTML content sanitization
 * 
 * For any rich text content, when HTML is submitted, it should be sanitized to remove 
 * potentially dangerous elements while preserving safe formatting
 */

it('removes dangerous HTML elements consistently', function () {
    $sanitizer = new HtmlSanitizationService();
    
    // Dangerous HTML that should be removed
    $dangerousHtml = [
        '<script>alert("XSS")</script>',
        '<iframe src="javascript:alert(1)"></iframe>',
        '<object data="malicious.swf"></object>',
        '<embed src="malicious.swf">',
        '<form><input type="text"></form>',
        '<button onclick="alert(1)">Click</button>',
        '<img src="x" onerror="alert(1)">',
        '<a href="javascript:alert(1)">Link</a>',
    ];
    
    foreach ($dangerousHtml as $html) {
        $sanitized = $sanitizer->sanitizeRichText($html);
        
        // Should not contain dangerous elements
        expect($sanitized)->not->toContain('<script');
        expect($sanitized)->not->toContain('javascript:');
        expect($sanitized)->not->toContain('<object');
        expect($sanitized)->not->toContain('<embed');
        expect($sanitized)->not->toContain('<form');
        expect($sanitized)->not->toContain('<input');
        expect($sanitized)->not->toContain('<button');
        expect($sanitized)->not->toContain('onerror=');
        expect($sanitized)->not->toContain('onclick=');
    }
})->repeat(10);

it('preserves safe HTML elements consistently', function () {
    $sanitizer = new HtmlSanitizationService();
    
    // Safe HTML that should be preserved
    $safeHtml = [
        '<p>This is a paragraph</p>',
        '<h1>Heading</h1>',
        '<strong>Bold text</strong>',
        '<em>Italic text</em>',
        '<ul><li>List item</li></ul>',
        '<a href="https://example.com">Safe link</a>',
        '<img src="https://example.com/image.jpg" alt="Image">',
        '<blockquote>Quote</blockquote>',
    ];
    
    foreach ($safeHtml as $html) {
        $sanitized = $sanitizer->sanitizeRichText($html);
        
        // Should preserve basic structure
        expect($sanitized)->not->toBeEmpty();
        
        // Should not contain dangerous content
        expect($sanitizer->containsDangerousContent($sanitized))->toBeFalse();
    }
})->repeat(10);

it('sanitizes basic text by removing all HTML', function () {
    $sanitizer = new HtmlSanitizationService();
    
    $htmlTexts = [
        '<p>Simple paragraph</p>',
        '<script>alert("XSS")</script>Normal text',
        'Text with <strong>bold</strong> formatting',
        '<h1>Title</h1><p>Content</p>',
        'Mixed <em>content</em> with <a href="#">links</a>',
    ];
    
    foreach ($htmlTexts as $html) {
        $sanitized = $sanitizer->sanitizeBasicText($html);
        
        // Should not contain any HTML tags
        expect($sanitized)->not->toContain('<');
        expect($sanitized)->not->toContain('>');
        
        // Should still contain the text content
        expect($sanitized)->not->toBeEmpty();
        expect(strlen($sanitized))->toBeGreaterThan(0);
    }
})->repeat(10);

it('detects dangerous content consistently', function () {
    $sanitizer = new HtmlSanitizationService();
    
    // Content that should be flagged as dangerous
    $dangerousContent = [
        '<script>alert("XSS")</script>',
        'javascript:alert(1)',
        '<iframe src="data:text/html,<script>alert(1)</script>"></iframe>',
        '<img onerror="alert(1)" src="x">',
        '<div onclick="malicious()">Click me</div>',
        'vbscript:msgbox("XSS")',
    ];
    
    foreach ($dangerousContent as $content) {
        expect($sanitizer->containsDangerousContent($content))->toBeTrue(
            "Content should be flagged as dangerous: {$content}"
        );
    }
    
    // Safe content that should not be flagged
    $safeContent = [
        '<p>Normal paragraph</p>',
        '<a href="https://example.com">Safe link</a>',
        '<img src="https://example.com/image.jpg" alt="Image">',
        'Plain text content',
        '<strong>Bold text</strong>',
    ];
    
    foreach ($safeContent as $content) {
        expect($sanitizer->containsDangerousContent($content))->toBeFalse(
            "Content should be safe: {$content}"
        );
    }
})->repeat(10);

it('sanitizes URLs correctly', function () {
    $sanitizer = new HtmlSanitizationService();
    
    // Valid URLs that should be preserved
    $validUrls = [
        'https://example.com',
        'http://domain.org/path',
        'https://subdomain.example.com/page?param=value',
        'mailto:contact@example.com',
    ];
    
    foreach ($validUrls as $url) {
        $sanitized = $sanitizer->sanitizeUrl($url);
        expect($sanitized)->toBe($url);
    }
    
    // Dangerous URLs that should be removed
    $dangerousUrls = [
        'javascript:alert(1)',
        'vbscript:msgbox("XSS")',
        'data:text/html,<script>alert(1)</script>',
        'file:///etc/passwd',
    ];
    
    foreach ($dangerousUrls as $url) {
        $sanitized = $sanitizer->sanitizeUrl($url);
        expect($sanitized)->toBeEmpty("Dangerous URL should be removed: {$url}");
    }
})->repeat(10);

it('sanitizes filenames safely', function () {
    $sanitizer = new HtmlSanitizationService();
    
    // Dangerous filenames that should be cleaned
    $dangerousFilenames = [
        '../../../etc/passwd',
        'file<script>.txt',
        'document"with"quotes.pdf',
        'file|with|pipes.doc',
        'very-long-filename-that-exceeds-normal-limits-and-should-be-truncated-to-prevent-filesystem-issues.txt',
    ];
    
    foreach ($dangerousFilenames as $filename) {
        $sanitized = $sanitizer->sanitizeFilename($filename);
        
        // Should not contain path traversal
        expect($sanitized)->not->toContain('../');
        expect($sanitized)->not->toContain('/');
        expect($sanitized)->not->toContain('\\');
        
        // Should not contain dangerous characters
        expect($sanitized)->not->toContain('<');
        expect($sanitized)->not->toContain('>');
        expect($sanitized)->not->toContain('"');
        expect($sanitized)->not->toContain('|');
        
        // Should not be too long
        expect(strlen($sanitized))->toBeLessThanOrEqual(100);
        
        // Should not be empty (unless original was completely invalid)
        if (!empty(preg_replace('/[^a-zA-Z0-9._-]/', '', basename($filename)))) {
            expect($sanitized)->not->toBeEmpty();
        }
    }
})->repeat(10);

it('handles user input sanitization consistently', function () {
    $sanitizer = new HtmlSanitizationService();
    
    // Various types of potentially dangerous user input
    $userInputs = [
        '<script>alert("XSS")</script>',
        'Normal text with <b>HTML</b>',
        'Text with "quotes" and \'apostrophes\'',
        'Special chars: &<>"\' and more',
        'javascript:alert(1)',
        'Text with\nnewlines\tand\ttabs',
    ];
    
    foreach ($userInputs as $input) {
        $sanitized = $sanitizer->sanitizeUserInput($input);
        
        // Should not contain dangerous script tags
        expect($sanitized)->not->toContain('<script');
        expect($sanitized)->not->toContain('javascript:');
        
        // Should properly encode special characters (only if they weren't removed as dangerous content)
        if (strpos($input, '<') !== false && !strpos($input, '<script') && !strpos($input, '</script>')) {
            expect($sanitized)->toContain('&lt;');
        }
        if (strpos($input, '>') !== false && !strpos($input, '</script>')) {
            expect($sanitized)->toContain('&gt;');
        }
        if (strpos($input, '"') !== false) {
            expect($sanitized)->toContain('&quot;');
        }
        
        // Should not be empty unless input was empty
        if (!empty(trim($input))) {
            expect($sanitized)->not->toBeEmpty();
        }
    }
})->repeat(10);