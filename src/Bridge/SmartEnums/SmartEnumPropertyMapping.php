<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Bridge\SmartEnums;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use function is_string;

/**
 * @implements PropertyMappingConverterInterface<EnumInterface>
 */
class SmartEnumPropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @param class-string<EnumInterface> $enumClass
     */
    public function __construct(string $objectProperty, string $resultSetColumn, private string $enumClass)
    {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    public function convert(null|bool|float|int|string $value): null|EnumInterface
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value) || $value === '') {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        $factory = new EnumFactory($this->enumClass);

        return $factory->fromValue($value);
    }
}
