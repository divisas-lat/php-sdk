<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

class HistoricalRateResponse
{
    /**
     * @param CurrencyRate[] $history
     */
    public function __construct(
        public readonly string $country,
        public readonly string $currencyCode,
        public readonly string $source,
        public readonly int $total,
        public readonly array $history
    ) {}

    public static function fromArray(array $data): self
    {
        $history = [];
        if (isset($data['history']) && is_array($data['history'])) {
            foreach ($data['history'] as $record) {
                // The API returns date, buy, sell inside history items
                // Let's adapt it to CurrencyRate
                $record['currency_code'] = $data['currency_code'] ?? '';
                $history[$record['date']] = CurrencyRate::fromArray($record);
            }
        }

        return new self(
            $data['country'] ?? '',
            $data['currency_code'] ?? '',
            $data['source'] ?? '',
            $data['total'] ?? 0,
            $history
        );
    }

    public function getRateOn(string $date): ?CurrencyRate
    {
        return $this->history[$date] ?? null;
    }
}
