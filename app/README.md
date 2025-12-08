# Notification Module

## Overview

This module follows Clean Architecture principles with Eloquent Models.

## Structure

```
app/Modules/Notification/
├── Domain/
│   ├── Models/              # Eloquent Models with business logic
│   ├── Repositories/        # Repository interfaces
│   └── Services/            # Domain services (pure business logic)
│
├── Application/
│   ├── Actions/             # Actions / Use cases
│   └── DTO/                 # Data Transfer Objects
│
├── Infrastructure/
│   ├── Repositories/        # Repository implementations
│   ├── Services/            # Infrastructure services (external integrations)
│   └── Providers/           # Service providers
│
└── Presentation/
    ├── Http/
    │   ├── Controllers/     # HTTP controllers
    │   ├── Requests/        # Form request validation
    │   └── Resources/       # API resources
    └── Routes/              # Route definitions
```

## Usage

### Creating an Action

```php
// app/Modules/Notification/Application/Actions/CreateNotificationAction.php
namespace App\Modules\Notification\Application\Actions;

use App\Modules\Notification\Application\DTO\CreateNotificationDTO;
use App\Modules\Notification\Domain\Models\Notification;
use App\Modules\Notification\Domain\Repositories\NotificationRepositoryInterface;

class CreateNotificationAction
{
    public function __construct(
        private NotificationRepositoryInterface $repository
    ) {}

    public function execute(CreateNotificationDTO $dto): Notification
    {
        $model = new Notification($dto->toArray());
        return $this->repository->save($model);
    }
}
```

### Creating a Controller

```php
// app/Modules/Notification/Presentation/Http/Controllers/NotificationController.php
namespace App\Modules\Notification\Presentation\Http\Controllers;

use App\Modules\Notification\Application\Actions\CreateNotificationAction;
use App\Modules\Notification\Presentation\Http\Requests\StoreNotificationRequest;

class NotificationController extends Controller
{
    public function __construct(
        private CreateNotificationAction $createAction
    ) {}

    public function store(StoreNotificationRequest $request)
    {
        // Implementation here
    }
}
```

## Service Provider

The service provider is automatically registered in `bootstrap/providers.php`.

Repository bindings are configured in:
`app/Modules/Notification/Infrastructure/Providers/NotificationServiceProvider.php`
