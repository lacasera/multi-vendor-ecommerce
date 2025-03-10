<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait HasSupplier
{
    public function scopeForSupplier($query, int $supplierId)
    {
        return $query->where('user_id', $supplierId);
    }
}
