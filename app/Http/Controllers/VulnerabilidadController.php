<?php

namespace App\Http\Controllers;

use App\Models\Vulnerabilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class VulnerabilidadController extends Controller
{
    /**
     * Mostrar todas las vulnerabilidades.
     */
    public function index(Request $request)
    {
        $query = Vulnerabilidad::query();
    
        // Aplicar filtros si están presentes
        if ($request->filled('criticidad')) {
            $query->where('criticidad', $request->criticidad);
        }
    
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
    
        $vulnerabilidades = $query->latest()->paginate(10)->withQueryString();
    
        // Para los selects
        $criticidades = ['Alta', 'Media', 'Baja'];
        $estados = ['Detectada', 'En evaluación', 'Corregida', 'En cuarentena', 'Falso positivo'];
    
        return view('vulnerabilidades.index', compact('vulnerabilidades', 'criticidades', 'estados'));
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
            'estado' => 'required|in:Detectada,En evaluación,Corregida,En cuarentena,Falso positivo',
            'fecha_deteccion' => 'nullable|date',
            'cvss' => 'nullable|numeric|min:0|max:10',
        ]);
    
        Vulnerabilidad::create([
            'nombre' => $request->nombre,
            'componente_afectado' => $request->componente_afectado,
            'criticidad' => $request->criticidad,
            'estado' => $request->estado,
            'fecha_deteccion' => $request->fecha_deteccion,
            'cvss' => $request->cvss,
        ]);
    
        return redirect()->route('vulnerabilidades.index')->with('success', 'Vulnerabilidad registrada correctamente.');
    }
    
    public function dashboard()
    {
        $total = Vulnerabilidad::count();
        $altas = Vulnerabilidad::where('criticidad', 'Alta')->count();
        $cuarentena = Vulnerabilidad::where('estado', 'En cuarentena')->count();
        $corregidas = Vulnerabilidad::where('estado', 'Corregida')->count();
        $falsos_positivos = Vulnerabilidad::where('estado', 'Falso positivo')->count();
    
        $porEstado = Vulnerabilidad::selectRaw('estado, COUNT(*) as total')
                        ->groupBy('estado')
                        ->pluck('total', 'estado');
    
        $ultimas = Vulnerabilidad::latest()->take(5)->get(); // 👈 añade esta línea
    
        return view('vulnerabilidades.dashboard', compact(
            'total', 'altas', 'cuarentena', 'corregidas', 'falsos_positivos', 'porEstado', 'ultimas'
        ));
    }
    
    
    public function simular()
    {
        Vulnerabilidad::create([
            'nombre' => 'Vuln-' . Str::upper(Str::random(5)),
            'componente_afectado' => 'Componente X',
            'criticidad' => 'Alta',
            'estado' => 'Detectada',
            'fecha_deteccion' => now(),
            'cvss' => rand(7, 10),
            'descripcion' => 'Vulnerabilidad generada automáticamente.',
        ]);
    
        return redirect()->route('dashboard')->with('success', '✅ Vulnerabilidad simulada con éxito.');
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
        $vulnerabilidad = Vulnerabilidad::findOrFail($id);
        return view('vulnerabilidades.edit', compact('vulnerabilidad'));
    }
    

    /**
     * Actualizar una vulnerabilidad específica (pendiente de implementar).
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'componente_afectado' => 'required|string|max:255',
            'criticidad' => 'required|in:Alta,Media,Baja',
            'estado' => 'required|in:Detectada,En evaluación,Corregida,En cuarentena,Falso positivo',
            'fecha_deteccion' => 'nullable|date',
            'cvss' => 'nullable|numeric|min:0|max:10',
        ]);
    
        $vulnerabilidad = Vulnerabilidad::findOrFail($id);
        $vulnerabilidad->update($request->all());
    
        return redirect()->route('vulnerabilidades.index')->with('success', '✅ Vulnerabilidad actualizada.');
    }
    

    /**
     * Eliminar una vulnerabilidad específica (pendiente de implementar).
     */
    public function destroy(string $id)
    {
        $vulnerabilidad = Vulnerabilidad::findOrFail($id);
        $vulnerabilidad->delete();
    
        return redirect()->route('vulnerabilidades.index')->with('success', '🗑 Vulnerabilidad eliminada.');
    }


    

    
}
