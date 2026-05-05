<?php

declare(strict_types=1);

namespace DivisasLat\DTO;

class ConversionResponse
{
    public function __construct(
        public readonly array $from,
        public readonly array $to,
        public readonly float $amount,
        public readonly float $result,
        public readonly float $effectiveRate,
        public readonly string $via,
        public readonly string $date,
        public readonly string $note
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['from'] ?? [],
            $data['to'] ?? [],
            (float) ($data['amount'] ?? 0),
            (float) ($data['result'] ?? 0),
            (float) ($data['effective_rate'] ?? 0),
            $data['via'] ?? '',
            $data['date'] ?? '',
            $data['note'] ?? ''
        );
    }

    public function formatResult(int $decimals = 2): string
    {
        return number_format($this->result, $decimals);
    }
}
