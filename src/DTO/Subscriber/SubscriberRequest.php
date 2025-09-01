<?php

namespace Knackline\Listmonk\DTO\Subscriber;

use Knackline\Listmonk\DTO\BaseRequest;

class SubscriberRequest extends BaseRequest
{
    public string $email;
    public string $name;
    public string $status = 'enabled';
    public array $lists = [];
    public ?array $attribs = null;
    public ?string $preconfirmSubscriptions = null;

    /**
     * Create a new subscriber request instance.
     *
     * @param  string  $email
     * @param  string  $name
     * @param  array  $lists
     * @param  string  $status
     * @param  array|null  $attribs
     * @param  bool|null  $preconfirmSubscriptions
     * @return void
     */
    public function __construct(
        string $email,
        string $name = '',
        array $lists = [],
        string $status = 'enabled',
        ?array $attribs = null,
        ?bool $preconfirmSubscriptions = null
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->lists = $lists;
        $this->status = $status;
        $this->attribs = $attribs;
        
        if ($preconfirmSubscriptions !== null) {
            $this->preconfirmSubscriptions = $preconfirmSubscriptions ? 'true' : 'false';
        }
    }

    /**
     * Get the validation rules for the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'name' => 'required|string',
            'status' => 'required|in:enabled,disabled',
            'lists' => 'required|array',
            'lists.*' => 'integer',
            'attribs' => 'nullable|array',
            'preconfirm_subscriptions' => 'nullable|in:true,false',
        ];
    }
}
