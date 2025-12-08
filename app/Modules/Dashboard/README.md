# Dashboard Module

## Overview

This module follows Clean Architecture principles with Eloquent Models.

## Structure

```
app/Modules/Dashboard/
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
// app/Modules/Dashboard/Application/Actions/CreateDashboardAction.php
namespace App\Modules\Dashboard\Application\Actions;

use App\Modules\Dashboard\Application\DTO\CreateDashboardDTO;
use App\Modules\Dashboard\Domain\Models\Dashboard;
use App\Modules\Dashboard\Domain\Repositories\DashboardRepositoryInterface;

class CreateDashboardAction
{
    public function __construct(
        private DashboardRepositoryInterface $repository
    ) {}

    public function execute(CreateDashboardDTO $dto): Dashboard
    {
        $model = new Dashboard($dto->toArray());
        return $this->repository->save($model);
    }
}
```

### Creating a Controller

```php
// app/Modules/Dashboard/Presentation/Http/Controllers/DashboardController.php
namespace App\Modules\Dashboard\Presentation\Http\Controllers;

use App\Modules\Dashboard\Application\Actions\CreateDashboardAction;
use App\Modules\Dashboard\Presentation\Http\Requests\StoreDashboardRequest;

class DashboardController extends Controller
{
    public function __construct(
        private CreateDashboardAction $createAction
    ) {}

    public function store(StoreDashboardRequest $request)
    {
        // Implementation here
    }
}
```

## Service Provider

The service provider is automatically registered in `bootstrap/providers.php`.

Repository bindings are configured in:
`app/Modules/Dashboard/Infrastructure/Providers/DashboardServiceProvider.php`
