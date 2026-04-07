<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Street;
use Illuminate\Http\Request;

class StreetController extends Controller
{
    public function index()
    {
        $streets = Street::latest()->paginate(10);

        return view('streets.index', compact('streets'));
    }

    public function create()
    {
        return view('streets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:streets',
            'zone' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            Street::create($data);

            \Log::info('Street created successfully', [
                'street_name' => $data['name'],
                'zone' => $data['zone'],
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('streets.index')->with('success', 'Street added!');
        } catch (\Exception $e) {
            \Log::error('Street creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create street. Please try again.')
                ->withInput();
        }
    }

    public function show(Street $street)
    {
        return view('streets.show', compact('street'));
    }

    // app/Models/Street.php
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function edit(Street $street)
    {
        return view('streets.edit', compact('street'));
    }

    public function update(Request $request, Street $street)
    {
        $data = $request->validate([
            'name' => 'required|unique:streets,name,'.$street->id,
            'zone' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $street->update($data);

            \Log::info('Street updated successfully', [
                'street_id' => $street->id,
                'street_name' => $data['name'],
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('streets.index')->with('success', 'Street updated!');
        } catch (\Exception $e) {
            \Log::error('Street update failed', [
                'street_id' => $street->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update street. Please try again.')
                ->withInput();
        }
    }

    public function destroy(Street $street)
    {
        try {
            $streetName = $street->name;
            $street->delete();

            \Log::info('Street deleted successfully', [
                'street_name' => $streetName,
                'deleted_by' => auth()->id(),
            ]);

            return redirect()->route('streets.index')->with('success', 'Street deleted.');
        } catch (\Exception $e) {
            \Log::error('Street deletion failed', [
                'street_id' => $street->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete street. It may be in use by other records.');
        }
    }
}
