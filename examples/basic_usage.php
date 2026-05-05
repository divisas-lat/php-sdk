<?php

require __DIR__ . '/vendor/autoload.php';

use DivisasLat\Client;
use DivisasLat\Enums\Country;

// To test with an API key: 
$client = new Client('gtq_de2faf5bceca49ffb600a529625f8c63');

echo "====================================\n";
echo "1. Fetching available countries...\n";
try {
    $countries = $client->countries()->list();
    echo "Found " . count($countries) . " countries.\n";
    if (count($countries) > 0) {
        echo "First country: {$countries[0]->name} ({$countries[0]->code})\n";
    }
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

/*
echo "====================================\n";
echo "2. Fetching available currencies for Guatemala...\n";
try {
    $currencies = $client->countries()->getCurrencies(Country::GUATEMALA);
    echo "Found " . count($currencies) . " currencies for GT.\n";
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }
*/

echo "====================================\n";
echo "3. Fetching today's exchange rate for Guatemala...\n";
try {
    $gtRates = $client->rates()->getToday(Country::GUATEMALA);
    echo "Base Currency: {$gtRates->baseCurrency} -> USD Buy: {$gtRates->rates[0]->formatBuy('Q ')}\n";
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

echo "====================================\n";
echo "4. Fetching today's specific USD rate for Guatemala...\n";
try {
    $usdRateResponse = $client->rates()->getByCurrency(Country::GUATEMALA, \DivisasLat\Enums\Currency::USD);
    $usdRate = $usdRateResponse->getRateFor('USD');
    if ($usdRate) {
        echo "USD to GTQ Buy: {$usdRate->formatBuy('Q ')}\n";
    } else {
        echo "USD rate not found in response.\n";
    }
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

echo "====================================\n";
echo "5. Fetching 3 days of history for Guatemala using Fluent Builder...\n";
try {
    $history = $client->query()
        ->forCountry(Country::GUATEMALA)
        ->getHistory('2023-01-01', '2023-01-03');
    echo "Fetched " . count($history->history) . " historical records.\n";
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

echo "====================================\n";
echo "6. Fetching statistical summary for Guatemala...\n";
try {
    $stats = $client->rates()->getStats(Country::GUATEMALA);
    echo "Average Mid Rate (30d): {$stats->stats['avg']}\n";
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

echo "====================================\n";
echo "7. Converting 150.50 USD to GTQ using Fluent Builder...\n";
try {
    $conversion = $client->query()
        ->forCountry(Country::GUATEMALA)
        ->withCurrency(\DivisasLat\Enums\Currency::USD)
        ->convert(\DivisasLat\Enums\Currency::GTQ, 150.50);
    echo "150.50 USD = {$conversion->formatResult()} GTQ\n";
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

echo "====================================\n";
echo "8. Fetching linear regression forecast (next 5 days)...\n";
try {
    $forecast = $client->rates()->getForecast(Country::GUATEMALA, 5);
    echo "Forecast for tomorrow: {$forecast->forecast[0]['projected']}\n";
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

echo "====================================\n";
echo "9. Fetching historical percentile...\n";
try {
    $percentile = $client->rates()->getPercentile(Country::GUATEMALA);
    echo "Current Percentile: {$percentile->percentile}%\n";
} catch (\Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }

echo "====================================\n";
echo "Test execution finished.\n";

