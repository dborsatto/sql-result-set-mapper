<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration\Base;

use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;

/**
 * @implements PropertyMappingConverterInterface<DateTimeImmutable>
 */
class DateTimeImmutablePropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    public function convert(?string $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        return new DateTimeImmutable($value);
    }
}
