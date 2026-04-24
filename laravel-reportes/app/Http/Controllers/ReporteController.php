<?php

namespace App\Http\Controllers;

use App\Exports\ReporteDinamicoExport;
use App\Models\AuditoriaAcceso;
use App\Models\EjecucionReporte;
use App\Models\Reporte;
use App\Models\Sistema;
use App\Services\ConexionSistemaService;
use App\Services\ValidadorSqlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Throwable;

class ReporteController extends Controller
{
    public function __construct(
        private readonly ConexionSistemaService $conexionSistemaService,
        private readonly ValidadorSqlService $validadorSqlService,
    ) {
    }

    public function index(Request $request, int $sistema)
    {
        $reportes = Reporte::query()
            ->where('sistema_id', $sistema)
            ->where('activo', true)
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'nombre', 'descripcion']);

        return view('reportes.index', [
            'reportes' => $reportes,
            'sistemaId' => $sistema,
        ]);
    }

    public function exportar(Request $request, int $sistema, int $reporte)
    {
        $request->validate([
            'formato' => 'required|in:xlsx,ods,csv',
        ]);

        $usuario = $request->user();
        $formato = $request->string('formato')->toString();

        $reporteModel = Reporte::query()
            ->where('id', $reporte)
            ->where('sistema_id', $sistema)
            ->where('activo', true)
            ->firstOrFail();

        $sistemaModel = Sistema::query()->findOrFail($sistema);

        $ejecucion = EjecucionReporte::query()->create([
            'reporte_id' => $reporteModel->id,
            'sistema_id' => $sistemaModel->id,
            'user_id' => $usuario->id,
            'formato' => $formato,
            'estado' => 'iniciado',
            'ip_origen' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'token_hash' => $request->bearerToken()
                ? Hash::make($request->bearerToken())
                : null,
        ]);

        try {
            $this->validadorSqlService->validarSoloLectura($reporteModel->consulta_sql);

            $conexion = $this->conexionSistemaService->conectar($sistemaModel);
            $resultado = DB::connection($conexion)->select($reporteModel->consulta_sql);

            $filas = collect($resultado)
                ->map(fn ($fila) => (array) $fila)
                ->values();

            $encabezados = $filas->isNotEmpty()
                ? array_keys($filas->first())
                : ['sin_datos'];

            $filasExport = $filas->isNotEmpty()
                ? $filas->toArray()
                : [['Sin resultados']];

            $tipoExcel = match ($formato) {
                'xlsx' => Excel::XLSX,
                'ods' => Excel::ODS,
                default => Excel::CSV,
            };

            $ejecucion->update([
                'estado' => 'generado',
                'filas' => max(count($filasExport) - (isset($filasExport[0]['sin_datos']) ? 1 : 0), 0),
                'fecha_generacion' => now(),
            ]);

            AuditoriaAcceso::query()->create([
                'user_id' => $usuario->id,
                'sistema_id' => $sistemaModel->id,
                'accion' => 'descarga_reporte',
                'resultado' => 'ok',
                'ip_origen' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'metadatos' => [
                    'reporte_id' => $reporteModel->id,
                    'formato' => $formato,
                    'ejecucion_id' => $ejecucion->id,
                ],
            ]);

            $nombreArchivo = sprintf(
                '%s_%s_%s.%s',
                $reporteModel->codigo,
                now()->format('Ymd_His'),
                $usuario->id,
                $formato
            );

            return ExcelFacade::download(
                new ReporteDinamicoExport($filasExport, $encabezados),
                $nombreArchivo,
                $tipoExcel
            );
        } catch (Throwable $e) {
            $ejecucion->update([
                'estado' => 'fallido',
                'mensaje_error' => $e->getMessage(),
            ]);

            AuditoriaAcceso::query()->create([
                'user_id' => $usuario->id,
                'sistema_id' => $sistemaModel->id,
                'accion' => 'descarga_reporte',
                'resultado' => 'error',
                'ip_origen' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'metadatos' => [
                    'reporte_id' => $reporteModel->id,
                    'formato' => $formato,
                    'error' => $e->getMessage(),
                ],
            ]);

            return back()->withErrors(['reporte' => 'No fue posible generar el reporte: '.$e->getMessage()]);
        }
    }
}
