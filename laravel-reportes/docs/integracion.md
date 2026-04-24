# Integración en proyecto Laravel existente

## 1) Dependencias

```bash
composer require maatwebsite/excel laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## 2) Registro de middleware

En `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ...
    'acceso.sistema' => \App\Http\Middleware\VerificaAccesoSistema::class,
];
```

## 3) Ajuste del modelo User (tabla requerida por Laravel)

En `app/Models/User.php`:

```php
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function sistemas(): BelongsToMany
    {
        return $this->belongsToMany(Sistema::class, 'sistema_usuario', 'user_id', 'sistema_id')
            ->withPivot('activo')
            ->withTimestamps();
    }
}
```

## 4) Cifrado de credenciales de sistemas

Al crear/editar un sistema:

```php
use Illuminate\Support\Facades\Crypt;

$datos['clave_bd'] = Crypt::encryptString($request->input('clave_bd'));
Sistema::create($datos);
```

## 5) Tokens de acceso

Para API con Sanctum:

```php
$token = $user->createToken('reportes-app')->plainTextToken;
```

Enviar en header: `Authorization: Bearer <token>`

## 6) Auditoría

- `ejecuciones_reporte`: trazabilidad de generación de reportes.
- `auditoria_accesos`: eventos de acceso/errores y metadatos.

## 7) Endurecimiento recomendado

- Usuario DB de solo lectura por sistema.
- Limitar redes/IP de conexión entre app reportes y origen.
- Activar HTTPS y cookies seguras.
- Usar cola (`ShouldQueue`) para reportes pesados.
- Monitorear intentos fallidos desde `auditoria_accesos`.
