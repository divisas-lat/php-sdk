<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

class StatsResponse
{
    public function __construct(
        public readonly string $country,
        public readonly string $currencyCode,
        public readonly string $currencyName,
        public readonly string $period,
        public readonly array $current,
        public readonly array $stats,
        public readonly int $dataPoints
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['country'] ?? '',
            $data['currency_code'] ?? '',
            $data['currency_name'] ?? '',
            $data['period'] ?? '',
            $data['current'] ?? [],
            $data['stats'] ?? [],
            (int) ($data['data_points'] ?? 0)
        );
    }
}
