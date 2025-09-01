<?php

namespace Knackline\Listmonk\DTO;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use JsonSerializable;

abstract class BaseResponse implements JsonSerializable
{
    /**
     * The raw response data.
     *
     * @var array
     */
    protected array $data;

    /**
     * Create a new response instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the raw response data.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Create a new response instance from an HTTP response.
     *
     * @param  \Illuminate\Http\Client\Response  $response
     * @return static
     */
    public static function fromResponse($response)
    {
        return new static($response->json('data', []));
    }

    /**
     * Create a collection of responses from an HTTP response.
     *
     * @param  \Illuminate\Http\Client\Response  $response
     * @param  string  $key
     * @return \Illuminate\Support\Collection
     */
    public static function collectionFromResponse($response, string $key = 'results')
    {
        $data = $response->json('data', []);
        
        return collect($data[$key] ?? [])->map(function ($item) {
            return new static($item);
        });
    }
}
