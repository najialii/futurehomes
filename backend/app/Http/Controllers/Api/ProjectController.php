<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Filter by service
        if ($request->has('service_id')) {
            $query->where('service_id', $request->get('service_id'));
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        } else {
            // Default to published projects only for public API
            $query->where('status', 'published');
        }

        // Always include service and images
        $query->with(['service', 'images' => function ($q) {
            $q->orderBy('display_order');
        }]);

        // Pagination
        $perPage = $request->get('per_page', 12);
        
        // Validate and sanitize per_page parameter
        if (!is_numeric($perPage) || $perPage < 1) {
            $perPage = 12; // Default
        } elseif ($perPage > 100) {
            $perPage = 100; // Maximum limit
        }
        
        $projects = $query->orderBy('display_order')->paginate($perPage);

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'status' => 'in:draft,published',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $project = Project::create($validated);
        $project->load(['service', 'images']);

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['service', 'images' => function ($q) {
            $q->orderBy('display_order');
        }]);

        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'service_id' => 'sometimes|required|exists:services,id',
            'status' => 'sometimes|in:draft,published',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $project->update($validated);
        $project->load(['service', 'images']);

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    /**
     * Get projects by service
     */
    public function byService(Request $request, $serviceId)
    {
        $query = Project::where('service_id', $serviceId)
            ->where('status', 'published')
            ->with(['service', 'images' => function ($q) {
                $q->orderBy('display_order');
            }]);

        $perPage = $request->get('per_page', 12);
        $projects = $query->orderBy('display_order')->paginate($perPage);

        return ProjectResource::collection($projects);
    }
}
