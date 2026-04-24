<?php

namespace App\Services;

use InvalidArgumentException;

class ValidadorSqlService
{
    /**
     * Permite únicamente SELECT para reducir riesgo de daño sobre motores origen.
     */
    public function validarSoloLectura(string $sql): void
    {
        $limpio = trim(mb_strtolower($sql));

        if (! str_starts_with($limpio, 'select')) {
            throw new InvalidArgumentException('La consulta del reporte debe iniciar con SELECT.');
        }

        $bloqueadas = [
            ' insert ',
            ' update ',
            ' delete ',
            ' drop ',
            ' alter ',
            ' truncate ',
            ' create ',
            ' grant ',
            ' revoke ',
            ' execute ',
            ' call ',
        ];

        $sqlPadding = ' '.$limpio.' ';
        foreach ($bloqueadas as $palabra) {
            if (str_contains($sqlPadding, $palabra)) {
                throw new InvalidArgumentException('La consulta contiene una operación no permitida: '.trim($palabra));
            }
        }
    }
}
