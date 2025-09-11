<?php

namespace Knackline\Listmonk;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;
use Knackline\Listmonk\DTO\Subscriber\SubscriberRequest as SubscriberRequestDTO;
use Knackline\Listmonk\DTO\Subscriber\SubscriberResponse as SubscriberResponseDTO;
use Knackline\Listmonk\DTO\List\ListRequest as ListRequestDTO;
use Knackline\Listmonk\DTO\List\ListResponse as ListResponseDTO;

class ListmonkClient
{
    /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $client;

    /**
     * The base URL for the API.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Create a new Listmonk client instance.
     *
     * @param  string  $baseUrl
     * @param  string  $username
     * @param  string  $password
     * @param  int  $timeout
     * @return void
     */
    public function __construct(string $baseUrl, string $username, string $password, int $timeout = 30)
    {
        $this->baseUrl = rtrim($baseUrl, '/');

        $this->client = Http::withHeaders([
            'Authorization' => 'token ' . $username . ':' . $password,
            'Content-Type' => 'application/json',
            'User-Agent' => 'knackline/laravel-listmonk',
        ])
            ->baseUrl($this->baseUrl . '/api')
            ->timeout($timeout)
            ->acceptJson();
    }

    protected function httpClient(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'token ' . config('listmonk.username') . ':' . config('listmonk.password'),
        ])->baseUrl(rtrim(config('listmonk.base_url'), '/'));
    }

    /*
    |--------------------------------------------------------------------------
    | Health & Config
    |--------------------------------------------------------------------------
    */

    /**
     * Check if the Listmonk server is healthy.
     *
     * @return bool
     */
    public function healthCheck(): bool
    {
        return $this->client->get('/health')->json('data', false) === true;
    }

    /**
     * Get server configuration.
     *
     * @return array
     */
    public function getServerConfig(): array
    {
        return $this->client->get('/config')->json('data', []);
    }

    /**
     * Get language pack.
     *
     * @param  string  $lang
     * @return array
     */
    public function getLanguagePack(string $lang): array
    {
        return $this->client->get("/lang/{$lang}")->json('data', []);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    /**
     * Get dashboard charts data.
     *
     * @return array
     */
    public function getDashboardCharts(): array
    {
        return $this->client->get('/dashboard/charts')->json('data', []);
    }

    /**
     * Get dashboard counts.
     *
     * @return array
     */
    public function getDashboardCounts(): array
    {
        return $this->client->get('/dashboard/counts')->json('data', []);
    }

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    /**
     * Get server settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->client->get('/settings')->json('data', []);
    }

    /**
     * Update server settings.
     *
     * @param  array  $settings
     * @return bool
     */
    public function updateSettings(array $settings): bool
    {
        return $this->client->put('/settings', $settings)->json('data', false) === true;
    }

    /**
     * Test SMTP settings.
     *
     * @param  array  $smtpConfig
     * @return bool
     */
    public function testSmtpSettings(array $smtpConfig): bool
    {
        return $this->client->post('/settings/smtp/test', $smtpConfig)
            ->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    /**
     * Reload the application.
     *
     * @return bool
     */
    public function reloadApp(): bool
    {
        return $this->client->post('/admin/reload')->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Logs
    |--------------------------------------------------------------------------
    */

    /**
     * Get application logs.
     *
     * @return array
     */
    public function getLogs(): array
    {
        return $this->client->get('/logs')->json('data', []);
    }

    /*
    |--------------------------------------------------------------------------
    | Subscribers
    |--------------------------------------------------------------------------
    */

    /**
     * Get subscribers with optional filters.
     *
     * @param  array  $filters
     * @return \Illuminate\Support\Collection<SubscriberResponseDTO>
     */
    public function getSubscribers(array $filters = [])
    {
        $response = $this->client->get('/subscribers', $filters);
        return SubscriberResponseDTO::collectionFromResponse($response);
    }

    /**
     * Get a subscriber by ID.
     *
     * @param  int  $id
     * @return SubscriberResponseDTO|null
     */
    public function getSubscriber(int $id): ?SubscriberResponseDTO
    {
        $response = $this->client->get("/subscribers/{$id}");

        if ($response->successful()) {
            return new SubscriberResponseDTO($response->json('data'));
        }

        return null;
    }

    /**
     * Create a new subscriber.
     *
     * @param  SubscriberRequestDTO  $subscriber
     * @return SubscriberResponseDTO|null
     */
    public function createSubscriber(SubscriberRequestDTO $subscriber): ?SubscriberResponseDTO
    {
        $response = $this->httpClient()
            ->post('/api/subscribers', $subscriber->toArray())
            ->throw();

        return SubscriberResponseDTO::fromResponse($response);
    }

    /**
     * Update a subscriber.
     *
     * @param  int  $id
     * @param  SubscriberRequestDTO  $subscriber
     * @return SubscriberResponseDTO|null
     */
    public function updateSubscriber(int $id, SubscriberRequestDTO $subscriber): ?SubscriberResponseDTO
    {
        $response = $this->httpClient()
            ->put("/api/subscribers/{$id}", $subscriber->toArray())
            ->throw();

        return SubscriberResponseDTO::fromResponse($response);
    }

    /**
     * Delete a subscriber.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteSubscriber(int $id): bool
    {
        return $this->client->delete("/subscribers/{$id}")
            ->json('data', false) === true;
    }

    /**
     * Delete multiple subscribers.
     *
     * @param  array  $ids
     * @return bool
     */
    public function deleteSubscribers(array $ids): bool
    {
        return $this->client->delete('/subscribers', ['id' => implode(',', $ids)])
            ->json('data', false) === true;
    }

    /**
     * Blocklist a subscriber.
     *
     * @param  int  $id
     * @return bool
     */
    public function blocklistSubscriber(int $id): bool
    {
        return $this->client->put("/subscribers/{$id}/blocklist")
            ->json('data', false) === true;
    }

    /**
     * Blocklist multiple subscribers.
     *
     * @param  array  $ids
     * @return bool
     */
    public function blocklistSubscribers(array $ids): bool
    {
        return $this->client->put('/subscribers/blocklist', [
            'id' => $ids,
            'action' => 'blocklist',
        ])->json('data', false) === true;
    }

    /**
     * Send opt-in confirmation to a subscriber.
     *
     * @param  int  $id
     * @return bool
     */
    public function sendOptin(int $id): bool
    {
        return $this->client->post("/subscribers/{$id}/optin")
            ->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Lists
    |--------------------------------------------------------------------------
    */

    /**
     * Get all lists.
     *
     * @param  array  $filters
     * @return \Illuminate\Support\Collection<ListResponseDTO>
     */
    public function getLists(array $filters = [])
    {
        $response = $this->client->get('/lists', $filters);
        return ListResponseDTO::collectionFromResponse($response);
    }

    /**
     * Get a list by ID.
     *
     * @param  int  $id
     * @return ListResponseDTO|null
     */
    public function getList(int $id): ?ListResponseDTO
    {
        $response = $this->client->get("/lists/{$id}");

        if ($response->successful()) {
            return new ListResponseDTO($response->json('data'));
        }

        return null;
    }

    /**
     * Create a new list.
     *
     * @param  ListRequestDTO  $list
     * @return ListResponseDTO|null
     */
    public function createList(ListRequestDTO $list): ?ListResponseDTO
    {
        $response = $this->client->post('/lists', $list->toArray());

        if ($response->successful()) {
            return new ListResponseDTO($response->json('data'));
        }

        return null;
    }

    /**
     * Update a list.
     *
     * @param  int  $id
     * @param  ListRequestDTO  $list
     * @return ListResponseDTO|null
     */
    public function updateList(int $id, ListRequestDTO $list): ?ListResponseDTO
    {
        $response = $this->client->put("/lists/{$id}", $list->toArray());

        if ($response->successful()) {
            return new ListResponseDTO($response->json('data'));
        }

        return null;
    }

    /**
     * Delete a list.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteList(int $id): bool
    {
        return $this->client->delete("/lists/{$id}")
            ->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Campaigns
    |--------------------------------------------------------------------------
    */

    /**
     * Get all campaigns.
     *
     * @param  array  $filters
     * @return array
     */
    public function getCampaigns(array $filters = []): array
    {
        return $this->client->get('/campaigns', $filters)->json('data', []);
    }

    /**
     * Get a campaign by ID.
     *
     * @param  int  $id
     * @return array|null
     */
    public function getCampaign(int $id): ?array
    {
        $response = $this->client->get("/campaigns/{$id}");

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    /**
     * Create a new campaign.
     *
     * @param  array  $data
     * @return array|null
     */
    public function createCampaign(array $data): ?array
    {
        $response = $this->client->post('/campaigns', $data);

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    /**
     * Update a campaign.
     *
     * @param  int  $id
     * @param  array  $data
     * @return array|null
     */
    public function updateCampaign(int $id, array $data): ?array
    {
        $response = $this->client->put("/campaigns/{$id}", $data);

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    /**
     * Delete a campaign.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteCampaign(int $id): bool
    {
        return $this->client->delete("/campaigns/{$id}")
            ->json('data', false) === true;
    }

    /**
     * Send a campaign.
     *
     * @param  int  $campaignId
     * @param  bool  $sendNow
     * @return bool
     */
    public function sendCampaign(int $campaignId, bool $sendNow = false): bool
    {
        return $this->client->put("/campaigns/{$campaignId}/status", [
            'status' => $sendNow ? 'scheduled' : 'draft',
            'send_later' => !$sendNow,
        ])->json('data', false) === true;
    }

    /**
     * Get campaign preview.
     *
     * @param  int  $id
     * @return string
     */
    public function getCampaignPreview(int $id): string
    {
        return $this->client->get("/campaigns/{$id}/preview")->body();
    }

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    */

    /**
     * Get all templates.
     *
     * @return array
     */
    public function getTemplates(): array
    {
        return $this->client->get('/templates')->json('data', []);
    }

    /**
     * Get a template by ID.
     *
     * @param  int  $id
     * @return array|null
     */
    public function getTemplate(int $id): ?array
    {
        $response = $this->client->get("/templates/{$id}");

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    /**
     * Create a new template.
     *
     * @param  array  $data
     * @return array|null
     */
    public function createTemplate(array $data): ?array
    {
        $response = $this->client->post('/templates', $data);

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    /**
     * Update a template.
     *
     * @param  int  $id
     * @param  array  $data
     * @return array|null
     */
    public function updateTemplate(int $id, array $data): ?array
    {
        $response = $this->client->put("/templates/{$id}", $data);

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    /**
     * Delete a template.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteTemplate(int $id): bool
    {
        return $this->client->delete("/templates/{$id}")
            ->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    */

    /**
     * Upload a media file.
     *
     * @param  string  $path
     * @param  string  $filename
     * @return array|null
     */
    public function uploadMedia(string $path, string $filename = null): ?array
    {
        $response = Http::attach(
            'file',
            file_get_contents($path),
            $filename ?? basename($path)
        )->post($this->baseUrl . '/api/media/upload');

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    /**
     * Get media.
     *
     * @param  array  $filters
     * @return array
     */
    public function getMedia(array $filters = []): array
    {
        return $this->client->get('/media', $filters)->json('data', []);
    }

    /*
    |--------------------------------------------------------------------------
    | Import/Export
    |--------------------------------------------------------------------------
    */

    /**
     * Import subscribers.
     *
     * @param  string  $filePath
     * @param  array  $params
     * @return array
     */
    public function importSubscribers(string $filePath, array $params = []): array
    {
        $response = Http::attach(
            'file',
            file_get_contents($filePath),
            basename($filePath)
        )->post($this->baseUrl . '/api/import/subscribers', [
                    'params' => json_encode($params),
                ]);

        if ($response->successful()) {
            return $response->json('data', []);
        }

        return [];
    }

    /**
     * Get import status.
     *
     * @return array
     */
    public function getImportStatus(): array
    {
        return $this->client->get('/import/subscribers')->json('data', []);
    }

    /**
     * Stop import.
     *
     * @return bool
     */
    public function stopImport(): bool
    {
        return $this->client->delete('/import/subscribers')
            ->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Bounces
    |--------------------------------------------------------------------------
    */

    /**
     * Get bounces.
     *
     * @param  array  $filters
     * @return array
     */
    public function getBounces(array $filters = []): array
    {
        return $this->client->get('/bounces', $filters)->json('data', []);
    }

    /**
     * Delete bounces.
     *
     * @param  array  $ids
     * @return bool
     */
    public function deleteBounces(array $ids = []): bool
    {
        $params = [];

        if (!empty($ids)) {
            $params['id'] = implode(',', $ids);
        } else {
            $params['all'] = true;
        }

        return $this->client->delete('/bounces', $params)
            ->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Transactional
    |--------------------------------------------------------------------------
    */

    /**
     * Send a transactional email.
     *
     * @param  string  $to
     * @param  string  $subject
     * @param  string  $body
     * @param  array  $data
     * @return bool
     */
    public function sendTransactionalEmail(string $to, string $subject, string $body, array $data = []): bool
    {
        $response = $this->client->post('/tx', array_merge([
            'subscriber_email' => $to,
            'template_id' => 0, // 0 for raw body
            'from_email' => $data['from_email'] ?? null,
            'from_name' => $data['from_name'] ?? null,
            'subject' => $subject,
            'body' => $body,
            'content_type' => $data['content_type'] ?? 'html',
            'messenger' => $data['messenger'] ?? 'email',
        ], $data));

        return $response->successful();
    }

    /*
    |--------------------------------------------------------------------------
    | Maintenance
    |--------------------------------------------------------------------------
    */

    /**
     * Get maintenance status.
     *
     * @return array
     */
    public function getMaintenanceStatus(): array
    {
        return $this->client->get('/maintenance')->json('data', []);
    }

    /**
     * Run maintenance.
     *
     * @param  array  $tasks
     * @return bool
     */
    public function runMaintenance(array $tasks): bool
    {
        return $this->client->post('/maintenance', [
            'tasks' => $tasks,
        ])->json('data', false) === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Make a raw API request.
     *
     * @param  string  $method
     * @param  string  $endpoint
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function request(string $method, string $endpoint, array $data = [])
    {
        return $this->client->$method(ltrim($endpoint, '/'), $data);
    }

    /**
     * Get the underlying HTTP client instance.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function getClient()
    {
        return $this->client;
    }
}
