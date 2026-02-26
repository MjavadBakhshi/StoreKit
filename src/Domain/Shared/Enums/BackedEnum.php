<?php

namespace Domain\Shared\Enums;

/**
 * This trai provides some usefull helpers for all backed enums.
 */
trait BackedEnum
{

    static function has(string $value) :bool
    {
        return in_array($value, static::values());
    }

    static function values() :array
    {
        return array_values(static::cases());
    }
}