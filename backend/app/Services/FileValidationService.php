<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FileValidationService
{
    /**
     * Validate image file upload
     */
    public function validateImage(UploadedFile $file, array $rules = []): void
    {
        $defaultRules = [
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,gif,webp,svg',
                'max:10240', // 10MB
            ]
        ];

        $rules = array_merge($defaultRules, $rules);

        $validator = Validator::make(['file' => $file], $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Validate document file upload
     */
    public function validateDocument(UploadedFile $file, array $rules = []): void
    {
        $defaultRules = [
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,txt',
                'max:5120', // 5MB
            ]
        ];

        $rules = array_merge($defaultRules, $rules);

        $validator = Validator::make(['file' => $file], $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Check if file is safe (basic security checks)
     */
    public function isFileSafe(UploadedFile $file): bool
    {
        // Check for executable extensions
        $dangerousExtensions = [
            'php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 'com', 'scr',
            'vbs', 'js', 'jar', 'sh', 'py', 'pl', 'rb', 'asp', 'aspx', 'jsp'
        ];

        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, $dangerousExtensions)) {
            return false;
        }

        // Check file content for PHP tags (basic check)
        $content = file_get_contents($file->getPathname());
        if (strpos($content, '<?php') !== false || strpos($content, '<?=') !== false) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize filename
     */
    public function sanitizeFilename(string $filename): string
    {
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Limit length
        if (strlen($filename) > 100) {
            $pathInfo = pathinfo($filename);
            $name = substr($pathInfo['filename'], 0, 90);
            $filename = $name . '.' . $pathInfo['extension'];
        }

        return $filename;
    }
}