<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use Illuminate\Http\Request;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = Stat::active()->ordered()->get();
        
        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:50',
            'icon' => 'nullable|string|max:255',
            'display_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $stat = Stat::create($validated);

        return response()->json([
            'message' => 'Stat created successfully',
            'data' => $stat
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stat $stat)
    {
        return response()->json([
            'data' => $stat
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stat $stat)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:50',
            'icon' => 'nullable|string|max:255',
            'display_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $stat->update($validated);

        return response()->json([
            'message' => 'Stat updated successfully',
            'data' => $stat
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stat $stat)
    {
        $stat->delete();

        return response()->json([
            'message' => 'Stat deleted successfully'
        ]);
    }
}
