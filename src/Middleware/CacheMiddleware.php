<?php

declare(strict_types=1);

namespace DivisasLat\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;

class CacheMiddleware
{
    private CacheInterface $cache;
    private int $ttl;

    public function __construct(CacheInterface $cache, int $ttl = 3600)
    {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if ($request->getMethod() !== 'GET') {
                return $handler($request, $options);
            }

            $cacheKey = $this->getCacheKey($request);

            if ($this->cache->has($cacheKey)) {
                $cachedBody = $this->cache->get($cacheKey);
                if (is_string($cachedBody)) {
                    $response = new Response(200, ['Content-Type' => 'application/json'], $cachedBody);
                    return Create::promiseFor($response);
                }
            }

            /** @var PromiseInterface $promise */
            $promise = $handler($request, $options);

            return $promise->then(
                function (ResponseInterface $response) use ($cacheKey) {
                    if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                        $body = (string) $response->getBody();
                        $this->cache->set($cacheKey, $body, $this->ttl);
                        $response->getBody()->rewind();
                    }
                    return $response;
                }
            );
        };
    }

    private function getCacheKey(RequestInterface $request): string
    {
        return 'divisas_lat_' . md5($request->getMethod() . ' ' . $request->getUri());
    }
}
