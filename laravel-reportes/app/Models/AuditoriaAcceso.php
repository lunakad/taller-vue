<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaAcceso extends Model
{
    use HasFactory;

    protected $table = 'auditoria_accesos';

    protected $fillable = [
        'user_id',
        'sistema_id',
        'accion',
        'resultado',
        'ip_origen',
        'user_agent',
        'metadatos',
    ];

    protected $casts = [
        'metadatos' => 'array',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sistema(): BelongsTo
    {
        return $this->belongsTo(Sistema::class, 'sistema_id');
    }
}
