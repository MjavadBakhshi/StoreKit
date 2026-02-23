<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Domain\Store\Actions\InsertStoreAction;
use Domain\Store\DataTransferObjects\StoreFormData;

class StoreController extends Controller
{
    function store(StoreFormData $data)
    {
        $result = InsertStoreAction::execute($data, Auth::user());

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(data: $result->toArray());
    }
}
