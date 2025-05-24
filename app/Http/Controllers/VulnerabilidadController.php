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
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Illuminate\Support\Facades\File; 



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
    
       $patron = \App\Models\PatronVulnerabilidad::first(); // puedes usar cualquier lógica aquí
        return view('vulnerabilidades.index', compact('vulnerabilidades', 'criticidades', 'estados', 'patron'));

    }
    

    /**
     * Mostrar el formulario para crear una nueva vulnerabilidad.
     */
    public function create(Request $request)
    {
        $patron_id = $request->query('patron'); // o $request->patron;
        $patron = null;

        if ($patron_id) {
            $patron = \App\Models\PatronVulnerabilidad::find($patron_id);
        }

        return view('vulnerabilidades.create', compact('patron'));
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

        auth()->user()->notify(new NuevaVulnerabilidadDetectada(
            $vulnerabilidad->nombre,
            $vulnerabilidad->id,
            strtolower($vulnerabilidad->criticidad)
        ));

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

        auth()->user()->notify(new NuevaVulnerabilidadDetectada(
            $vulnerabilidad->nombre,
            $vulnerabilidad->id,
            'alta'
        ));

        return redirect()->route('dashboard')->with('nueva_notificacion', true);
    }

    


    /**
     * Mostrar una vulnerabilidad específica (pendiente de implementar).
     */
    public function show($id)
    {
        $vulnerabilidad = Vulnerabilidad::findOrFail($id);

        return view('vulnerabilidades.show', compact('vulnerabilidad'));
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
    $request->validate(['archivo' => 'required|file']);

    $file = $request->file('archivo');
    $originalName = $file->getClientOriginalName();
    $extension = $file->getClientOriginalExtension();
    logger('📁 Archivo recibido: ' . $originalName);
    logger('📦 Extensión detectada: ' . $extension);

    $archivosContenido = $extension === 'zip'
        ? $this->procesarZip($file)
        : [$originalName => $this->procesarArchivoSimple($file)];

    if (empty($archivosContenido)) {
        return back()->withErrors(['archivo' => '⚠️ Archivo vacío o sin contenido útil']);
    }

    [$descripcion, $fragmentos, $lineasDetectadas] = $this->analizarContenido($archivosContenido);

    if (empty($lineasDetectadas)) {
        return redirect()->route('vulnerabilidades.index')->with('success', '✅ No se detectaron vulnerabilidades.');
    }

    $registro = Vulnerabilidad::create([
        'nombre' => 'VULN-' . strtoupper(Str::random(5)),
        'componente_afectado' => $originalName,
        'criticidad' => 'Alta',
        'estado' => 'Detectada',
        'fecha_deteccion' => now(),
        'cvss' => 9,
        'descripcion' => trim($descripcion),
        'linea_detectada' => $lineasDetectadas[0],
        'fragmento_detectado' => implode("\n", $fragmentos),
        'tipo' => $extension === 'zip' ? 'Carpeta' : 'Archivo',
    ]);

    auth()->user()->notify(new NuevaVulnerabilidadDetectada(
        "{$registro->nombre} (" . count($lineasDetectadas) . " hallazgos)",
        $registro->id,
        'alta'
    ));

    return redirect()->route('vulnerabilidades.index')
        ->with('success', count($lineasDetectadas) . ' vulnerabilidades detectadas.');
}

private function procesarZip($file)
{
    $filename = 'zip_' . time() . '.zip';
    $storedPath = $file->storeAs('vulnerabilidades', $filename);
    $fullPath = Storage::disk('local')->path($storedPath);
    $extractPath = storage_path('app/temp_extract_' . time());

    if (!file_exists($fullPath)) {
        logger('❌ ZIP no encontrado: ' . $fullPath);
        return [];
    }

    $zip = new \ZipArchive;
    if ($zip->open($fullPath) !== TRUE) {
        logger('❌ Fallo al abrir ZIP: ' . $fullPath);
        return [];
    }

    $zip->extractTo($extractPath);
    $zip->close();
    logger('✅ ZIP extraído en: ' . $extractPath);

    $archivos = collect(File::allFiles($extractPath))
        ->filter(fn($f) => preg_match('/\.(php|js|html|py|java|txt)$/', $f->getFilename()));

    logger('📄 Archivos analizables: ' . $archivos->count());

    $mapaContenido = [];

    foreach ($archivos as $archivo) {
        $relative = $archivo->getRelativePathname();
        logger("📘 Leyendo: {$relative}");
        $mapaContenido[$relative] = File::get($archivo->getPathname());
    }

    File::deleteDirectory($extractPath);
    Storage::delete($storedPath);

    return $mapaContenido;
}

private function procesarArchivoSimple($file)
{
    $path = $file->storeAs('vulnerabilidades', $file->getClientOriginalName());
    logger('📄 Archivo simple leído');
    return Storage::get($path);
}

private function analizarContenido($archivosContenido)
{
    $descripcion = '';
    $fragmentos = [];
    $lineasDetectadas = [];

    $patrones = PatronVulnerabilidad::all();
    logger('🧬 Patrones: ' . $patrones->pluck('regex')->implode(', '));

    foreach ($archivosContenido as $archivo => $contenido) {
        $lineas = explode("\n", $contenido);
        foreach ($patrones as $patron) {
            foreach ($lineas as $i => $linea) {
                if (@preg_match("/{$patron->regex}/", $linea, $match)) {
                    $descripcion .= "🔸 {$patron->nombre} (Archivo: {$archivo}, Línea " . ($i + 1) . "): {$match[0]}\n";
                    $fragmentos[] = "{$archivo} [Línea " . ($i + 1) . "]: " . trim($linea);
                    $lineasDetectadas[] = $i + 1;
                    logger("🎯 Coincidencia en {$archivo}: patrón {$patron->regex} en línea " . ($i + 1));
                }
            }
        }
    }

    return [$descripcion, $fragmentos, $lineasDetectadas];
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

    public function exportarPdf($id)
    {
        $vulnerabilidad = Vulnerabilidad::findOrFail($id);

        $pdf = Pdf::loadView('vulnerabilidades.pdf', compact('vulnerabilidad'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('vulnerabilidad_' . $id . '.pdf');
    }
    
}
