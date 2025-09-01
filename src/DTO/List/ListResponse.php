<?php

namespace Knackline\Listmonk\DTO\List;

use Knackline\Listmonk\DTO\BaseResponse;

class ListResponse extends BaseResponse
{
    public function getId(): int
    {
        return $this->data['id'];
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getType(): string
    {
        return $this->data['type'];
    }

    public function getDescription(): ?string
    {
        return $this->data['description'] ?? null;
    }

    public function getSubscriberCount(): int
    {
        return $this->data['subscriber_count'] ?? 0;
    }

    public function getTags(): array
    {
        if (empty($this->data['tags'])) {
            return [];
        }
        
        return is_string($this->data['tags']) ? 
            array_map('trim', explode(',', $this->data['tags'])) : 
            $this->data['tags'];
    }

    public function getOptin(): string
    {
        return $this->data['optin'] ?? 'single';
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
