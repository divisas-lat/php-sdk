<?php

declare(strict_types=1);

namespace DivisasLat\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \DivisasLat\Resources\Rates rates()
 * @method static \DivisasLat\Resources\Countries countries()
 * @method static \DivisasLat\Builder\RatesBuilder query()
 *
 * @see \DivisasLat\Client
 */
class Divisas extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'divisas';
    }
}
