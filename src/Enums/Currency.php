<?php

declare(strict_types=1);

namespace DivisasLat\Enums;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GTQ = 'GTQ';
    case MXN = 'MXN';
    case CRC = 'CRC';
    case CAD = 'CAD';
    case AUD = 'AUD';
    case NOK = 'NOK';
    case GBP = 'GBP';
    case JPY = 'JPY';
    case CHF = 'CHF';
}
