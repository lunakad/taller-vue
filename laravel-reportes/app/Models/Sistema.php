<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sistema extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sistemas';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'motor',
        'host',
        'puerto',
        'base_datos',
        'usuario_bd',
        'clave_bd',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'puerto' => 'integer',
    ];

    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class, 'sistema_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sistema_usuario', 'sistema_id', 'user_id')
            ->withPivot('activo')
            ->withTimestamps();
    }
}
