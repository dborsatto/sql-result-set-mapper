<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Exception;

use function sprintf;

final class SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException extends AbstractSqlResultSetMapperException
{
    public static function create(string $columnName): self
    {
        return new self(sprintf(
            'Sql result set could not be normalized because it is missing configured property column "%s".',
            $columnName,
        ));
    }
}
