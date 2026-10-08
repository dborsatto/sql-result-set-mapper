<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Exception;

use Exception;
use Throwable;

abstract class AbstractSqlResultSetMapperException extends Exception implements SqlResultSetMapperExceptionInterface
{
    protected function __construct(string $message, Throwable|null $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
