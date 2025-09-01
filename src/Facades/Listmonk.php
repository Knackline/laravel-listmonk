<?php

namespace Knackline\Listmonk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array createSubscriber(array $data)
 * @method static array getSubscribers(array $filters = [])
 * @method static array createList(array $data)
 * @method static array getLists()
 * @method static array createCampaign(array $data)
 * @method static array sendCampaign(int $campaignId)
 * @method static array getTemplates()
 * @method static array request(string $method, string $endpoint, array $data = [])
 */
class Listmonk extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'listmonk';
    }
}
