<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

class CountryResponse
{
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly string $baseCurrency,
        public readonly string $flag
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['code'] ?? '',
            $data['name'] ?? '',
            $data['base_currency'] ?? '',
            $data['flag'] ?? ''
        );
    }
}
