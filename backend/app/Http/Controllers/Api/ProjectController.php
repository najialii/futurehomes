<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('service_id')) {
            $query->where('service_id', $request->get('service_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        } else {

            $query->where('status', 'published');
        }

        $query->with(['service', 'images' => function ($q) {
            $q->orderBy('display_order');
        }]);

        $perPage = $request->get('per_page', 12);

        if (!is_numeric($perPage) || $perPage < 1) {
            $perPage = 12; // Default
        } elseif ($perPage > 100) {
            $perPage = 100; // Maximum limit
        }
        
        $projects = $query->orderBy('display_order')->paginate($perPage);

        return ProjectResource::collection($projects);
    }

    
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

    
    public function show(Project $project)
    {
        $project->load(['service', 'images' => function ($q) {
            $q->orderBy('display_order');
        }]);

        return new ProjectResource($project);
    }

    
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

    
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    
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

    public function featured()
    {
        $projects = Project::published()
            ->featured()
            ->with(['service', 'images' => function ($q) {
                $q->orderBy('display_order');
            }])
            ->orderBy('display_order')
            ->limit(4)
            ->get();

        return ProjectResource::collection($projects);
    }
}
