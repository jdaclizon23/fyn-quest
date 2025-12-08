# Where to Put Actions Folder in Clean Architecture

## Quick Answer

**Actions = Use Cases** → Place in **`Application/Actions/`**

---

## Understanding Actions

Looking at your current Actions (like `CreateNewUser`, `ResetUserPassword`), they are:
- ✅ Single-purpose classes
- ✅ Perform specific operations
- ✅ Orchestrate business logic
- ✅ Use validation and models

In Clean Architecture, these are **Use Cases** (Application layer).

---

## Placement Options

### Application/Actions/ (Recommended)

```
app/Modules/OJT/
├── Application/
│   ├── Actions/           ← Actions go here
│   │   ├── CreateOjtLogAction.php
│   │   ├── ApproveOjtLogAction.php
│   │   └── RejectOjtLogAction.php
│   └── DTO/
│       └── CreateOjtLogDTO.php
```

**Why**: Matches the module structure and naming convention

---

## Mapping: Current Actions → Clean Architecture

| Current Location | Clean Architecture Location | Example |
|-----------------|----------------------------|---------|
| `app/Actions/Fortify/CreateNewUser.php` | `app/Modules/OJT/Application/Actions/CreateOjtLogAction.php` | Action |
| `app/Actions/Fortify/ResetUserPassword.php` | `app/Modules/OJT/Application/Actions/ResetOjtLogPasswordAction.php` | Action |

---

## Example: Converting Action to Clean Architecture Action

### Before (Action Pattern)
```php
<?php
// app/Actions/Fortify/CreateNewUser.php
namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateNewUser
{
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
    }
}
```

### After (Clean Architecture Action Pattern)
```php
<?php
// app/Modules/OJT/Application/Actions/CreateOjtLogAction.php
namespace App\Modules\OJT\Application\Actions;

use App\Modules\OJT\Application\DTO\CreateOjtLogDTO;
use App\Modules\OJT\Domain\Models\OjtLog;
use App\Modules\OJT\Domain\Repositories\OjtLogRepositoryInterface;

class CreateOjtLogAction
{
    public function __construct(
        private OjtLogRepositoryInterface $repository
    ) {}

    public function execute(CreateOjtLogDTO $dto): OjtLog
    {
        // Create domain model
        $ojtLog = new OjtLog([
            'user_id' => $dto->userId,
            'title' => $dto->title,
            'description' => $dto->description,
            'log_date' => $dto->logDate,
            'hours' => $dto->hours,
            'status' => 'pending',
        ]);

        // Save via repository
        return $this->repository->save($ojtLog);
    }
}
```

**Key Differences:**
- ✅ Uses DTO instead of raw array
- ✅ Uses Repository Interface (not direct model access)
- ✅ Dependency injection
- ✅ Single responsibility (execute method)

---

## Complete Structure with Actions/Use Cases

```
app/Modules/OJT/
├── Domain/
│   ├── Models/
│   │   └── OjtLog.php
│   └── Repositories/
│       └── OjtLogRepositoryInterface.php
│
├── Application/
│   ├── Actions/               ← Actions go here
│   │   ├── CreateOjtLogAction.php
│   │   ├── UpdateOjtLogAction.php
│   │   ├── ApproveOjtLogAction.php
│   │   └── DeleteOjtLogAction.php
│   └── DTO/
│       ├── CreateOjtLogDTO.php
│       └── UpdateOjtLogDTO.php
│
├── Infrastructure/
│   ├── Repositories/
│   │   └── EloquentOjtLogRepository.php
│   └── Providers/
│       └── OjtServiceProvider.php
│
└── Presentation/
    ├── Http/
    │   ├── Controllers/
    │   │   └── OjtLogController.php
    │   └── Requests/
    │       └── StoreOjtLogRequest.php
    └── Routes/
        └── ojt_api.php
```

---

## Flow with Actions/Use Cases

```
Controller
    ↓ (uses DTO)
Action                  ← Your Actions go here
    ↓ (uses Repository Interface)
Repository Interface
    ↑ (implements)
Repository Implementation
    ↓ (works with)
Eloquent Model
```

---

## Decision: Actions Folder

### Use `Application/Actions/`:
- ✅ Matches the module structure
- ✅ Consistent naming convention
- ✅ Clear and straightforward

**Important points:**
1. They're in the **Application layer**
2. They orchestrate business logic
3. They use **DTOs** and **Repository Interfaces**

---

## Example: OJT Module Actions Structure

```php
<?php
// app/Modules/OJT/Application/Actions/CreateOjtLogAction.php
namespace App\Modules\OJT\Application\Actions;

use App\Modules\OJT\Application\DTO\CreateOjtLogDTO;
use App\Modules\OJT\Domain\Models\OjtLog;
use App\Modules\OJT\Domain\Repositories\OjtLogRepositoryInterface;

class CreateOjtLogAction
{
    public function __construct(
        private OjtLogRepositoryInterface $repository
    ) {}

    public function execute(CreateOjtLogDTO $dto): OjtLog
    {
        $ojtLog = new OjtLog([
            'user_id' => $dto->userId,
            'title' => $dto->title,
            'description' => $dto->description,
            'log_date' => $dto->logDate,
            'hours' => $dto->hours,
            'status' => 'pending',
        ]);

        return $this->repository->save($ojtLog);
    }
}
```

```php
<?php
// app/Modules/OJT/Presentation/Http/Controllers/OjtLogController.php
namespace App\Modules\OJT\Presentation\Http\Controllers;

use App\Modules\OJT\Application\DTO\CreateOjtLogDTO;
use App\Modules\OJT\Application\Actions\CreateOjtLogAction;
use App\Modules\OJT\Presentation\Http\Requests\StoreOjtLogRequest;

class OjtLogController extends Controller
{
    public function __construct(
        private CreateOjtLogAction $createOjtLogAction
    ) {}

    public function store(StoreOjtLogRequest $request)
    {
        $dto = new CreateOjtLogDTO(
            userId: $request->user()->id,
            title: $request->validated('title'),
            description: $request->validated('description'),
            logDate: $request->validated('log_date'),
            hours: $request->validated('hours'),
        );

        $ojtLog = $this->createOjtLogAction->execute($dto);

        return response()->json($ojtLog, 201);
    }
}
```

---

## Summary

**Actions folder location:**
- ✅ **`app/Modules/OJT/Application/Actions/`**

**Key points:**
- Actions = Use Cases in Clean Architecture
- They belong in the **Application layer**
- They orchestrate business logic
- They use DTOs and Repository Interfaces
- Controllers call Actions, not repositories directly
