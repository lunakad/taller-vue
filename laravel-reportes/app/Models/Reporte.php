<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reporte extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reportes';

    protected $fillable = [
        'sistema_id',
        'codigo',
        'nombre',
        'descripcion',
        'consulta_sql',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function sistema(): BelongsTo
    {
        return $this->belongsTo(Sistema::class, 'sistema_id');
    }

    public function ejecuciones(): HasMany
    {
        return $this->hasMany(EjecucionReporte::class, 'reporte_id');
    }
}
