# Base MVC Laravel para Reportes Gerenciales

Este módulo incluye código base para:

- Autenticación Laravel (web) y token API (Sanctum).
- Relación `sistema`-`usuario` para control de acceso.
- Reportes asociados a cada sistema.
- Conexión dinámica por motor (`mysql`/`pgsql`) según sistema elegido.
- Exportación de reportes en `xlsx`, `ods`, `csv`.
- Auditoría de accesos y de ejecuciones de reportes.

## Estructura

- `app/Models`: modelos de dominio de reportes.
- `app/Services`: conexión dinámica y validación SQL.
- `app/Http/Middleware`: autorización por sistema.
- `app/Http/Controllers/ReporteController.php`: listado/exportación.
- `database/migrations`: tablas en español.
- `resources/views/reportes/index.blade.php`: vista de lista y descargas.
- `docs/integracion.md`: pasos para integrar en proyecto Laravel real.
