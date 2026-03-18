<?php

namespace Domain\FileManager\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Domain\Shared\Models\BaseModel;
use Domain\FileManager\Builders\FileUploadBuilder;
use Domain\Shared\Models\Concerns\HasPublicId;

class FileUpload extends BaseModel
{
    use HasPublicId;

    protected $guarded = ['id'];

    function path():Attribute
    {
        return Attribute::make(
            get: fn() => "store-$this->store_id/$this->stored_name"
        );
    }

    function newEloquentBuilder($query) :Builder
    {
        return new FileUploadBuilder($query);
    }

}