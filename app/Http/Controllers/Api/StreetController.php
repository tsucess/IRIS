<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StreetResource;
use App\Models\Street;
use Illuminate\Http\Request;

class StreetController extends Controller
{
    /**
     * Display a listing of streets
     */
    public function index(Request $request)
    {
        $query = Street::query();

        // Filter by zone
        if ($request->has('zone')) {
            $query->where('zone', $request->zone);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Include counts
        if ($request->has('with_counts')) {
            $query->withCount(['users', 'projects']);
        }

        $perPage = $request->get('per_page', 15);
        $streets = $query->paginate($perPage);

        return StreetResource::collection($streets);
    }

    /**
     * Store a newly created street
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:streets,name',
            'zone' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $street = Street::create($validated);

            \Log::info('Street created via API', [
                'street_id' => $street->id,
                'created_by' => auth()->id(),
            ]);

            return new StreetResource($street);
        } catch (\Exception $e) {
            \Log::error('API street creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to create street',
                'error' => 'An error occurred',
            ], 500);
        }
    }

    /**
     * Display the specified street
     */
    public function show(Street $street)
    {
        $street->loadCount(['users', 'projects']);
        
        return new StreetResource($street);
    }

    /**
     * Update the specified street
     */
    public function update(Request $request, Street $street)
    {
        $validated = $request->validate([
            'name' => 'required|unique:streets,name,' . $street->id,
            'zone' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $street->update($validated);

            \Log::info('Street updated via API', [
                'street_id' => $street->id,
                'updated_by' => auth()->id(),
            ]);

            return new StreetResource($street);
        } catch (\Exception $e) {
            \Log::error('API street update failed', [
                'street_id' => $street->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to update street',
                'error' => 'An error occurred',
            ], 500);
        }
    }

    /**
     * Remove the specified street
     */
    public function destroy(Street $street)
    {
        try {
            $street->delete();

            \Log::info('Street deleted via API', [
                'street_id' => $street->id,
                'deleted_by' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Street deleted successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('API street deletion failed', [
                'street_id' => $street->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to delete street',
                'error' => 'An error occurred',
            ], 500);
        }
    }
}

