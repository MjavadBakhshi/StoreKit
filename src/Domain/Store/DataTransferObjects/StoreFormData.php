<?php

namespace Domain\Store\DataTransferObjects;

use Spatie\LaravelData\Data;

class StoreFormData extends Data
{
    function __construct(
        public readonly string $name,
        public readonly ?string $description
    ) {}
}