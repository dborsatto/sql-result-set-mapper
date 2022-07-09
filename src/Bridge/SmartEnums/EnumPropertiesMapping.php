<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Bridge\SmartEnums;

use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\EnumListConverter\EnumListConverterInterface;
use DBorsatto\SmartEnums\EnumListConverter\JsonEnumListConverter;
use DBorsatto\SmartEnums\EnumListConverter\SerializedArrayEnumListConverter;
use DBorsatto\SmartEnums\EnumListConverter\SymbolSeparatedValuesEnumListConverter;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;

class EnumPropertiesMapping extends PropertyMapping
{
    /**
     * @param class-string<EnumInterface> $enumClass
     */
    private function __construct(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
        EnumListConverterInterface $enumListConverter
    ) {
        parent::__construct(
            $objectProperty,
            $resultSetColumn,
            static function (?string $value) use ($enumClass, $enumListConverter): ?array {
                if ($value === null) {
                    return null;
                }

                return $enumListConverter->convertFromStringToEnumList($enumClass, $value);
            },
        );
    }

    /**
     * @param class-string<EnumInterface> $enumClass
     */
    public static function fromSymbolSeparatedValues(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
        string $symbol = ','
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
        string $propertyKey = 'values'
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
        string $enumClass
    ): self {
        return new self(
            $objectProperty,
            $resultSetColumn,
            $enumClass,
            new SerializedArrayEnumListConverter(),
        );
    }
}
