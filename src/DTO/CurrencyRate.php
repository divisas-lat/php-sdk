<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

class CurrencyRate
{
    public function __construct(
        public readonly string $currencyCode,
        public readonly float $buy,
        public readonly float $sell,
        public readonly ?string $currencyName = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['currency_code'] ?? '',
            (float) ($data['buy'] ?? 0),
            (float) ($data['sell'] ?? 0),
            $data['currency_name'] ?? null
        );
    }

    public function getMidRate(): float
    {
        return ($this->buy + $this->sell) / 2;
    }

    public function getSpread(): float
    {
        return $this->sell - $this->buy;
    }

    public function formatBuy(string $prefix = '', int $decimals = 4): string
    {
        return $prefix . number_format($this->buy, $decimals);
    }

    public function formatSell(string $prefix = '', int $decimals = 4): string
    {
        return $prefix . number_format($this->sell, $decimals);
    }
}
