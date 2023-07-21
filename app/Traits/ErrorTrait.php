<?php

namespace App\Traits;

use Illuminate\Http\Response;

/**
 * Error Message Trait
 *
 * This trait provides a method for generating an error response.
 * It returns a response view with the error message and error code.
 *
 * @author KyiLinLinThant
 * @created 21/06/2023
 */
trait ErrorTrait
{
    protected $errorMessage;
    protected $errorCode;

    protected function error($errorMessage, $errorCode = 500)
    {
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
