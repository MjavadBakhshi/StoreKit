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

    // It is just a wrapper to convert type to ActionException.
    static function from(Exception $e)
    {
        return new self($e->getMessage(), $e->getCode());
    }
}
