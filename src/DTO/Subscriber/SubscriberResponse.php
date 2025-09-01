<?php

namespace Knackline\Listmonk\DTO\Subscriber;

use Knackline\Listmonk\DTO\BaseResponse;

class SubscriberResponse extends BaseResponse
{
    public function getId(): int
    {
        return $this->data['id'];
    }

    public function getUuid(): string
    {
        return $this->data['uuid'];
    }

    public function getEmail(): string
    {
        return $this->data['email'];
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getStatus(): string
    {
        return $this->data['status'];
    }

    public function getLists(): array
    {
        return $this->data['lists'] ?? [];
    }

    public function getAttributes(): array
    {
        return $this->data['attribs'] ?? [];
    }

    public function getCreatedAt(): string
    {
        return $this->data['created_at'];
    }

    public function getUpdatedAt(): string
    {
        return $this->data['updated_at'];
    }
}
