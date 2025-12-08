# Flow

Controller → DTO → Action → Repository → Eloquent Model

## Architecture Layers

1. **Presentation Layer** (Controller)
   - Handles HTTP requests/responses
   - Uses DTOs to transfer data
   - Location: `Presentation/Http/Controllers/`

2. **Application Layer** (Action)
   - Orchestrates business logic
   - Uses Repository Interface
   - **Actions go here**: `Application/Actions/`

3. **Domain Layer** (Model + Repository Interface)
   - Eloquent Models with business logic
   - Repository interfaces (contracts)
   - Location: `Domain/Models/` and `Domain/Repositories/`

4. **Infrastructure Layer** (Repository Implementation)
   - Implements repository interfaces
   - Works with Eloquent Models
   - Location: `Infrastructure/Repositories/`