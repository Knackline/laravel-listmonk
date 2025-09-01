# Laravel Listmonk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/knackline/laravel-listmonk.svg?style=flat-square)](https://packagist.org/packages/knackline/laravel-listmonk)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/knackline/laravel-listmonk/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/knackline/laravel-listmonk/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/knackline/laravel-listmonk.svg?style=flat-square)](https://packagist.org/packages/knackline/laravel-listmonk)

A Laravel package to interact with the Listmonk API, providing a fluent interface to manage subscribers, lists, campaigns, and more.

## Features

- 🚀 Full API coverage for Listmonk
- 🔄 Fluent, object-oriented API
- 🛠 Built with Laravel best practices
- 📦 Easy installation and configuration
- 🔍 Comprehensive test suite
- 📝 Well-documented methods

## Requirements

- PHP 8.0 or higher
- Laravel 9.x or 10.x
- Listmonk 2.0 or higher

## Installation

You can install the package via Composer:

```bash
composer require knackline/laravel-listmonk
```

Publish the config file with:

```bash
php artisan vendor:publish --provider="Knackline\Listmonk\Providers\ListmonkServiceProvider" --tag="listmonk-config"
```

## Configuration

Update your `.env` file with your Listmonk credentials:

```env
LISTMONK_URL=https://your-listmonk-installation.com
LISTMONK_USERNAME=your-username
LISTMONK_PASSWORD=your-password
```

Or modify the `config/listmonk.php` file directly.

## Usage

### Facade

```php
use Knackline\Listmonk\Facades\Listmonk;

// Get all subscribers
$subscribers = Listmonk::getSubscribers();

// Create a new subscriber
$subscriber = Listmonk::createSubscriber([
    'email' => 'user@example.com',
    'name' => 'John Doe',
    'status' => 'enabled',
    'lists' => [1, 2], // List IDs to subscribe to
]);
```

### Dependency Injection

```php
use Knackline\Listmonk\ListmonkClient;

class YourController
{
    protected $listmonk;

    public function __construct(ListmonkClient $listmonk)
    {
        $this->listmonk = $listmonk;
    }

    public function index()
    {
        $subscribers = $this->listmonk->getSubscribers();
        // ...
    }
}
```

### Available Methods

#### Subscribers
- `getSubscribers(array $filters = [])` - Get all subscribers
- `getSubscriber(int $id)` - Get a subscriber by ID
- `createSubscriber(array $data)` - Create a new subscriber
- `updateSubscriber(int $id, array $data)` - Update a subscriber
- `deleteSubscriber(int $id)` - Delete a subscriber
- `blocklistSubscriber(int $id)` - Blocklist a subscriber

#### Lists
- `getLists(array $filters = [])` - Get all lists
- `getList(int $id)` - Get a list by ID
- `createList(array $data)` - Create a new list
- `updateList(int $id, array $data)` - Update a list
- `deleteList(int $id)` - Delete a list

#### Campaigns
- `getCampaigns(array $filters = [])` - Get all campaigns
- `getCampaign(int $id)` - Get a campaign by ID
- `createCampaign(array $data)` - Create a new campaign
- `updateCampaign(int $id, array $data)` - Update a campaign
- `deleteCampaign(int $id)` - Delete a campaign
- `sendCampaign(int $campaignId, bool $sendNow = false)` - Send a campaign

#### Templates
- `getTemplates()` - Get all templates
- `getTemplate(int $id)` - Get a template by ID
- `createTemplate(array $data)` - Create a new template
- `updateTemplate(int $id, array $data)` - Update a template
- `deleteTemplate(int $id)` - Delete a template

#### Transactional Emails
- `sendTransactionalEmail(string $to, string $subject, string $body, array $data = [])` - Send a transactional email

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rajkumar Samra](https://github.com/rjsamra)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
