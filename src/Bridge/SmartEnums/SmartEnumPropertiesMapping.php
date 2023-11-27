<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Bridge\SmartEnums;

use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\EnumListConverter\EnumListConverterInterface;
use DBorsatto\SmartEnums\EnumListConverter\JsonEnumListConverter;
use DBorsatto\SmartEnums\EnumListConverter\SerializedArrayEnumListConverter;
use DBorsatto\SmartEnums\EnumListConverter\SymbolSeparatedValuesEnumListConverter;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use function is_string;

/**
 * @implements PropertyMappingConverterInterface<list<EnumInterface>>
 */
class SmartEnumPropertiesMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @param class-string<EnumInterface> $enumClass
     */
    private function __construct(
        string $objectProperty,
        string $resultSetColumn,
        private string $enumClass,
        private EnumListConverterInterface $enumListConverter,
    ) {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    /**
     * @param class-string<EnumInterface> $enumClass
     * @param non-empty-string            $symbol
     */
    public static function fromSymbolSeparatedValues(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
        string $symbol = ',',
    ): self {
        return new self(
            $objectProperty,
            $resultSetColumn,
            $enumClass,
            new SymbolSeparatedValuesEnumListConverter($symbol),
        );
    }

    /**
     * @param class-string<EnumInterface> $enumClass
     */
    public static function fromJsonList(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
        string $propertyKey = 'values',
    ): self {
        return new self(
            $objectProperty,
            $resultSetColumn,
            $enumClass,
            new JsonEnumListConverter($propertyKey),
        );
    }

    /**
     * @param class-string<EnumInterface> $enumClass
     */
    public static function fromSerializedArray(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
    ): self {
        return new self(
            $objectProperty,
            $resultSetColumn,
            $enumClass,
            new SerializedArrayEnumListConverter(),
        );
    }

    public function convert(null|bool|float|int|string $value): null|array
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value) || $value === '') {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        return $this->enumListConverter->convertFromStringToEnumList($this->enumClass, $value);
    }
}
