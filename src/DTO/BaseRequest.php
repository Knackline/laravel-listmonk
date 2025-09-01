<?php

namespace Knackline\Listmonk\DTO;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JsonSerializable;

abstract class BaseRequest implements JsonSerializable
{
    /**
     * Get the validation rules for the request.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Validate the request data.
     *
     * @throws ValidationException
     */
    public function validate(): void
    {
        $validator = Validator::make($this->toArray(), $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Convert the request to an HTTP client request.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function toHttp()
    {
        return Http::withBody(json_encode($this->toArray()), 'application/json');
    }

    /**
     * Convert the request to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);
            
            if ($value !== null) {
                $data[$property->getName()] = $value instanceof JsonSerializable 
                    ? $value->jsonSerialize() 
                    : $value;
            }
        }

        return $data;
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
}
