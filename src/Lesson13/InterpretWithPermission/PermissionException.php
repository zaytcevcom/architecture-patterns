<?php

declare(strict_types=1);

namespace App\Lesson13\InterpretWithPermission;

use Exception;
use Throwable;

class PermissionException extends Exception
{
    public function __construct(string $message = 'Access denied', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
