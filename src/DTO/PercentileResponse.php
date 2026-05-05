<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

class PercentileResponse
{
    public function __construct(
        public readonly string $country,
        public readonly string $currencyCode,
        public readonly string $currencyName,
        public readonly array $today,
        public readonly int $percentile,
        public readonly string $period,
        public readonly string $signal,
        public readonly array $range
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['country'] ?? '',
            $data['currency_code'] ?? '',
            $data['currency_name'] ?? '',
            $data['today'] ?? [],
            (int) ($data['percentile'] ?? 0),
            $data['period'] ?? '',
            $data['signal'] ?? '',
            $data['range'] ?? []
        );
    }
}
