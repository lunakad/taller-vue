<?php

namespace App\Http\Middleware;

use App\Models\AuditoriaAcceso;
use App\Models\Sistema;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificaAccesoSistema
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $sistemaId = (int) $request->route('sistema');

        $sistema = Sistema::query()->where('id', $sistemaId)->where('activo', true)->first();

        if (! $user || ! $sistema) {
            $this->auditar($request, $user?->id, $sistemaId, 'acceso_sistema', 'denegado');
            abort(403, 'Sistema inválido.');
        }

        $tieneAcceso = $user->sistemas()
            ->where('sistemas.id', $sistemaId)
            ->wherePivot('activo', true)
            ->exists();

        if (! $tieneAcceso) {
            $this->auditar($request, $user->id, $sistemaId, 'acceso_sistema', 'denegado');
            abort(403, 'No tiene permisos para este sistema.');
        }

        $this->auditar($request, $user->id, $sistemaId, 'acceso_sistema', 'ok');
        return $next($request);
    }

    private function auditar(Request $request, ?int $userId, ?int $sistemaId, string $accion, string $resultado): void
    {
        AuditoriaAcceso::query()->create([
            'user_id' => $userId,
            'sistema_id' => $sistemaId,
            'accion' => $accion,
            'resultado' => $resultado,
            'ip_origen' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'metadatos' => [
                'ruta' => $request->path(),
                'metodo' => $request->method(),
            ],
        ]);
    }
}
