## knackline/laravel-listmonk

Laravel client for the Listmonk API (subscribers, lists, campaigns, templates, transactional mail, and more).

### Setup

@verbatim
<code-snippet name="Publish Listmonk config" lang="bash">
php artisan vendor:publish --provider="Knackline\Listmonk\Providers\ListmonkServiceProvider" --tag=config
</code-snippet>
@endverbatim

Env: `LISTMONK_URL`, `LISTMONK_USERNAME`, `LISTMONK_PASSWORD`. Entry points: `Knackline\Listmonk\Facades\Listmonk`, helper `listmonk()`, or inject `Knackline\Listmonk\ListmonkClient`.

Create/update subscribers and lists with **DTOs**, not bare arrays:

@verbatim
<code-snippet name="Create subscriber with DTO" lang="php">
use Knackline\Listmonk\Facades\Listmonk;
use Knackline\Listmonk\DTO\Subscriber\SubscriberRequest;

$request = new SubscriberRequest('user@example.com', 'Jane Doe', [1], 'enabled');
$request->validate();
$subscriber = Listmonk::createSubscriber($request);
</code-snippet>
@endverbatim

For full API coverage, response helpers, pitfalls, and filing GitHub issues for missing features or bugs, activate the `laravel-listmonk` skill. When this package cannot satisfy a requested feature, bug, or support need, follow that skill’s issue-filing steps and open an issue on `Knackline/laravel-listmonk`.
