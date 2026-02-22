<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;


class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Specialty::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sortOrder = $request->get('sort', 'asc') === 'desc' ? 'desc' : 'asc';
        $specialties = $query->orderBy('name', $sortOrder)->get();

        return view('admin.specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.specialties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specialties',
        ]);

        Specialty::create([
            'name' => $request->name,
        ]);

        return redirect()->route('specialties.index')->with('success', 'Spécialité créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        return view('admin.specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialty $specialty)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name,' . $specialty->id,
        ]);

        $specialty->update([
            'name' => $request->name,
        ]);

        return redirect()->route('specialties.index')->with('success', 'Spécialité modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {
        // Constraint: Cannot delete specialty attached to doctors
        if ($specialty->doctorProfiles()->exists()) {
             return back()->with('error', 'Impossible de supprimer cette spécialité car elle est attribuée à des médecins.');
        }

        $specialty->delete();

        return redirect()->route('specialties.index')->with('success', 'Spécialité supprimée avec succès.');
    }
}
