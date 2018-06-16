<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Exception;

class EmptyPathException extends \UnexpectedValueException
{
    public function __construct(string $message = 'Upload has an empty path.', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
