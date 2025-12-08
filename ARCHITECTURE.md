# Clean Architecture Design Pattern for Laravel 12

## Overview

This document outlines a Clean Architecture pattern adapted for Laravel 12, promoting separation of concerns, testability, and maintainability.

## Architecture Layers

```
app/
├── Domain/              # Core business logic (independent of framework)
│   ├── Entities/        # Domain entities
│   ├── ValueObjects/    # Value objects
│   ├── Enums/          # Domain enums
│   └── Interfaces/     # Domain interfaces/contracts
│
├── Application/         # Application use cases and business logic
│   ├── Services/        # Application services (use cases)
│   ├── DTOs/           # Data Transfer Objects
│   ├── Interfaces/     # Application interfaces
│   └── Exceptions/     # Application exceptions
│
├── Infrastructure/      # External concerns (database, APIs, etc.)
│   ├── Repositories/    # Repository implementations
│   ├── Services/        # External service integrations
│   └── Factories/       # Infrastructure factories
│
└── Presentation/        # HTTP layer (controllers, requests, responses)
    ├── Http/
    │   ├── Controllers/ # Thin controllers (delegate to services)
    │   ├── Requests/    # Form requests (validation)
    │   ├── Resources/   # API resources
    │   └── Middleware/  # HTTP middleware
    └── Inertia/         # Inertia-specific resources
```

## Layer Responsibilities

### 1. Domain Layer (`app/Domain/`)
**Purpose**: Core business logic, completely framework-agnostic.

- **Entities**: Rich domain models with business logic
- **ValueObjects**: Immutable objects representing domain concepts
- **Enums**: Domain-specific enumerations
- **Interfaces**: Contracts that define domain operations

**Rules**:
- No dependencies on Laravel or external libraries
- Contains only pure PHP business logic
- Can be tested without framework

**Example Structure**:
```
Domain/
├── User/
│   ├── User.php                    # Domain entity
│   ├── UserId.php                  # Value object
│   ├── Email.php                   # Value object
│   ├── UserStatus.php              # Enum
│   └── Interfaces/
│       └── UserRepositoryInterface.php
```

### 2. Application Layer (`app/Application/`)
**Purpose**: Orchestrates domain objects to perform application use cases.

- **Services**: Application services that coordinate domain objects
- **DTOs**: Data structures for transferring data between layers
- **Interfaces**: Application-level contracts
- **Exceptions**: Application-specific exceptions

**Rules**:
- Depends only on Domain layer
- Contains use case orchestration logic
- No direct database access (uses repositories)

**Example Structure**:
```
Application/
├── User/
│   ├── Services/
│   │   ├── CreateUserService.php
│   │   ├── UpdateUserProfileService.php
│   │   └── DeleteUserService.php
│   ├── DTOs/
│   │   ├── CreateUserDTO.php
│   │   └── UpdateProfileDTO.php
│   └── Interfaces/
│       └── UserServiceInterface.php
```

### 3. Infrastructure Layer (`app/Infrastructure/`)
**Purpose**: Implements interfaces defined in Domain/Application layers.

- **Repositories**: Eloquent implementations of domain repositories
- **Services**: External API integrations, email services, etc.
- **Factories**: Infrastructure-specific factories

**Rules**:
- Implements interfaces from Domain/Application layers
- Can depend on Laravel and external packages
- Handles all external concerns (database, APIs, file system)

**Example Structure**:
```
Infrastructure/
├── Repositories/
│   └── EloquentUserRepository.php
├── Services/
│   ├── EmailService.php
│   └── PaymentGatewayService.php
└── Factories/
    └── UserFactory.php
```

### 4. Presentation Layer (`app/Presentation/`)
**Purpose**: Handles HTTP requests and responses.

- **Controllers**: Thin controllers that delegate to application services
- **Requests**: Form request validation
- **Resources**: API resource transformers
- **Middleware**: HTTP middleware

**Rules**:
- Depends on Application layer
- Handles HTTP concerns only
- Controllers should be thin (delegate to services)

## Dependency Flow

```
Presentation → Application → Domain
     ↓              ↓
Infrastructure → Domain
```

**Key Principle**: Dependencies point inward. Outer layers depend on inner layers, but inner layers never depend on outer layers.

## Example Implementation

### Domain Entity
```php
// app/Domain/User/User.php
namespace App\Domain\User;

class User
{
    public function __construct(
        private UserId $id,
        private Email $email,
        private string $name,
        private UserStatus $status
    ) {}

    public function updateProfile(string $name, Email $email): void
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }
}
```

### Application Service
```php
// app/Application/User/Services/UpdateUserProfileService.php
namespace App\Application\User\Services;

use App\Application\User\DTOs\UpdateProfileDTO;
use App\Domain\User\Interfaces\UserRepositoryInterface;

class UpdateUserProfileService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(UpdateProfileDTO $dto): void
    {
        $user = $this->userRepository->findById($dto->userId);
        
        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->updateProfile($dto->name, $dto->email);
        
        $this->userRepository->save($user);
    }
}
```

### Infrastructure Repository
```php
// app/Infrastructure/Repositories/EloquentUserRepository.php
namespace App\Infrastructure\Repositories;

use App\Domain\User\User;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\UserId;
use App\Models\User as UserModel;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value());
        
        return $model ? $this->toDomain($model) : null;
    }

    public function save(User $user): void
    {
        UserModel::updateOrCreate(
            ['id' => $user->getId()->value()],
            $this->toArray($user)
        );
    }

    private function toDomain(UserModel $model): User
    {
        // Convert Eloquent model to domain entity
    }

    private function toArray(User $user): array
    {
        // Convert domain entity to array
    }
}
```

### Presentation Controller
```php
// app/Presentation/Http/Controllers/Settings/ProfileController.php
namespace App\Presentation\Http\Controllers\Settings;

use App\Application\User\Services\UpdateUserProfileService;
use App\Application\User\DTOs\UpdateProfileDTO;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function __construct(
        private UpdateUserProfileService $updateProfileService
    ) {}

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $dto = new UpdateProfileDTO(
            userId: $request->user()->id,
            name: $request->validated('name'),
            email: $request->validated('email')
        );

        $this->updateProfileService->execute($dto);

        return to_route('profile.edit');
    }
}
```

## Benefits

1. **Testability**: Each layer can be tested independently
2. **Maintainability**: Clear separation of concerns
3. **Flexibility**: Easy to swap implementations (e.g., change database)
4. **Scalability**: Structure supports growth
5. **Framework Independence**: Domain logic doesn't depend on Laravel

## Migration Strategy

1. Start with new features using clean architecture
2. Gradually refactor existing code
3. Extract domain logic from controllers to services
4. Create repositories for database access
5. Move business logic from models to domain entities

## Best Practices

1. **Keep controllers thin**: Controllers should only handle HTTP concerns
2. **Use DTOs**: Transfer data between layers using DTOs
3. **Dependency Injection**: Use Laravel's container for dependency injection
4. **Interfaces**: Define contracts in Domain/Application, implement in Infrastructure
5. **Single Responsibility**: Each class should have one reason to change
6. **Don't over-engineer**: Start simple, add complexity as needed

## Additional Considerations

- **Events**: Use Laravel events for cross-cutting concerns
- **Jobs**: Use queues for async operations
- **Caching**: Implement caching at the Infrastructure layer
- **Validation**: Keep validation in Form Requests (Presentation) and Domain entities
- **Authorization**: Use Laravel policies, but keep business rules in Domain
