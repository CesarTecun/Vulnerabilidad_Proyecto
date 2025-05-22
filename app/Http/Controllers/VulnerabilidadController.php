<?php

namespace App\Http\Controllers;

use App\Models\Vulnerabilidad;
use Illuminate\Http\Request;
use App\Notifications\NuevaVulnerabilidadDetectada;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\AnalizadorVulnerabilidad;
use Carbon\Carbon;
use App\Models\PatronVulnerabilidad;



class VulnerabilidadController extends Controller
{


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
    
        $vulnerabilidad = Vulnerabilidad::create([
            'nombre' => $request->nombre,
            'componente_afectado' => $request->componente_afectado,
            'criticidad' => $request->criticidad,
            'estado' => $request->estado,
            'fecha_deteccion' => $request->fecha_deteccion,
            'cvss' => $request->cvss,
            'descripcion' => $request->descripcion,
        ]);
    
        // ✅ Enviar notificación al usuario actual
        auth()->user()->notify(new NuevaVulnerabilidadDetectada($vulnerabilidad->nombre));
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
        $vulnerabilidad = Vulnerabilidad::create([
            'nombre' => 'Vuln-' . Str::upper(Str::random(5)),
            'componente_afectado' => 'Componente X',
            'criticidad' => 'Alta',
            'estado' => 'Detectada',
            'fecha_deteccion' => now(),
            'cvss' => rand(7, 10),
            'descripcion' => 'Vulnerabilidad generada automáticamente.',
        ]);
        
        // ✅ Notificar al usuario autenticado
        auth()->user()->notify(new \App\Notifications\NuevaVulnerabilidadDetectada($vulnerabilidad->nombre));
        
        return redirect()->route('dashboard')->with('nueva_notificacion', true);

        
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
    
        $lineaDetectada = $vulnerabilidad->linea_detectada;
        $archivo = $vulnerabilidad->componente_afectado;
        $contexto = [];
    
        if ($lineaDetectada && \Storage::exists($archivo)) {
            $lineas = explode("\n", \Storage::get($archivo));
            $inicio = max(0, $lineaDetectada - 6);
            $fin = min(count($lineas), $lineaDetectada + 4);
    
            for ($i = $inicio; $i < $fin; $i++) {
                $contexto[] = [
                    'num' => $i + 1,
                    'contenido' => $lineas[$i] ?? '',
                    'resaltado' => ($i + 1) == $lineaDetectada,
                ];
            }
        }
    
        return view('vulnerabilidades.edit', compact('vulnerabilidad', 'contexto'));
    }
    
    public function agrupadasPorArchivo(Request $request)
    {
        $criterio = $request->get('criterio', 'componente_afectado');
    
        $agrupadas = Vulnerabilidad::all()->groupBy($criterio);
    
        return view('vulnerabilidades.agrupadas', compact('agrupadas'));
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


public function detectarDesdeArchivo(Request $request)
{
    $request->validate([
        'archivo' => 'required|file',
    ]);

    // 1. Guardar archivo subido
    $file = $request->file('archivo');
    $path = $file->storeAs('vulnerabilidades', $file->getClientOriginalName());

    // 2. Leer contenido y patrones
    $contenido = Storage::get($path);
    $lineas = explode("\n", $contenido);
    $patrones = PatronVulnerabilidad::all();
    $detectadas = [];

    // 3. Buscar coincidencias
    foreach ($patrones as $patron) {
        foreach ($lineas as $i => $linea) {
            if (@preg_match("/{$patron->regex}/", $linea, $match)) {
                $detectadas[] = [
                    'nombre' => $patron->nombre,
                    'detalle' => $match[0],
                    'criticidad' => $patron->criticidad,
                    'archivo' => $path,
                    'linea' => $i + 1,
                    'fragmento' => trim($linea),
                ];
                break; // solo una coincidencia por patrón
            }
        }
    }

    // 4. Registrar vulnerabilidades detectadas
    foreach ($detectadas as $vuln) {
        $registro = Vulnerabilidad::create([
            'nombre' => 'Vuln-' . strtoupper(Str::random(5)),
            'componente_afectado' => $vuln['archivo'],
            'criticidad' => $vuln['criticidad'],
            'estado' => 'Detectada',
            'fecha_deteccion' => now(),
            'cvss' => $this->asignarCvss($vuln['criticidad']),
            'descripcion' => $vuln['nombre'] . ' | ' . $vuln['detalle'],
            'linea_detectada' => $vuln['linea'] ?? null,
            'fragmento_detectado' => $vuln['fragmento'] ?? null,
            'tipo' => $vuln['nombre'],
        ]);

        auth()->user()->notify(new \App\Notifications\NuevaVulnerabilidadDetectada($registro->nombre));
    }

    return redirect()->route('vulnerabilidades.index')
        ->with('success', count($detectadas) . ' vulnerabilidades detectadas.');
}



private function asignarCvss($criticidad)
{
    return match (strtolower($criticidad)) {
        'alta' => rand(7, 10),
        'media' => rand(4, 6),
        'baja' => rand(1, 3),
        default => 5,
    };
}

    
}
