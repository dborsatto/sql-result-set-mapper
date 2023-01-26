<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Exception;

use Throwable;

class SqlResultSetCouldNotBeHydratedException extends AbstractSqlResultSetMapperException
{
    public static function create(Throwable $previous = null): self
    {
        return new self('Sql result set could not be hydrated', $previous);
    }
}
