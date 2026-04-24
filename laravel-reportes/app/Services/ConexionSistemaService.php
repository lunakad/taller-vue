<?php

namespace App\Services;

use App\Models\Sistema;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ConexionSistemaService
{
    public function conectar(Sistema $sistema): string
    {
        $nombreConexion = 'sistema_'.$sistema->id;

        config([
            "database.connections.$nombreConexion" => [
                'driver' => $sistema->motor,
                'host' => $sistema->host,
                'port' => $sistema->puerto,
                'database' => $sistema->base_datos,
                'username' => $sistema->usuario_bd,
                'password' => Crypt::decryptString($sistema->clave_bd),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'schema' => $sistema->motor === 'pgsql' ? 'public' : null,
                'sslmode' => $sistema->motor === 'pgsql' ? 'prefer' : null,
                'strict' => true,
            ],
        ]);

        DB::purge($nombreConexion);
        DB::reconnect($nombreConexion);

        return $nombreConexion;
    }
}
