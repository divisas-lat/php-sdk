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

    private function getCountry(): Country|string
    {
        if ($this->country === null) {
            throw new DivisasException('Country is required. Call forCountry() first.');
        }
        return $this->country;
    }

    public function getToday(): TodayRatesResponse
    {
        $country = $this->getCountry();
        if ($this->currency) {
            return $this->ratesResource->getByCurrency($country, $this->currency);
        }
        return $this->ratesResource->getToday($country);
    }

    public function getHistory(string $from, string $to): HistoricalRateResponse
    {
        return $this->ratesResource->getHistory($this->getCountry(), $from, $to, $this->currency ?? 'USD');
    }

    public function getStats(string $period = '30d'): StatsResponse
    {
        return $this->ratesResource->getStats($this->getCountry(), $this->currency ?? 'USD', $period);
    }

    public function convert(Currency|string $to, float $amount): ConversionResponse
    {
        return $this->ratesResource->convert($this->getCountry(), $this->currency ?? 'USD', $to, $amount);
    }

    public function getForecast(int $days = 7): ForecastResponse
    {
        return $this->ratesResource->getForecast($this->getCountry(), $days, $this->currency ?? 'USD');
    }

    public function getPercentile(string $period = '1y'): PercentileResponse
    {
        return $this->ratesResource->getPercentile($this->getCountry(), $this->currency ?? 'USD', $period);
    }
}
