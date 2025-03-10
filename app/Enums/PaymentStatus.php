<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';

    public function isPending(): bool
    {
        return $this->value === self::PENDING->value;
    }

    public function isPaid(): bool
    {
        return $this->value === self::PAID->value;
    }

    public function isFailed()
    {
        return $this->value === self::FAILED->value;
    }
}
