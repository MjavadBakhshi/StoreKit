<?php

namespace Domain\FileManager\Actions;

use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Support\Str;

use Domain\Shared\Exceptions\ActionException;
use Domain\FileManager\Models\FileUpload;
use Domain\Store\Models\Store;
use GuzzleHttp\Psr7\UploadedFile;

class UploadFilesAction 
{
    static function execute(
        array $files,
        Store $store,
        bool $isPrivate = false,
        string $storageName = 'public'
    ) :array|false|ActionException
    {
        try {
            DB::beginTransaction();

            $storedFileIdsList = [];
            $store = Store::lockForUpdate()->find($store->id);

        
            foreach($files as $file)
            {
                // Checking store capacity.
                $fileSize = $file->getSize() / 1024; // IN KB

                if($store->free_storage_capacity < $fileSize)
                {
                    DB::commit();
                    return new ActionException(
                        "Storage capacity has not enough capacity to upload more files."
                    );
                }

                // Reducing the storage capacity.
                $store->free_storage_capacity = max(
                    0, $store->free_storage_capacity - $fileSize
                );

                // changing name.
                $storedName = Str::orderedUuid()->toString();
                $filePath = "store-{$store->id}/{$storedName}";
                Storage::disk($storageName)->put($filePath, $file);

                // Saving file record.
                $file = FileUpload::create([
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => $storedName,
                    'is_private' => $isPrivate,
                    'store_id' => $store->id,
                    'size' => $fileSize
                ]);

                $storedFileIdsList[] = $file->id;
            }

            // persist capacity changes.
            $store->save();

            DB::commit();

            return $storedFileIdsList;
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return false;
        }
    }   
}