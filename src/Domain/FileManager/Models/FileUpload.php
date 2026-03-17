<?php

namespace Domain\FileManager\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $guarded = ['id'];

    function path():Attribute
    {
        return Attribute::make(
            get: fn() => "store-$this->store_id/$this->stored_name"
        );
    }

}