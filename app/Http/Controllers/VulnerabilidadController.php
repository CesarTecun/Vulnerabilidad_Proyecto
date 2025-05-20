<?php

namespace App\Http\Controllers;

use App\Models\Vulnerabilidad;
use Illuminate\Http\Request;

class VulnerabilidadController extends Controller
{
    /**
     * Mostrar todas las vulnerabilidades.
     */
    public function index()
    {
        $vulnerabilidades = Vulnerabilidad::all();
        return view('vulnerabilidades.index', compact('vulnerabilidades'));
    }

    /**
     * Mostrar el formulario para crear una nueva vulnerabilidad.
     */
    public function create()
    {
        return view('vulnerabilidades.create');
    }

    /**
     * Almacenar una nueva vulnerabilidad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'componente_afectado' => 'required|string|max:255',
            'criticidad' => 'required|in:Alta,Media,Baja',
            'descripcion' => 'nullable|string',
        ]);

        Vulnerabilidad::create([
            'nombre' => $request->nombre,
            'componente_afectado' => $request->componente_afectado,
            'criticidad' => $request->criticidad,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('vulnerabilidades.index')->with('success', 'Vulnerabilidad registrada correctamente.');
    }

    /**
     * Mostrar una vulnerabilidad específica (pendiente de implementar).
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Mostrar formulario para editar una vulnerabilidad (pendiente de implementar).
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Actualizar una vulnerabilidad específica (pendiente de implementar).
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Eliminar una vulnerabilidad específica (pendiente de implementar).
     */
    public function destroy(string $id)
    {
        //
    }
}
