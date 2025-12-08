<?php

namespace App\Modules\Dashboard\Domain\Repositories;

use App\Modules\Dashboard\Domain\Models\Dashboard;
use Illuminate\Support\Collection;

/**
 * Repository Interface - Defined in Domain layer
 * Uses Eloquent Models
 */
interface DashboardRepositoryInterface
{
    public function all(array $searchCriteria): Collection;

    public function paginated(array $searchCriteria = [], int $perPage = 15, int $page = 1): mixed;
    
    public function findById(int $id): ?Dashboard;

    public function findByUuid(string $uuid): ?Dashboard;
    
    public function create(array $args = []): Dashboard;
    
    public function update(array $data,Dashboard $model): Dashboard;

    public function deleteById(int $id): bool;

    public function deleteByUuid(string $uuid): bool;
}
