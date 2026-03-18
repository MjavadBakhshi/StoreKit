<?php

namespace Domain\FileManager\Builders;

use Illuminate\Database\Eloquent\Builder;

use Domain\FileManager\Models\FileUpload;

class FileUploadBuilder extends Builder
{

    // It is usually used to valdiate the selected file in the UI.
    static function getFilesGroupedByPublicId(array $ids) :array
    {
        return FileUpload::select('id', 'public_id')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('public_id')
            ->toArray();
    }

    static function getPublicId(int $id) :?string
    {
        return FileUpload::where('id', $id)->value('public_id');
    }
}