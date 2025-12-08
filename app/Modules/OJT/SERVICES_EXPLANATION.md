# Domain/Services vs Infrastructure/Services

## Key Differences

| Aspect | Domain/Services | Infrastructure/Services |
|--------|----------------|------------------------|
| **Purpose** | Pure business logic | External integrations |
| **Dependencies** | None (pure PHP) | Laravel, external packages |
| **Framework** | Framework-agnostic | Framework-specific |
| **Location** | `Domain/Services/` | `Infrastructure/Services/` |
| **Testability** | Can test without Laravel | Requires framework/mocks |
| **Examples** | Business rules, calculations | Email, APIs, file system |

---

## Domain/Services

### Purpose
Contains **pure business logic** that doesn't naturally belong to a single entity. These are domain-specific operations that involve business rules and calculations.

### Characteristics
- ✅ **Pure PHP** - No Laravel dependencies
- ✅ **Framework-agnostic** - Can work without Laravel
- ✅ **Business logic only** - No external concerns
- ✅ **Works with Domain entities/models** - Operates on domain objects
- ❌ **No database access** - Uses repositories via interfaces
- ❌ **No HTTP calls** - No external API calls
- ❌ **No file system** - No file operations

### When to Use
- Business calculations that span multiple entities
- Complex business rules that don't fit in a single entity
- Domain-specific algorithms
- Business validations that require multiple entities

### Example: Domain Service

```php
<?php
// app/Modules/OJT/Domain/Services/OjtHoursCalculator.php

namespace App\Modules\OJT\Domain\Services;

use App\Modules\OJT\Domain\Models\OjtLog;

/**
 * Domain Service - Pure business logic
 * Calculates total hours worked based on business rules
 */
class OjtHoursCalculator
{
    /**
     * Calculate total hours for a user
     * Business rule: Only count approved logs
     */
    public function calculateTotalHours(array $ojtLogs): int
    {
        return array_reduce(
            $ojtLogs,
            function (int $total, OjtLog $log) {
                // Business rule: Only approved logs count
                if ($log->status === 'approved') {
                    return $total + $log->hours;
                }
                return $total;
            },
            0
        );
    }

    /**
     * Business rule: Calculate average hours per day
     */
    public function calculateAverageHoursPerDay(array $ojtLogs): float
    {
        $approvedLogs = array_filter(
            $ojtLogs,
            fn(OjtLog $log) => $log->status === 'approved'
        );

        if (empty($approvedLogs)) {
            return 0.0;
        }

        $totalHours = $this->calculateTotalHours($ojtLogs);
        $daysCount = count($approvedLogs);

        return $totalHours / $daysCount;
    }

    /**
     * Business rule: Check if user has completed required hours
     */
    public function hasCompletedRequiredHours(array $ojtLogs, int $requiredHours): bool
    {
        $totalHours = $this->calculateTotalHours($ojtLogs);
        return $totalHours >= $requiredHours;
    }
}
```

**Key Points:**
- Pure PHP, no Laravel dependencies
- Works with domain models (`OjtLog`)
- Contains business rules (only approved logs count)
- Can be tested without database or framework

---

## Infrastructure/Services

### Purpose
Implements **external concerns** and integrations. These handle all interactions with external systems, frameworks, and services.

### Characteristics
- ✅ **Framework-specific** - Uses Laravel features
- ✅ **External integrations** - APIs, email, file system, etc.
- ✅ **Implements interfaces** - Implements contracts from Domain/Application
- ✅ **Can use Laravel facades** - Mail, Storage, Http, etc.
- ❌ **No business logic** - Business rules belong in Domain
- ❌ **Not framework-agnostic** - Depends on Laravel

### When to Use
- Sending emails
- Calling external APIs
- File system operations
- Payment gateway integrations
- Notification services
- Caching implementations

### Example: Infrastructure Service

```php
<?php
// app/Modules/OJT/Infrastructure/Services/EmailNotificationService.php

namespace App\Modules\OJT\Infrastructure\Services;

use App\Modules\OJT\Domain\Models\OjtLog;
use Illuminate\Support\Facades\Mail;

/**
 * Infrastructure Service - External integration
 * Handles email notifications (Laravel-specific)
 */
class EmailNotificationService
{
    /**
     * Send approval notification email
     * Uses Laravel Mail facade
     */
    public function sendApprovalNotification(OjtLog $ojtLog, string $recipientEmail): void
    {
        Mail::to($recipientEmail)->send(
            new OjtLogApprovedMail($ojtLog)
        );
    }

    /**
     * Send rejection notification email
     */
    public function sendRejectionNotification(OjtLog $ojtLog, string $recipientEmail, string $reason): void
    {
        Mail::to($recipientEmail)->send(
            new OjtLogRejectedMail($ojtLog, $reason)
        );
    }
}
```

