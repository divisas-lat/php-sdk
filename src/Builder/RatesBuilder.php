<?php

declare(strict_types=1);

namespace DivisasLat\Builder;

use DivisasLat\Resources\Rates;
use DivisasLat\Enums\Country;
use DivisasLat\Enums\Currency;
use DivisasLat\DTO\TodayRatesResponse;
use DivisasLat\DTO\HistoricalRateResponse;
use DivisasLat\DTO\StatsResponse;
use DivisasLat\DTO\ConversionResponse;
use DivisasLat\DTO\ForecastResponse;
use DivisasLat\DTO\PercentileResponse;
use DivisasLat\Exceptions\DivisasException;

class RatesBuilder
{
    private Rates $ratesResource;
    private Country|string|null $country = null;
    private Currency|string|null $currency = null;

    public function __construct(Rates $ratesResource)
    {
        $this->ratesResource = $ratesResource;
    }

    public function forCountry(Country|string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function withCurrency(Currency|string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    private function requireCountry(): void
    {
        if ($this->country === null) {
            throw new DivisasException('Country is required. Call forCountry() first.');
        }
    }

    public function getToday(): TodayRatesResponse
    {
        $this->requireCountry();
        if ($this->currency) {
            return $this->ratesResource->getByCurrency($this->country, $this->currency);
        }
        return $this->ratesResource->getToday($this->country);
    }

    public function getHistory(string $from, string $to): HistoricalRateResponse
    {
        $this->requireCountry();
        return $this->ratesResource->getHistory($this->country, $from, $to, $this->currency ?? 'USD');
    }

    public function getStats(string $period = '30d'): StatsResponse
    {
        $this->requireCountry();
        return $this->ratesResource->getStats($this->country, $this->currency ?? 'USD', $period);
    }

    public function convert(Currency|string $to, float $amount, ?string $date = null): ConversionResponse
    {
        $this->requireCountry();
        return $this->ratesResource->convert($this->country, $this->currency ?? 'USD', $to, $amount, $date);
    }

    public function getForecast(int $days = 7): ForecastResponse
    {
        $this->requireCountry();
        return $this->ratesResource->getForecast($this->country, $days, $this->currency ?? 'USD');
    }

    public function getPercentile(string $period = '1y'): PercentileResponse
    {
        $this->requireCountry();
        return $this->ratesResource->getPercentile($this->country, $this->currency ?? 'USD', $period);
    }
}
