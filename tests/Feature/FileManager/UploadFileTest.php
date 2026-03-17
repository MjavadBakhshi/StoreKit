<?php

namespace Tests\Feature\FileManager;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

use Domain\FileManager\Actions\UploadFilesAction;
use Domain\FileManager\Models\FileUpload;
use Domain\Shared\Exceptions\ActionException;

class UploadFileTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
        $this->store->update([
            'max_storage_capacity' => 10 * 1024, # 10 MB
            'free_storage_capacity' => 10 * 1024, # 10 MB
        ]);
    }


    #[Test]
    function can_upload_multiple_files_successfully()
    {  
        // Making fake files.
        $storage = Storage::fake('files');
        list($files, $fakeFiles) = $this->getFakeFiles();

        // #TODO end to end testing using endpoints
        
        // Trying to upload
        $storedFileIds = UploadFilesAction::execute(
            $fakeFiles,
            $this->store,
            storageName: 'files'
        );

        // Checking records count and files count.
        $this->assertEquals(count($fakeFiles), count($storedFileIds));

        // Checking records has been stored and files has been uploaded?
        $storedFiles = FileUpload::whereIn('id', $storedFileIds)->get();
        foreach($storedFiles as $file)
            $storage->assertExists($file->path);

        // Checking free storage capacity of the store has been reduced correctly?
        $totalUploadSize = collect($files)->sum('size');
        // sync store data with DB.
        $this->store->refresH();
        $this->assertEquals(
            $this->store->max_storage_capacity - $totalUploadSize,
            $this->store->free_storage_capacity
        );

    }

    #[Test]
    function cannot_upload_more_than_storeage_capacity_of_store()
    {   
        $this->store->update([
            'max_storage_capacity' => 2 * 1024, # 2MB,
            'free_storage_capacity' => 2 * 1024, # 2MB,
        ]);

        // Making fake files.
        Storage::fake('files');
        list($files, $fakeFiles) = $this->getFakeFiles();

        $storedFileIds = UploadFilesAction::execute(
            $fakeFiles,
            $this->store,
            storageName: 'files'
        );

        $this->assertTrue($storedFileIds instanceof ActionException);
    }


    private function getFakeFiles()
    {
        $files = [
            ['name' => 'file1.txt', 'size' => 1024, 'type' => 'text/plain'],
            ['name' => 'file2.jpg', 'size' => 2048, 'type' => 'image/jpeg'],
        ];

        // Making fake files.
        $fileFactory = new FileFactory;
        foreach ($files as $file) {
            // $content = Str::random($file['size']);
            $fakeFiles[] = $fileFactory->create(
                name: $file['name'],
                kilobytes: $file['size'],
                mimeType: $file['type']
            );
        }

        return [$files, $fakeFiles];
    }
}
