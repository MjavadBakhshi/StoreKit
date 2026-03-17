<?php

namespace Domain\FileManager\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use Domain\Shared\Models\Concerns\HasPublicId;

class FileUpload extends Model
{
    use HasPublicId;

    protected $guarded = ['id'];

    function path():Attribute
    {
        return Attribute::make(
            get: fn() => "store-$this->store_id/$this->stored_name"
        );
    }

}