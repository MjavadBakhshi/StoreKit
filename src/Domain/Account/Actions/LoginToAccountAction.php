<?php

namespace Domain\Account\Actions;

use Illuminate\Support\Facades\Auth;

use Domain\Account\DataTransferObjects\LoginFormData;
use Domain\Shared\Exceptions\ActionException;

class LoginToAccountAction 
{
    static function execute(LoginFormData $data) :ActionException|array
    {
        try {

            $credentials = $data->toArray();            

            throw_if(
                !Auth::attempt($credentials),
                new ActionException("The credentials are incorrect.")
            );

            // Get authenticated user
            $user = Auth::user();

            // Create token for API auth
            $token = $user->createToken('api-token')->plainTextToken;


            return [
                'user' => $user,
                'token' => $token
            ];
        }
        catch(ActionException $e)
        {
            return $e;
        }
    }

}