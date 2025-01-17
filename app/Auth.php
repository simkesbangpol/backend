<?php

namespace App;

use App\Exceptions\UnauthorizedException;
use App\Transformers\TokenTransformer;
use App\Transformers\UserTransformer;

class Auth
{
    /**
     * Authenticate a user by emailand password
     *
     * @param  string  $username
     * @param  string  $password
     * @return array
     */
    public function authenticateByEmailAndPassword(string $username, string $password): array
    {
        if (!$token = app('auth')->attempt(compact('username', 'password'))) {
            throw new UnauthorizedException("Wrong Login credentials");
        }

        $token = fractal($token, new TokenTransformer())->toArray();
        $token['data']['user'] = app('auth')->user();
        $token['data']['roles'] = app('auth')->user()->getRoleNames();

        return $token;
    }

    /**
     * Get the current authenticated user.
     *
     * @return array
     */
    public function getAuthenticatedUser(): array
    {
        $user = app('auth')->user();

        return fractal($user, new UserTransformer())->toArray();
    }

    /**
     * Refresh current authentication token.
     *
     * @return array
     */
    public function refreshAuthenticationToken(): array
    {
        $token = app('auth')->refresh();

        return fractal($token, new TokenTransformer())->toArray();
    }

    /**
     * Invalidate current authentication token.
     *
     * @return bool
     */
    public function invalidateAuthenticationToken(): bool
    {
        return (bool) app('auth')->logout();
    }
}
