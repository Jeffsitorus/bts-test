<?php

namespace App\Traits;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

trait AuthTrait
{
    public function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
