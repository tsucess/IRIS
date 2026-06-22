<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occupation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OccupationController extends Controller
{
    public function index(Request $request)
    {
        $query = Occupation::withCount('residents');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('category', 'like', "%{$term}%")
                  ->orWhere('sector', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('sector')) {
            $query->where('sector', $request->sector);
        }

        $occupations = $query->orderBy('name')->paginate(15)->withQueryString();

        $categories = Occupation::whereNotNull('category')->distinct()->pluck('category');
        $sectors    = Occupation::whereNotNull('sector')->distinct()->pluck('sector');

        return view('admin.occupations.index', compact('occupations', 'categories', 'sectors'));
    }

    public function create()
    {
        return view('admin.occupations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:occupations,name',
            'category'    => 'nullable|string|max:100',
            'sector'      => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        try {
            Occupation::create($data);
            Log::info('Occupation created', [
                'name' => $data['name'], 'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.occupations.index')
                ->with('success', 'Occupation added.');
        } catch (\Exception $e) {
            Log::error('Occupation creation failed', [
                'error' => $e->getMessage(), 'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to create occupation.')->withInput();
        }
    }

    public function edit(Occupation $occupation)
    {
        return view('admin.occupations.edit', compact('occupation'));
    }

    public function update(Request $request, Occupation $occupation)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:occupations,name,'.$occupation->id,
            'category'    => 'nullable|string|max:100',
            'sector'      => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        try {
            $occupation->update($data);
            Log::info('Occupation updated', [
                'occupation_id' => $occupation->id, 'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.occupations.index')
                ->with('success', 'Occupation updated.');
        } catch (\Exception $e) {
            Log::error('Occupation update failed', [
                'occupation_id' => $occupation->id, 'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update occupation.')->withInput();
        }
    }

    public function destroy(Occupation $occupation)
    {
        try {
            $occupation->delete();
            Log::info('Occupation deleted', [
                'occupation_id' => $occupation->id, 'deleted_by' => auth()->id(),
            ]);

            return redirect()->route('admin.occupations.index')
                ->with('success', 'Occupation deleted.');
        } catch (\Exception $e) {
            Log::error('Occupation deletion failed', [
                'occupation_id' => $occupation->id, 'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete occupation.');
        }
    }
}
