<?php

namespace App\Services;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HtmlSanitizationService
{
    protected HtmlSanitizer $sanitizer;

    public function __construct()
    {
        // Configure allowed HTML tags and attributes
        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowElement('img', ['src', 'alt', 'title', 'width', 'height'])
            ->allowElement('a', ['href', 'title', 'target'])
            ->allowElement('iframe', ['src', 'width', 'height', 'frameborder'])
            ->allowElement('video', ['src', 'controls', 'width', 'height'])
            ->allowElement('audio', ['src', 'controls'])
            ->allowAttribute('class', '*')
            ->allowAttribute('id', '*')
            ->allowAttribute('style', '*')
            ->blockElement('script')
            ->blockElement('object')
            ->blockElement('embed')
            ->blockElement('form')
            ->blockElement('input')
            ->blockElement('button');

        $this->sanitizer = new HtmlSanitizer($config);
    }

    /**
     * Sanitize HTML content for rich text fields
     */
    public function sanitizeRichText(string $html): string
    {
        if (empty($html)) {
            return '';
        }

        return $this->sanitizer->sanitize($html);
    }

    /**
     * Sanitize HTML content for basic text fields (strip all HTML)
     */
    public function sanitizeBasicText(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Strip all HTML tags
        $cleaned = strip_tags($text);
        
        // Decode HTML entities
        $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove extra whitespace
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
    }

    /**
     * Sanitize user input to prevent XSS
     */
    public function sanitizeUserInput(string $input): string
    {
        if (empty($input)) {
            return '';
        }

        // Remove potentially dangerous characters
        $input = str_replace(['<script', '</script>', 'javascript:', 'vbscript:', 'onload=', 'onerror='], '', $input);
        
        // Encode special characters
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return trim($input);
    }

    /**
     * Validate and sanitize URL
     */
    public function sanitizeUrl(string $url): string
    {
        if (empty($url)) {
            return '';
        }

        // Remove dangerous protocols
        $dangerousProtocols = ['javascript:', 'vbscript:', 'data:', 'file:'];
        
        foreach ($dangerousProtocols as $protocol) {
            if (stripos($url, $protocol) === 0) {
                return '';
            }
        }

        // Validate URL format
        $sanitized = filter_var($url, FILTER_SANITIZE_URL);
        
        if (!filter_var($sanitized, FILTER_VALIDATE_URL)) {
            return '';
        }

        return $sanitized;
    }

    /**
     * Check if content contains potentially dangerous elements
     */
    public function containsDangerousContent(string $content): bool
    {
        $dangerousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/on\w+\s*=/i', // Event handlers like onclick, onload, etc.
            '/<iframe\b[^>]*src\s*=\s*["\']?(?!https?:\/\/)[^"\'>\s]+/i', // Non-HTTP iframe sources
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Clean filename for safe storage
     */
    public function sanitizeFilename(string $filename): string
    {
        // Remove path traversal attempts
        $filename = basename($filename);
        
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Limit length
        if (strlen($filename) > 100) {
            $pathInfo = pathinfo($filename);
            $name = substr($pathInfo['filename'], 0, 90);
            $filename = $name . '.' . ($pathInfo['extension'] ?? '');
        }

        return $filename;
    }
}