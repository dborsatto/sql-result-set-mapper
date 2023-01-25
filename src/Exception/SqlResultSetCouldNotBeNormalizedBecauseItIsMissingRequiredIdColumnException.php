<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Exception;

use function sprintf;

class SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException extends AbstractSqlResultSetMapperException
{
    public static function create(string $idColumn): self
    {
        return new self(sprintf(
            'Sql result set could not be normalized because it is missing required id column "%s".',
            $idColumn,
        ));
    }
}
