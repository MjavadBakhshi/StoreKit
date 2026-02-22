<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;

use Domain\Account\Actions\LoginToAccountAction;
use Domain\Account\DataTransferObjects\LoginFormData;

class AuthController extends Controller
{
    function login(LoginFormData $data) 
    {
        $result = LoginToAccountAction::execute($data);

        // When login is failed it returns error response.
        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(
            data: $result
        );
    }
}
