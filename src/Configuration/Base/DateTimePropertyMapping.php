<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration\Base;

use DateTime;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use function is_string;

/**
 * @implements PropertyMappingConverterInterface<DateTime>
 */
class DateTimePropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    public function convert(null|bool|float|int|string $value): null|DateTime
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        return new DateTime($value);
    }
}
