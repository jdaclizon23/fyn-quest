# OJT Module - Using Eloquent Models

## Architecture Overview

This module uses **Eloquent Models** directly in the Domain layer, following a simplified Clean Architecture approach.

## Structure

### ✅ Domain Layer: **Eloquent Models**
- **Location**: `app/Modules/OJT/Domain/Models/`
- **Example**: `OjtLog.php`
- **Characteristics**:
  - Extends Laravel's `Model` class
  - Contains business logic and rules
  - Handles database persistence
  - Uses Eloquent features (scopes, relationships, etc.)

### ✅ Domain Layer: **Repository Interfaces**
- **Location**: `app/Modules/OJT/Domain/Repositories/`
- **Example**: `OjtLogRepositoryInterface.php`
- **Characteristics**:
  - Defines contracts for data access
  - Uses Eloquent Models in signatures
  - Framework-agnostic interface

### ✅ Infrastructure Layer: **Repository Implementations**
- **Location**: `app/Modules/OJT/Infrastructure/Repositories/`
- **Example**: `EloquentOjtLogRepository.php`
- **Characteristics**:
  - Implements repository interfaces
  - Works directly with Eloquent Models
  - Handles database queries

## Architecture Flow

```
Presentation (Controller)
    ↓ uses DTO
Application (Action)
    ↓ uses Eloquent Model
Domain (Model + Repository Interface)
    ↑ implements
Infrastructure (Repository Implementation)
```

## Example Usage

### Eloquent Model (With Business Logic)
```php
// app/Modules/OJT/Domain/Models/OjtLog.php
class OjtLog extends Model {
    public function approve(): void {
        // Business logic here
        $this->status = 'approved';
        $this->save();
    }
}
```

### Repository Interface
```php
// app/Modules/OJT/Domain/Repositories/OjtLogRepositoryInterface.php
interface OjtLogRepositoryInterface {
    public function findById(int $id): ?OjtLog;
    public function save(OjtLog $ojtLog): OjtLog;
}
```

### Repository Implementation
```php
// app/Modules/OJT/Infrastructure/Repositories/EloquentOjtLogRepository.php
class EloquentOjtLogRepository implements OjtLogRepositoryInterface {
    public function findById(int $id): ?OjtLog {
        return OjtLog::find($id);
    }
}
```

## Rules

1. **Domain layer** = Eloquent Models with business logic + Repository Interfaces
2. **Infrastructure layer** = Repository Implementations
3. **Application layer** = Works with Eloquent Models (via Repository Interface)
4. **Presentation layer** = Works with DTOs (which use Eloquent Models)

## Service Provider Registration

To use the repository pattern with dependency injection, register the service provider:

**File**: `bootstrap/providers.php`
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Modules\OJT\Infrastructure\Providers\OjtServiceProvider::class, // Add this
];
```

This will bind `OjtLogRepositoryInterface` to `EloquentOjtLogRepository` automatically.

## Benefits

- **Simplicity**: Direct use of Eloquent features
- **Laravel Integration**: Full access to Eloquent capabilities
- **Business Logic**: Lives in Model methods
- **Testability**: Can mock repositories for testing
- **Flexibility**: Can swap repository implementations if needed
