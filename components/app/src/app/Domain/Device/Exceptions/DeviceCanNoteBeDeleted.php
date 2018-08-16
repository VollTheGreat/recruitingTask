<?php
/**
 * Created by PhpStorm.
 * User: vollthegreat
 * Date: 16.08.18
 * Time: 19:57
 */

namespace App\Domain\Device\Exceptions;

use Exception;

class DeviceCanNoteBeDeleted extends Exception
{
    /**
     * DeviceCreationFailed constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}