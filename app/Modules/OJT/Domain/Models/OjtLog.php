<?php

namespace App\Modules\OJT\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * OJT Log Model - Eloquent Model with business logic
 */
class OjtLog extends Model
{
    use HasFactory;

    protected $table = 'ojt_logs';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'log_date',
        'hours',
        'status',
    ];

    protected $casts = [
        'log_date' => 'datetime',
        'hours' => 'integer',
    ];

    /**
     * Boot method to add model events and validation
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($ojtLog) {
            $ojtLog->validate();
        });

        static::updating(function ($ojtLog) {
            $ojtLog->validate();
        });
    }

    /**
     * Business rule: Validate model attributes
     */
    private function validate(): void
    {
        if ($this->hours <= 0) {
            throw new \InvalidArgumentException('Hours must be greater than zero');
        }

        if (empty($this->title)) {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        if (empty($this->description)) {
            throw new \InvalidArgumentException('Description cannot be empty');
        }
    }

    /**
     * Business logic: Update log details
     */
    public function updateLog(string $title, string $description, int $hours): void
    {
        $this->title = $title;
        $this->description = $description;
        $this->hours = $hours;
        $this->save();
    }

    /**
     * Business logic: Approve the log
     */
    public function approve(): void
    {
        if ($this->status === 'approved') {
            throw new \DomainException('Log is already approved');
        }
        $this->status = 'approved';
        $this->save();
    }

    /**
     * Business logic: Reject the log
     */
    public function reject(): void
    {
        if ($this->status === 'rejected') {
            throw new \DomainException('Log is already rejected');
        }
        $this->status = 'rejected';
        $this->save();
    }

    /**
     * Business logic: Check if log is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Scope: Get pending logs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get approved logs
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get logs by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
