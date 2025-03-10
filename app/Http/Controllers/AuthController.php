<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateUser;
use App\Exceptions\PermissionDenied;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Authentication failed.', Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user->type->isUser()) {
            throw new PermissionDenied('You cannot do this.');
        }

        $token = $this->createToken($user);

        return $this->success(compact('user', 'token'));
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, CreateUser $createUser): JsonResponse
    {
        try {
            $user = $createUser->execute($request->validated());
            $token = $this->createToken($user);

            return $this->success(compact('user', 'token'), 'success', JsonResponse::HTTP_CREATED);
        } catch (Throwable $th) {
            return $this->error('Something unexpected happened.', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param User $user
     * @return string
     */
    private function createToken(User $user): string
    {
        // Revoke all tokens...
        $user->tokens()->delete();

        return $user->createToken($user->name, ['*'], now()->addDays(3))->plainTextToken;
    }
}
