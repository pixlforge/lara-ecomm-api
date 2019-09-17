<?php

namespace App\Exceptions;

use Exception;

class PaymentFailedException extends Exception
{
    /**
     * The exception property.
     *
     * @var Exception $exception
     */
    protected $exception;
    
    /**
     * PaymentFailedException constructor.
     *
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }
}
