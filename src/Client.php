<?php

declare(strict_types=1);

namespace DivisasLat;

use DivisasLat\Exceptions\AuthenticationException;
use DivisasLat\Exceptions\RateLimitException;
use DivisasLat\Exceptions\DivisasException;
use DivisasLat\Resources\Rates;
use DivisasLat\Resources\Countries;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const BASE_URI = 'https://api.divisas.lat/';
    
    private GuzzleClient $http;
    private Rates $rates;
    private Countries $countries;

    public function __construct(?string $apiKey = null, array $options = [])
    {
        $stack = HandlerStack::create();
        
        // Middleware to handle API errors
        $stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
            $statusCode = $response->getStatusCode();
            if ($statusCode === 401) {
                throw new AuthenticationException('Invalid or missing API Key.');
            }
            if ($statusCode === 429) {
                throw new RateLimitException('Rate limit exceeded.');
            }
            if ($statusCode >= 400) {
                throw new DivisasException('API Error: ' . $response->getReasonPhrase(), $statusCode);
            }
            return $response;
        }));

        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => 'divisas-php-sdk/1.0.0',
        ];

        if ($apiKey !== null) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        if (isset($options['cache']) && $options['cache'] instanceof \Psr\SimpleCache\CacheInterface) {
            $ttl = $options['cache_ttl'] ?? 3600;
            $stack->push(new \DivisasLat\Middleware\CacheMiddleware($options['cache'], (int)$ttl), 'cache');
        }

        $defaultOptions = [
            'base_uri' => self::BASE_URI,
            'headers' => $headers,
            'handler' => $stack,
            'timeout' => 10.0,
        ];

        $this->http = new GuzzleClient(array_merge($defaultOptions, $options));
        $this->rates = new Rates($this->http);
        $this->countries = new Countries($this->http);
    }

    public function rates(): Rates
    {
        return $this->rates;
    }

    public function query(): \DivisasLat\Builder\RatesBuilder
    {
        return new \DivisasLat\Builder\RatesBuilder($this->rates);
    }

    public function countries(): Countries
    {
        return $this->countries;
    }
}
