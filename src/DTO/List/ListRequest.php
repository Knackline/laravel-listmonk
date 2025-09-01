<?php

namespace Knackline\Listmonk\DTO\List;

use Knackline\Listmonk\DTO\BaseRequest;

class ListRequest extends BaseRequest
{
    public string $name;
    public string $type = 'public';
    public ?string $description = null;
    public ?string $tags = null;
    public ?string $optin = 'single';
    public ?array $tagsArray = null;

    /**
     * Create a new list request instance.
     *
     * @param  string  $name
     * @param  string  $type
     * @param  string|null  $description
     * @param  string|null  $tags
     * @param  string  $optin
     * @return void
     */
    public function __construct(
        string $name,
        string $type = 'public',
        ?string $description = null,
        ?string $tags = null,
        string $optin = 'single'
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->tags = $tags;
        $this->optin = $optin;
    }

    /**
     * Get the validation rules for the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'type' => 'required|in:public,private',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'optin' => 'required|in:single,double',
        ];
    }

    /**
     * Convert the request to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        
        if ($this->tagsArray !== null) {
            $data['tags'] = $this->tagsArray;
            unset($data['tagsArray']);
        }
        
        return $data;
    }
}
