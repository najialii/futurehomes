<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Testimonial::query();

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        } else {

            $query->where('status', 'approved');
        }

        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->get('min_rating'));
        }

        $perPage = $request->get('per_page', 10);

        if (!is_numeric($perPage) || $perPage < 1) {
            $perPage = 10; // Default
        } elseif ($perPage > 100) {
            $perPage = 100; // Maximum limit
        }
        
        $testimonials = $query->latest()->paginate($perPage);

        return TestimonialResource::collection($testimonials);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'in:pending,approved,rejected',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        $testimonial = Testimonial::create($validated);

        return new TestimonialResource($testimonial);
    }

    
    public function show(Testimonial $testimonial)
    {
        return new TestimonialResource($testimonial);
    }

    
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

    
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial deleted successfully'
        ]);
    }
}
