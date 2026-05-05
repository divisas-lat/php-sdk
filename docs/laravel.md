# Integración con Laravel

El SDK de Divisas.lat incluye soporte nativo (First-Class Citizen) para Laravel 10 y superior.

## Instalación Automática
Gracias al *Package Discovery* de Laravel, no necesitas registrar manualmente el `ServiceProvider`. Al requerir el paquete con Composer, Laravel lo detectará automáticamente.

## Publicar Configuración
Puedes publicar el archivo de configuración con Artisan:
```bash
php artisan vendor:publish --tag=divisas-config
```
Esto creará el archivo `config/divisas.php` donde podrás definir tu API Key y configuración de Caché (ver más abajo).

Asegúrate de agregar tu API Key a tu archivo `.env`:
```env
DIVISAS_API_KEY=tu_api_key_aqui
```

## Uso del Facade
Puedes usar el Facade `Divisas` desde cualquier lugar de tu aplicación Laravel sin necesidad de instanciar el cliente manualmente.

```php
use DivisasLat\Laravel\Facades\Divisas;
use DivisasLat\Enums\Country;

class ExchangeController extends Controller
{
    public function index()
    {
        // Uso del builder fluido a través del Facade
        $rate = Divisas::query()
            ->forCountry(Country::GUATEMALA)
            ->getToday();

        return view('exchange.index', ['rate' => $rate]);
    }
}
```

## Caché Transparente (PSR-16)
Las llamadas a APIs externas pueden consumir mucho tiempo y agotar tus cuotas.
El SDK de Divisas viene configurado para enlazarse automáticamente con el sistema de Caché de Laravel.

En `config/divisas.php`:
```php
    'cache_store' => env('DIVISAS_CACHE_STORE', 'redis'), // o 'file', 'memcached', etc
    'cache_ttl' => env('DIVISAS_CACHE_TTL', 3600), // En segundos
```

Con esto activado, el SDK almacenará automáticamente las respuestas HTTP de forma local por 1 hora, mejorando dramáticamente el rendimiento de tu aplicación sin necesidad de que tú escribas lógica de caché adicional.
