<?php

declare(strict_types=1);

namespace DivisasLat\Resources;

use DivisasLat\Enums\Country;
use DivisasLat\Enums\Currency;
use DivisasLat\Exceptions\DivisasException;
use DivisasLat\DTO\TodayRatesResponse;
use DivisasLat\DTO\HistoricalRateResponse;
use DivisasLat\DTO\StatsResponse;
use DivisasLat\DTO\ConversionResponse;
use DivisasLat\DTO\ForecastResponse;
use DivisasLat\DTO\PercentileResponse;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Handles all exchange rate related endpoints.
 */
class Rates
{
    private ClientInterface $http;

    public function __construct(ClientInterface $http)
    {
        $this->http = $http;
    }

    /**
     * Resolves the currency code from either an Enum or string.
     *
     * @param Currency|string $currency ISO Currency Code (e.g., 'USD' or Currency::USD)
     * @return string
     */
    private function getCurrencyCode(Currency|string $currency): string
    {
        return $currency instanceof Currency ? $currency->value : $currency;
    }

    /**
     * Resolves the country code from either an Enum or string.
     *
     * @param Country|string $country ISO Country Code (e.g., 'GT' or Country::GUATEMALA)
     * @return string
     */
    private function getCountryCode(Country|string $country): string
    {
        return $country instanceof Country ? $country->value : $country;
    }

    /**
     * Get the current day's exchange rates for the specified country.
     *
     * @param Country|string $country
     * @return TodayRatesResponse
     * @throws DivisasException
     */
    public function getToday(Country|string $country): TodayRatesResponse
    {
        $code = $this->getCountryCode($country);
        $response = $this->request("v1/{$code}/rates");
        return TodayRatesResponse::fromArray($response);
    }

    /**
     * Get the current day's exchange rate for a specific currency.
     *
     * @param Country|string $country
     * @param Currency|string $currencyId The currency code (e.g., 'USD')
     * @return TodayRatesResponse
     * @throws DivisasException
     */
    public function getByCurrency(Country|string $country, Currency|string $currencyId): TodayRatesResponse
    {
        $code = $this->getCountryCode($country);
        $currCode = $this->getCurrencyCode($currencyId);
        $response = $this->request("v1/{$code}/rates/{$currCode}");
        return TodayRatesResponse::fromArray($response);
    }

    /**
     * Get historical exchange rates within a date range (up to 366 days).
     *
     * @param Country|string $country
     * @param string $from Start date (YYYY-MM-DD)
     * @param string $to End date (YYYY-MM-DD)
     * @param Currency|string $currency Currency code (default USD)
     * @return HistoricalRateResponse
     * @throws DivisasException
     */
    public function getHistory(Country|string $country, string $from, string $to, Currency|string $currency = 'USD'): HistoricalRateResponse
    {
        $code = $this->getCountryCode($country);
        $currCode = $this->getCurrencyCode($currency);
        $response = $this->request("v1/{$code}/rates/history", [
            'from' => $from,
            'to' => $to,
            'currency' => $currCode,
        ]);
        return HistoricalRateResponse::fromArray($response);
    }

    /**
     * Get an advanced statistical summary of historical rates.
     *
     * @param Country|string $country
     * @param Currency|string $currency Currency code (default USD)
     * @param string $period Period (default 30d)
     * @return StatsResponse
     * @throws DivisasException
     */
    public function getStats(Country|string $country, Currency|string $currency = 'USD', string $period = '30d'): StatsResponse
    {
        $code = $this->getCountryCode($country);
        $currCode = $this->getCurrencyCode($currency);
        $response = $this->request("v1/{$code}/rates/stats", [
            'currency' => $currCode,
            'period' => $period,
        ]);
        return StatsResponse::fromArray($response);
    }

    /**
     * Convert an amount between two currencies using the country's local currency as pivot.
     *
     * @param Country|string $country
     * @param Currency|string $from Currency to convert from (e.g., 'USD')
     * @param Currency|string $to Currency to convert to (e.g., 'GTQ')
     * @param float $amount Amount to convert
     * @return ConversionResponse
     * @throws DivisasException
     */
    public function convert(Country|string $country, Currency|string $from, Currency|string $to, float $amount): ConversionResponse
    {
        $code = $this->getCountryCode($country);
        $fromCode = $this->getCurrencyCode($from);
        $toCode = $this->getCurrencyCode($to);
        $response = $this->request("v1/{$code}/rates/convert", [
            'from' => $fromCode,
            'to' => $toCode,
            'amount' => $amount,
        ]);
        return ConversionResponse::fromArray($response);
    }

    /**
     * Get a future projection of exchange rates using linear regression.
     *
     * @param Country|string $country
     * @param int $days Number of days to forecast (optional)
     * @param Currency|string $currency Currency code (default USD)
     * @return ForecastResponse
     * @throws DivisasException
     */
    public function getForecast(Country|string $country, int $days = 30, Currency|string $currency = 'USD'): ForecastResponse
    {
        $code = $this->getCountryCode($country);
        $currCode = $this->getCurrencyCode($currency);
        $response = $this->request("v1/{$code}/rates/forecast", [
            'days' => $days,
            'currency' => $currCode,
        ]);
        return ForecastResponse::fromArray($response);
    }

    /**
     * Get the historical percentile context of the current price.
     *
     * @param Country|string $country
     * @param Currency|string $currency Currency code (default USD)
     * @param string $period Period (default 1y)
     * @return PercentileResponse
     * @throws DivisasException
     */
    public function getPercentile(Country|string $country, Currency|string $currency = 'USD', string $period = '1y'): PercentileResponse
    {
        $code = $this->getCountryCode($country);
        $currCode = $this->getCurrencyCode($currency);
        $response = $this->request("v1/{$code}/rates/percentile", [
            'currency' => $currCode,
            'period' => $period,
        ]);
        return PercentileResponse::fromArray($response);
    }

    /**
     * Helper to execute GET requests.
     *
     * @param string $endpoint
     * @param array $query
     * @return array
     * @throws DivisasException
     */
    private function request(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->http->request('GET', $endpoint, [
                'query' => $query
            ]);
            
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new DivisasException('Invalid JSON response from API.');
            }

            return $decoded;
        } catch (GuzzleException $e) {
            throw new DivisasException('Request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
