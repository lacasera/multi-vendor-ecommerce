<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait HasStatus
{
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
