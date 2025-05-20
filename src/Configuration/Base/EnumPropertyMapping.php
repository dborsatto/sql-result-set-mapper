<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration\Base;

use BackedEnum;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use Override;
use ValueError;

use function is_string;

/**
 * @template T of BackedEnum
 *
 * @implements PropertyMappingConverterInterface<T>
 */
final readonly class EnumPropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @param class-string<T> $enumClass
     */
    public function __construct(string $objectProperty, string $resultSetColumn, private string $enumClass)
    {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    #[Override]
    public function convert(bool|float|int|string|null $value): ?BackedEnum
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value) || $value === '') {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        $enumClass = $this->enumClass;

        try {
            return $enumClass::from($value);
        } catch (ValueError $exception) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value, $exception);
        }
    }
}
