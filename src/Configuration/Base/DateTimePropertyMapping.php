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
final readonly class DateTimePropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    public function convert(bool|float|int|string|null $value): DateTime|null
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
