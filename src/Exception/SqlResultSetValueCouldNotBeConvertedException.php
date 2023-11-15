<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Exception;

use Throwable;
use function get_debug_type;
use function sprintf;

class SqlResultSetValueCouldNotBeConvertedException extends AbstractSqlResultSetMapperException
{
    public static function create(mixed $value, Throwable $previous = null): self
    {
        return new self(sprintf(
            'Sql result set value of type "%s" could not be converted.',
            get_debug_type($value),
        ), $previous);
    }
}
