<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $customData;
    protected $statusCode;

    public function __construct($message = "", $statusCode = 500, Exception $previous = null, $customData = null)
    {
        parent::__construct($message, $statusCode, $previous);
        $this->customData = $customData;
        $this->statusCode = $statusCode;
    }

    public function getCustomData()
    {
        return $this->customData;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
