<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: string
{
    case USER = 'user';
    case SUPPLIER = 'supplier';
    case ADMIN = 'admin';

    public function isSupplier(): bool
    {
        return $this->value == self::SUPPLIER->value;
    }

    public function isAdmin(): bool
    {
        return $this->value == self::ADMIN->value;
    }

    public function isUser(): bool
    {
        return $this->value == self::USER->value;
    }
}
