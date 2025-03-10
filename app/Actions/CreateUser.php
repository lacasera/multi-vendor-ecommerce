<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class CreateUser
{
    public function execute(array $payload): User
    {
        if (empty($payload['name']) || empty($payload['email']) || empty($payload['password'])) {
            throw new InvalidArgumentException('The name, email and password fields are required.');
        }

        return User::create([
            'name' => data_get($payload, 'name'),
            'email' => data_get($payload, 'email'),
            'password' => Hash::make(data_get($payload, 'password')),
        ]);
    }
}
