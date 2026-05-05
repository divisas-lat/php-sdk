# Uso Básico

A partir de la versión 1.x, el SDK de PHP no devuelve *arrays asociativos*. En su lugar, devuelve **Data Transfer Objects (DTOs)**. Esto es el estándar moderno para 2026 y proporciona ventajas significativas:

1. **Autocompletado (DX)**: Tu editor de código (VSCode, PhpStorm) te mostrará las propiedades exactas disponibles.
2. **Tipado Estricto**: Prevención de errores en tiempo de ejecución.

## Cliente Básico
```php
use DivisasLat\Client;
use DivisasLat\Enums\Country;

// 1. Inicializar Cliente
$client = new Client('tu_api_key_aqui');

// 2. Obtener tipo de cambio de hoy
$response = $client->rates()->getToday(Country::GUATEMALA);

echo $response->baseCurrency; // "GTQ"
echo $response->rates[0]->formatBuy('Compra: Q'); // "Compra: Q 7.63"
```

## Fluent Query Builder
Para una experiencia de desarrollo de primer nivel, incluimos un constructor de consultas (Query Builder) fluido.

```php
use DivisasLat\Enums\Country;
use DivisasLat\Enums\Currency;

// Conversión fluida
$conversion = $client->query()
    ->forCountry(Country::GUATEMALA)
    ->withCurrency(Currency::USD)
    ->convert(Currency::GTQ, 150.50);

echo $conversion->formatResult(); // "1,148.34"

// Estadísticas fluidas
$stats = $client->query()
    ->forCountry(Country::GUATEMALA)
    ->getStats('30d');

echo $stats->stats['avg']; 
```

## Manejo de Excepciones
El SDK arroja instancias de `DivisasLat\Exceptions\DivisasException` en caso de error.

```php
try {
    $client->query()->forCountry('GT')->getToday();
} catch (\DivisasLat\Exceptions\AuthenticationException $e) {
    echo "API Key inválida";
} catch (\DivisasLat\Exceptions\RateLimitException $e) {
    echo "Límite de peticiones excedido";
} catch (\DivisasLat\Exceptions\DivisasException $e) {
    echo "Error general: " . $e->getMessage();
}
```
