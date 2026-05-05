<?php

declare(strict_types=1);

namespace DivisasLat\Resources;

use DivisasLat\Enums\Country;
use DivisasLat\Exceptions\DivisasException;
use DivisasLat\DTO\CountryResponse;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Handles all country and currency catalog endpoints.
 */
class Countries
{
    private ClientInterface $http;

    public function __construct(ClientInterface $http)
    {
        $this->http = $http;
    }

    /**
     * Get the list of all supported countries.
     *
     * @return CountryResponse[]
     * @throws DivisasException
     */
    public function list(): array
    {
        $response = $this->request('v1/countries');
        $countries = [];
        foreach ($response as $item) {
            $countries[] = CountryResponse::fromArray($item);
        }
        return $countries;
    }

    /**
     * Get the catalog of supported currencies for a specific country.
     *
     * @param Country|string $country ISO Country Code (e.g., 'GT')
     * @return array
     * @throws DivisasException
     */
    public function getCurrencies(Country|string $country): array
    {
        $code = $country instanceof Country ? $country->value : $country;
        return $this->request("v1/{$code}/currencies");
    }

    /**
     * Helper to execute GET requests.
     *
     * @param string $endpoint
     * @return array
     * @throws DivisasException
     */
    private function request(string $endpoint): array
    {
        try {
            $response = $this->http->request('GET', $endpoint);
            
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new DivisasException('Invalid JSON response from API.');
            }

            return $decoded;
        } catch (GuzzleException $e) {
            throw new DivisasException('Request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
