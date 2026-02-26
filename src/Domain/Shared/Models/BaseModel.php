<?php

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseModel extends Model
{
    use HasFactory;
    
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