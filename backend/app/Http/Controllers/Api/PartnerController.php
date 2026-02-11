<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        } else {

            $query->where('is_active', true);
        }

        $partners = $query->orderBy('display_order')->get();

        return PartnerResource::collection($partners);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $partner = Partner::create($validated);

        return new PartnerResource($partner);
    }

    
    public function show(Partner $partner)
    {
        return new PartnerResource($partner);
    }

    
    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $partner->update($validated);

        return new PartnerResource($partner);
    }

    
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return response()->json([
            'message' => 'Partner deleted successfully'
        ]);
    }
}
