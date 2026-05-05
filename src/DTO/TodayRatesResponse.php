<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

use DateTimeImmutable;

class TodayRatesResponse
{
    /**
     * @param CurrencyRate[] $rates
     */
    public function __construct(
        public readonly string $country,
        public readonly string $baseCurrency,
        public readonly DateTimeImmutable $date,
        public readonly string $source,
        public readonly bool $cached,
        public readonly array $rates
    ) {}

    public static function fromArray(array $data): self
    {
        $rates = [];
        if (isset($data['rates']) && is_array($data['rates'])) {
            foreach ($data['rates'] as $rate) {
                $rates[] = CurrencyRate::fromArray($rate);
            }
        } elseif (isset($data['rate']) && is_array($data['rate'])) {
            // For single currency endpoint
            $rates[] = CurrencyRate::fromArray($data['rate']);
        }

        return new self(
            $data['country'] ?? '',
            $data['base_currency'] ?? '',
            new DateTimeImmutable($data['date'] ?? 'now'),
            $data['source'] ?? '',
            $data['cached'] ?? false,
            $rates
        );
    }

    public function getRateFor(string $currencyCode): ?CurrencyRate
    {
        foreach ($this->rates as $rate) {
            if (strtoupper($rate->currencyCode) === strtoupper($currencyCode)) {
                return $rate;
            }
        }
        return null;
    }
}
