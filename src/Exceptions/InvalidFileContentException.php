<?php
/**
 * Created by PhpStorm.
 * User: Dasun Dissanayake
 * Date: 2018-10-28
 * Time: 1:48 AM.
 */

namespace Mife\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidFileContentException.
 */
class InvalidFileContentException extends Exception
{
    /**
     * InvalidFileContentException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = 'Invalid file content in generated token file. Please check the token file content.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
