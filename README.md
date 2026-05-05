# Divisas.lat PHP SDK

El SDK oficial para interactuar con la API de Divisas.lat utilizando PHP. 
Este SDK ha sido reescrito para brindar una **Experiencia de Desarrollo (DX) Premium** en aplicaciones modernas usando DTOs y una Interfaz Fluida (Fluent API).

## Requisitos
- PHP 8.1 o superior
- Composer

## Instalación

```bash
composer require divisas-lat/php-sdk
```

## Autenticación (API Key)
Para utilizar este SDK necesitas una **API Key**. El acceso sin autenticación a la API de Divisas.lat cuenta con límites de uso muy estrictos.

Puedes obtener tu API Key gratuita registrándote en el landing page oficial:
**[Obtener mi API Key en Divisas.lat](https://divisas.lat)**

## Características
- **Tipado Estricto (DTOs)**: Respuestas orientadas a objetos, no arrays, para máximo autocompletado en tu IDE.
- **Fluent Query Builder**: Una API elegante y expresiva para construir tus llamadas.
- **Caché Transparente**: Soporte para PSR-16 (y Laravel Cache) para ahorrar peticiones.
- **Soporte Laravel Nativo**: Facades y ServiceProvider incluidos (`Divisas::query()`).
- **Herramienta CLI**: Un binario de consola para realizar consultas sin código.

## Uso Rápido

```php
<?php

require 'vendor/autoload.php';

use DivisasLat\Client;
use DivisasLat\Enums\Country;

$client = new Client('tu_api_key_aqui');

try {
    // Modo Fluido: Obtener tasa de hoy en Guatemala
    $today = $client->query()->forCountry(Country::GUATEMALA)->getToday();
    echo $today->rates[0]->formatBuy('Q ');

    // Modo Fluido: Convertir Dólares a Quetzales
    $conversion = $client->query()
        ->forCountry(Country::GUATEMALA)
        ->withCurrency('USD')
        ->convert('GTQ', 150.50);

    echo $conversion->formatResult(); // 1,148.34

} catch (\DivisasLat\Exceptions\DivisasException $e) {
    echo "Error de la API: " . $e->getMessage();
}
```

## Documentación Completa

Hemos preparado documentación detallada para cada uno de los componentes:

- [Uso General (DTOs, Queries)](docs/usage.md)
- [Integración con Laravel (Facade, Caché)](docs/laravel.md)
- [Herramienta CLI (Línea de Comandos)](docs/cli.md)
