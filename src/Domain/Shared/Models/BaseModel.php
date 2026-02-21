<?php

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class BaseModel extends Model
{
    protected static function newFactory(): Factory
    {
        // Get short class name, e.g. 'User' from 'Domain\Account\Models\User'
        $modelName = class_basename(static::class);

        // Build the factory class FQN
        $factoryClass = "Database\\Factories\\{$modelName}Factory";

        if (!class_exists($factoryClass)) {
            throw new \Exception("Factory class {$factoryClass} not found for model {$modelName}");
        }

        return $factoryClass::new();
    }
}