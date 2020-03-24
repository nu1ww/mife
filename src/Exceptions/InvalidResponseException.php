<?php

namespace Mife\Exceptions;

use Exception;
use Throwable;

class InvalidResponseException extends Exception
{
    /**
     * InvalidResponseException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = 'Invalid response format. Please check the token_generate.log file.',
        $code = 6,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
