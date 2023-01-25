<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration\Base;

use DateTime;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;

/**
 * @implements PropertyMappingConverterInterface<DateTime>
 */
class DateTimePropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    public function convert(?string $value): ?DateTime
    {
        if ($value === null) {
            return null;
        }

        return new DateTime($value);
    }
}
