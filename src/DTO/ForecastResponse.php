<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

class ForecastResponse
{
    public function __construct(
        public readonly string $country,
        public readonly string $currencyCode,
        public readonly string $model,
        public readonly int $basedOnDays,
        public readonly array $forecast
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['country'] ?? '',
            $data['currency_code'] ?? '',
            $data['model'] ?? '',
            (int) ($data['based_on_days'] ?? 0),
            $data['forecast'] ?? []
        );
    }
}
