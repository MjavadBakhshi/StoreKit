<?php

namespace Tests\Feature\FileManager;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

class UploadFileTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    #[Test]
    function can_upload_multiple_files_successfully()
    {
        
    }

    function cannot_upload_more_than_storeage_capacity_of_store()
    {

    }
}
