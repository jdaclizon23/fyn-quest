<?php

namespace App\Modules\OJT\Domain\Repositories;

use App\Modules\OJT\Domain\Models\OjtLog;

/**
 * Repository Interface - Defined in Domain layer
 * Uses Eloquent Models
 */
interface OjtLogRepositoryInterface
{
    public function findById(int $id): ?OjtLog;
    
    public function findByUserId(int $userId): array;
    
    public function save(OjtLog $ojtLog): OjtLog;
    
    public function delete(OjtLog $ojtLog): void;
    
    public function findAll(): array;
}
