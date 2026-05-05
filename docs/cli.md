# Herramienta CLI

El SDK de PHP incluye una potente herramienta de línea de comandos (CLI) que te permite interactuar con la API directamente desde tu terminal, sin necesidad de escribir código.

Esto es útil para depurar, revisar tasas rápidamente en servidores y verificar valores de conversión en tiempo real.

## Instalación / Ejecución
Si el SDK está instalado globalmente o en un proyecto local, puedes invocarlo desde la carpeta `vendor/bin` o ejecutándolo directamente si clonaste el repositorio.

```bash
# Asignar API KEY (una sola vez)
export DIVISAS_API_KEY=tu_api_key_aqui

# Ejecutar herramienta local
./vendor/bin/divisas
```

## Comandos Disponibles

### 1. Obtener tipo de cambio (`rate`)
Obtén el tipo de cambio del día para un país y una divisa base.
```bash
$ divisas rate GT
🌎 Country: GT | Base: GTQ
📈 Rate: Buy: 7.6302 / Sell: 7.6302
```

### 2. Conversión Rápida (`convert`)
Convierte una cantidad de una moneda a otra en un país en tiempo real.
```bash
$ divisas convert 100 USD to GTQ in GT
💸 Result: 100 USD = 763.02 GTQ
```

### 3. Resumen Estadístico (`stats`)
Obtiene información estadística descriptiva de la moneda de los últimos 30 días.
```bash
$ divisas stats GT
📊 Stats for GT (30 days)
Min: 7.6120
Max: 7.6950
Avg: 7.6384
```
