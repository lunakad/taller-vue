<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EjecucionReporte extends Model
{
    use HasFactory;

    protected $table = 'ejecuciones_reporte';

    protected $fillable = [
        'reporte_id',
        'sistema_id',
        'user_id',
        'formato',
        'estado',
        'filas',
        'ip_origen',
        'user_agent',
        'token_hash',
        'mensaje_error',
        'fecha_generacion',
    ];

    protected $casts = [
        'fecha_generacion' => 'datetime',
        'filas' => 'integer',
    ];

    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }

    public function sistema(): BelongsTo
    {
        return $this->belongsTo(Sistema::class, 'sistema_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
