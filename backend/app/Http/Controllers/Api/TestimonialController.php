<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        } else {
            // Default to approved testimonials only for public API
            $query->where('status', 'approved');
        }

        // Filter by rating
        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->get('min_rating'));
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        
        // Validate and sanitize per_page parameter
        if (!is_numeric($perPage) || $perPage < 1) {
            $perPage = 10; // Default
        } elseif ($perPage > 100) {
            $perPage = 100; // Maximum limit
        }
        
        $testimonials = $query->latest()->paginate($perPage);

        return TestimonialResource::collection($testimonials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'in:pending,approved,rejected',
        ]);

        // Default status to pending for public submissions
        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        $testimonial = Testimonial::create($validated);

        return new TestimonialResource($testimonial);
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        return new TestimonialResource($testimonial);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'sometimes|required|string|max:255',
            'feedback' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'status' => 'sometimes|in:pending,approved,rejected',
        ]);

        $testimonial->update($validated);

        return new TestimonialResource($testimonial);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial deleted successfully'
        ]);
    }
}
