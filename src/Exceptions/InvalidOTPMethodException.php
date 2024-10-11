<?php

namespace Mkdev\LaravelAdvancedOTP\Exceptions;
use Exception;

class InvalidOTPMethodException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
