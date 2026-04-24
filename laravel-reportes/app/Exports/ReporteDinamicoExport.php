<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteDinamicoExport implements FromArray, WithHeadings
{
    public function __construct(
        private readonly array $filas,
        private readonly array $encabezados
    ) {
    }

    public function array(): array
    {
        return $this->filas;
    }

    public function headings(): array
    {
        return $this->encabezados;
    }
}
