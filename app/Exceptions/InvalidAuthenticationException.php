<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

/* The `class InvalidAuthenticationException extends Exception` statement in PHP is creating a new
custom exception class named `InvalidAuthenticationException` that extends the built-in `Exception`
class. This custom exception class is used to handle specific types of exceptions related to invalid
authentication scenarios in your application. */
class InvalidAuthenticationException extends Exception
{
    use RenderToJson;

    protected $message = 'Your credentials don\'t match.';
    protected $code = 400;
}
