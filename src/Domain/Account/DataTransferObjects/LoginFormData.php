<?php

namespace Domain\Account\DataTransferObjects;

use Spatie\LaravelData\Data;

class LoginFormData extends Data
{
    function __construct(
        public readonly string $email,
        public readonly string $password
    ){}

    static function rules() :array
    {
        return [
            'email' => 'required|string|email'
        ];
    }
}