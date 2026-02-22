<?php

namespace Domain\Shared\Exceptions;

use Exception;

class ActionException extends Exception
{
    // Optional: default message
    protected $message = 'Something went wrong!';
    
    // Optional: default HTTP status code
    protected $code = 400;

    public function __construct($message = null, $code = null)
    {
        parent::__construct(
            $message ?? $this->message,
            $code ?? $this->code
        );
    }
}
