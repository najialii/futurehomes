<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Service;
use App\Models\Project;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContentDeletionService
{
    /**
     * Delete a company and clean up associated files
     */
    public function deleteCompany(Company $company): bool
    {
        try {
            // Delete logo file if exists
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            // Delete media library files
            $company->clearMediaCollection('logo');

            // Delete the company record
            $company->delete();

            Log::info('Company deleted successfully', ['company_id' => $company->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete company', [
                'company_id' => $company->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete a service and clean up associated files and relationships
     */
    public function deleteService(Service $service): bool
    {
        try {
            // Delete icon file if exists
            if ($service->icon_path) {
                Storage::disk('public')->delete($service->icon_path);
            }

            // Delete media library files
            $service->clearMediaCollection('icon');

            // Delete associated projects (cascade)
            foreach ($service->projects as $project) {
                $this->deleteProject($project);
            }

            // Delete the service record
            $service->delete();

            Log::info('Service deleted successfully', ['service_id' => $service->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete service', [
                'service_id' => $service->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete a project and clean up associated files
     */
    public function deleteProject(Project $project): bool
    {
        try {
            // Delete associated images
            foreach ($project->images as $image) {
                // Delete original image
                if ($image->image_path) {
                    Storage::disk('public')->delete($image->image_path);
                }

                // Delete thumbnail
                $thumbnailPath = $this->getThumbnailPath($image->image_path);
                if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }

                // Delete image record
                $image->delete();
            }

            // Delete media library files
            if (method_exists($project, 'clearMediaCollection')) {
                $project->clearMediaCollection('images');
            }

            // Delete the project record
            $project->delete();

            Log::info('Project deleted successfully', ['project_id' => $project->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete project', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Delete a partner and clean up associated files
     */
    public function deletePartner(Partner $partner): bool
    {
        try {
            // Delete logo file if exists
            if ($partner->logo_path) {
                Storage::disk('public')->delete($partner->logo_path);
            }

            // Delete media library files
            $partner->clearMediaCollection('logo');

            // Delete the partner record
            $partner->delete();

            Log::info('Partner deleted successfully', ['partner_id' => $partner->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete partner', [
                'partner_id' => $partner->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete a testimonial and clean up associated files
     */
    public function deleteTestimonial(Testimonial $testimonial): bool
    {
        try {
            // Delete client photo if exists
            if ($testimonial->client_photo_path) {
                Storage::disk('public')->delete($testimonial->client_photo_path);
            }

            // Delete media library files
            $testimonial->clearMediaCollection('client_photo');

            // Delete the testimonial record
            $testimonial->delete();

            Log::info('Testimonial deleted successfully', ['testimonial_id' => $testimonial->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete testimonial', [
                'testimonial_id' => $testimonial->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete a page (soft delete to preserve version history)
     */
    public function deletePage(Page $page): bool
    {
        try {
            // Soft delete the page to preserve version history
            $page->delete();

            Log::info('Page deleted successfully', ['page_id' => $page->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete page', [
                'page_id' => $page->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete a contact submission
     */
    public function deleteContactSubmission(ContactSubmission $submission): bool
    {
        try {
            // Delete the contact submission record
            $submission->delete();

            Log::info('Contact submission deleted successfully', ['submission_id' => $submission->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete contact submission', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Clean up orphaned files in storage
     */
    public function cleanupOrphanedFiles(): array
    {
        $cleaned = [];
        $directories = ['companies', 'services', 'projects', 'partners', 'testimonials'];

        foreach ($directories as $directory) {
            $files = Storage::disk('public')->files($directory);
            
            foreach ($files as $file) {
                // Check if file is referenced in database
                if (!$this->isFileReferenced($file)) {
                    Storage::disk('public')->delete($file);
                    $cleaned[] = $file;
                }
            }
        }

        Log::info('Orphaned files cleaned up', ['files_count' => count($cleaned)]);
        return $cleaned;
    }

    /**
     * Get thumbnail path for an image
     */
    private function getThumbnailPath(string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        $pathInfo = pathinfo($imagePath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
    }

    /**
     * Check if a file is referenced in the database
     */
    private function isFileReferenced(string $filePath): bool
    {
        // Check in companies
        if (Company::where('logo_path', $filePath)->exists()) {
            return true;
        }

        // Check in services
        if (Service::where('icon_path', $filePath)->exists()) {
            return true;
        }

        // Check in partners
        if (Partner::where('logo_path', $filePath)->exists()) {
            return true;
        }

        // Check in testimonials
        if (Testimonial::where('client_photo_path', $filePath)->exists()) {
            return true;
        }

        // Check in project images
        if (\DB::table('project_images')->where('image_path', $filePath)->exists()) {
            return true;
        }

        return false;
    }
}