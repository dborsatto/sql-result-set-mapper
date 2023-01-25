<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Bridge\SmartEnums;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;

/**
 * @implements PropertyMappingConverterInterface<EnumInterface>
 */
class EnumPropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @var class-string<EnumInterface>
     */
    private string $enumClass;

    /**
     * @param class-string<EnumInterface> $enumClass
     */
    public function __construct(string $objectProperty, string $resultSetColumn, string $enumClass)
    {
        parent::__construct($objectProperty, $resultSetColumn);

        $this->enumClass = $enumClass;
    }

    /**
     * @throws SmartEnumExceptionInterface
     */
    public function convert(?string $value): ?EnumInterface
    {
        if ($value === null) {
            return null;
        }

        $factory = new EnumFactory($this->enumClass);

        return $factory->fromValue($value);
    }
}
