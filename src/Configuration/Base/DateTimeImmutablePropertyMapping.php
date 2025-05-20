<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration\Base;

use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use Override;

use function is_string;

/**
 * @implements PropertyMappingConverterInterface<DateTimeImmutable>
 */
final readonly class DateTimeImmutablePropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    #[Override]
    public function convert(bool|float|int|string|null $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        return new DateTimeImmutable($value);
    }
}
