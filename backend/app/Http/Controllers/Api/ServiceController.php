<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    
    public function index(Request $request)
    {
        $cacheKey = 'services_' . md5(json_encode($request->all()));
        
        $services = cache()->remember($cacheKey, 3600, function () use ($request) {
            $query = Service::query();

            if ($request->has('active')) {
                $query->where('is_active', $request->boolean('active'));
            } else {
                $query->where('is_active', true);
            }

            if ($request->has('with_projects_count')) {
                $query->withCount('projects');
            }

            if ($request->has('with_projects')) {
                $query->with(['projects' => function ($q) {
                    $q->where('status', 'published')->orderBy('display_order');
                }]);
            }

            return $query->orderBy('display_order')->get();
        });

        return ServiceResource::collection($services);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $service = Service::create($validated);

        return new ServiceResource($service);
    }

    
    public function show(Service $service, Request $request)
    {

        if ($request->has('with_projects')) {
            $service->load(['projects' => function ($q) {
                $q->published()->ordered()->with('images');
            }]);
        }

        return new ServiceResource($service);
    }

    
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return new ServiceResource($service);
    }

    
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully'
        ]);
    }
}
