<?php

namespace App\Modules\Dashboard\Infrastructure\Repositories;

use App\Modules\Dashboard\Domain\Models\Dashboard;
use App\Modules\Dashboard\Domain\Repositories\DashboardRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Repository Implementation - Eloquent implementation
 * Works directly with Eloquent Models
 */
class DashboardRepository implements DashboardRepositoryInterface
{

    public function all(array $searchCriteria) : Collection
    {
        // TODO: implement all here.
    }

    public function paginated(array $searchCriteria = [], int $perPage = 15, int $page = 1):LengthAwarePaginator{
        // TODO: implement paginated here.
    }

    public function findById(int $id): ?Dashboard{
        // TODO: implement
    }

    public function findByUuid(string $uuid): ?Dashboard{
         // TODO: implement findByUuid
    }

    public function create(array $args = []) : Dashboard{
         // TODO: implement create here.
    }

    public function update(array $data = [], Dashboard $model): Dashboard{
        // TODO: implement update here.
    }

    public function deleteById(int $id): bool{
        // TODO: implement deleteById here.
        return false;
    }

    public function deleteByUuid(string $uuid) : bool{
        // TODO: implement deleteByUuid here.
        return false;
    }
}

