<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Design::query();

        if ($request->has('category')) {
            $query->byCategory($request->get('category'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        } else {

            $query->published();
        }

        if ($request->has('featured') && $request->get('featured') === 'true') {
            $query->featured();
        }

        if ($request->has('tags')) {
            $tags = explode(',', $request->get('tags'));
            $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tags', trim($tag));
                }
            });
        }

        $perPage = $request->get('per_page', 12);

        if (!is_numeric($perPage) || $perPage < 1) {
            $perPage = 12; // Default
        } elseif ($perPage > 100) {
            $perPage = 100; // Maximum limit
        }
        
        $designs = $query->ordered()->paginate($perPage);

        return DesignResource::collection($designs);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:interior,exterior,landscape,architectural,general',
            'image_path' => 'required|string',
            'alt_text' => 'nullable|string|max:255',
            'status' => 'in:draft,published',
            'is_featured' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $design = Design::create($validated);

        return new DesignResource($design);
    }

    
    public function show(Design $design)
    {
        return new DesignResource($design);
    }

    
    public function update(Request $request, Design $design)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|string|in:interior,exterior,landscape,architectural,general',
            'image_path' => 'sometimes|required|string',
            'alt_text' => 'nullable|string|max:255',
            'status' => 'sometimes|in:draft,published',
            'is_featured' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $design->update($validated);

        return new DesignResource($design);
    }

    
    public function destroy(Design $design)
    {
        $design->delete();

        return response()->json([
            'message' => 'Design deleted successfully'
        ]);
    }

    
    public function byCategory(Request $request, $category)
    {
        $query = Design::byCategory($category)
            ->published();

        $perPage = $request->get('per_page', 12);
        $designs = $query->ordered()->paginate($perPage);

        return DesignResource::collection($designs);
    }

    
    public function featured(Request $request)
    {
        $query = Design::featured()->published();

        $perPage = $request->get('per_page', 8);
        $designs = $query->ordered()->paginate($perPage);

        return DesignResource::collection($designs);
    }

    
    public function categories()
    {
        return response()->json([
            'data' => Design::getCategories()
        ]);
    }

    
    public function tags()
    {
        return response()->json([
            'data' => Design::getAvailableTags()
        ]);
    }
}
