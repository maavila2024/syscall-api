<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class UserAlreadyVerifiedException extends Exception
{
    use RenderToJson;

    protected $message = 'Token is not valid.';
    protected $code = 400;
}
