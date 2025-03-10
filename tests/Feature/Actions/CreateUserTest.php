<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\CreateUser;
use App\Models\User;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    #[Test]
    #[DataProvider('invalid_payload_data_provider')]
    public function it_will_fail_when_payload_is_empty(array $payload): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The name, email and password fields are required.');

        (new CreateUser())->execute($payload);
    }

    #[Test]
    public function it_will_create_a_user_successfully(): void
    {
        $user = (new CreateUser())->execute([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'testing',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test User',
            'email' => 'test@test.com',
        ]);
    }

    public static function invalid_payload_data_provider(): array
    {
        return [
            'empty payload' => [
                []
            ],
            'non-existent name' => [
                ['email' => 'test@test.com', 'password' => 'testing'],
            ],
            'non-existent email' => [
                ['name' => 'Test User', 'password' => 'testing'],
            ],
            'non-existent password' => [
                ['name' => 'Test User', 'email' => 'test@test.com',],
            ],
            'fields with null' => [
                ['name' => null, 'email' => null, 'password' => null],
            ],
            'fields with empty string' => [
                ['name' => '', 'email' => '', 'password' => ''],
            ],
        ];
    }
}