**Key Points:**
- Uses Laravel Mail facade
- Handles external concern (email)
- Framework-specific implementation
- Requires Laravel to work

---

## Complete Example: Using Both

### 1. Domain Service Interface (Define Contract)

```php
<?php
// app/Modules/OJT/Domain/Services/OjtHoursCalculatorInterface.php

namespace App\Modules\OJT\Domain\Services;

interface OjtHoursCalculatorInterface
{
    public function calculateTotalHours(array $ojtLogs): int;
    public function hasCompletedRequiredHours(array $ojtLogs, int $requiredHours): bool;
}
```

### 2. Domain Service Implementation (Pure Business Logic)

```php
<?php
// app/Modules/OJT/Domain/Services/OjtHoursCalculator.php

namespace App\Modules\OJT\Domain\Services;

class OjtHoursCalculator implements OjtHoursCalculatorInterface
{
    public function calculateTotalHours(array $ojtLogs): int
    {
        // Pure business logic - no external dependencies
        return array_reduce(
            $ojtLogs,
            fn(int $total, $log) => $total + ($log->status === 'approved' ? $log->hours : 0),
            0
        );
    }

    public function hasCompletedRequiredHours(array $ojtLogs, int $requiredHours): bool
    {
        return $this->calculateTotalHours($ojtLogs) >= $requiredHours;
    }
}
```

### 3. Infrastructure Service (External Integration)

```php
<?php
// app/Modules/OJT/Infrastructure/Services/EmailNotificationService.php

namespace App\Modules\OJT\Infrastructure\Services;

use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public function sendApprovalNotification($ojtLog, string $email): void
    {
        // Laravel-specific - external concern
        Mail::to($email)->send(new OjtLogApprovedMail($ojtLog));
    }
}
```

### 4. Action (Orchestrates Both)

```php
<?php
// app/Modules/OJT/Application/Actions/ApproveOjtLogAction.php

namespace App\Modules\OJT\Application\Actions;

use App\Modules\OJT\Domain\Models\OjtLog;
use App\Modules\OJT\Domain\Repositories\OjtLogRepositoryInterface;
use App\Modules\OJT\Domain\Services\OjtHoursCalculatorInterface;
use App\Modules\OJT\Infrastructure\Services\EmailNotificationService;

class ApproveOjtLogAction
{
    public function __construct(
        private OjtLogRepositoryInterface $repository,
        private OjtHoursCalculatorInterface $calculator, // Domain service
        private EmailNotificationService $emailService   // Infrastructure service
    ) {}

    public function execute(int $ojtLogId, string $userEmail): void
    {
        // 1. Get domain model
        $ojtLog = $this->repository->findById($ojtLogId);
        
        // 2. Use domain service for business logic
        $userLogs = $this->repository->findByUserId($ojtLog->user_id);
        $hasCompletedHours = $this->calculator->hasCompletedRequiredHours($userLogs, 100);
        
        // 3. Business logic (domain model)
        $ojtLog->approve();
        
        // 4. Persist (repository)
        $this->repository->save($ojtLog);
        
        // 5. External concern (infrastructure service)
        if ($hasCompletedHours) {
            $this->emailService->sendApprovalNotification($ojtLog, $userEmail);
        }
    }
}
```

---

## Decision Tree: Which One to Use?

```
Is it pure business logic?
├─ YES → Domain/Service
│   └─ Examples: Calculations, business rules, validations
│
└─ NO → Is it external integration?
    ├─ YES → Infrastructure/Service
    │   └─ Examples: Email, APIs, file system, payment gateways
    │
    └─ NO → Is it action orchestration?
        └─ YES → Application/Action
            └─ Examples: Coordinate domain services + infrastructure services
```

---

## Summary

| Question | Domain/Service | Infrastructure/Service |
|----------|---------------|----------------------|
| **Can it work without Laravel?** | ✅ Yes | ❌ No |
| **Does it contain business rules?** | ✅ Yes | ❌ No |
| **Does it call external APIs?** | ❌ No | ✅ Yes |
| **Does it send emails?** | ❌ No | ✅ Yes |
| **Does it calculate totals?** | ✅ Yes | ❌ No |
| **Does it use Laravel facades?** | ❌ No | ✅ Yes |
| **Is it framework-agnostic?** | ✅ Yes | ❌ No |

---

## Best Practices

1. **Domain Services**: Keep pure, no external dependencies
2. **Infrastructure Services**: Implement interfaces defined in Domain/Application
3. **Use Dependency Injection**: Inject services via interfaces
4. **Test Independently**: Domain services can be tested without Laravel
5. **Keep Business Logic in Domain**: Don't put business rules in Infrastructure services
