<?php

namespace App\Http\Controllers;

use App\Models\PatronVulnerabilidad;
use Illuminate\Http\Request;

class PatronVulnerabilidadController extends Controller
{
    public function index()
    {
        $patrones = PatronVulnerabilidad::all();
        return view('patrones.index', compact('patrones'));
    }

    public function create()
    {
        return view('patrones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'regex' => 'required|string',
            'criticidad' => 'required|in:Alta,Media,Baja',
        ]);

        PatronVulnerabilidad::create($request->all());

        return redirect()->route('patrones.index')->with('success', 'Patrón creado con éxito.');
    }

    public function edit(PatronVulnerabilidad $patron)
    {
        return view('patrones.edit', compact('patron'));
    }

    public function update(Request $request, PatronVulnerabilidad $patron)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'regex' => 'required|string',
            'criticidad' => 'required|in:Alta,Media,Baja',
        ]);

        $patron->update($request->all());

        return redirect()->route('patrones.index')->with('success', 'Patrón actualizado.');
    }

    public function destroy(PatronVulnerabilidad $patron)
    {
        $patron->delete();

        return redirect()->route('patrones.index')->with('success', 'Patrón eliminado.');
    }
}
