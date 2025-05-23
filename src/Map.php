<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper;

use BackedEnum;
use Closure;
use DBorsatto\SqlResultSetMapper\Configuration\Base\DateTimeImmutablePropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\Base\DateTimePropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\Base\EnumPropertiesMapping;
use DBorsatto\SqlResultSetMapper\Configuration\Base\EnumPropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Configuration\ClosurePropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\RelationMapping;

final class Map
{
    /**
     * @template T of object
     *
     * @param class-string<T>                       $targetClass
     * @param list<RelationMapping|PropertyMapping> $mappings
     *
     * @return ClassMapping<T>
     */
    public static function create(
        string $targetClass,
        string $resultSetIdColumn,
        array $mappings,
    ): ClassMapping {
        return new ClassMapping($targetClass, $resultSetIdColumn, $mappings);
    }

    /**
     * @param class-string                          $targetClass
     * @param list<RelationMapping|PropertyMapping> $mappings
     */
    public static function multipleRelation(
        string $objectProperty,
        string $targetClass,
        string $resultSetIdColumn,
        array $mappings,
    ): RelationMapping {
        return RelationMapping::multiple(
            $objectProperty,
            new ClassMapping($targetClass, $resultSetIdColumn, $mappings),
        );
    }

    /**
     * @param class-string                          $targetClass
     * @param list<RelationMapping|PropertyMapping> $mappings
     */
    public static function singleRelation(
        string $objectProperty,
        string $targetClass,
        string $resultSetIdColumn,
        array $mappings,
    ): RelationMapping {
        return RelationMapping::single(
            $objectProperty,
            new ClassMapping($targetClass, $resultSetIdColumn, $mappings),
        );
    }

    public static function property(string $objectProperty, string $resultSetColumn): PropertyMapping
    {
        return new PropertyMapping($objectProperty, $resultSetColumn);
    }

    /**
     * @template T
     *
     * @param Closure(bool|float|int|string|null): T $closure
     */
    public static function propertyConversion(
        string $objectProperty,
        string $resultSetColumn,
        Closure $closure,
    ): ClosurePropertyMapping {
        return new ClosurePropertyMapping($objectProperty, $resultSetColumn, $closure);
    }

    public static function datetimeImmutableProperty(
        string $objectProperty,
        string $resultSetColumn,
    ): DateTimeImmutablePropertyMapping {
        return new DateTimeImmutablePropertyMapping($objectProperty, $resultSetColumn);
    }

    public static function datetimeProperty(
        string $objectProperty,
        string $resultSetColumn,
    ): DateTimePropertyMapping {
        return new DateTimePropertyMapping($objectProperty, $resultSetColumn);
    }

    /**
     * @param class-string<BackedEnum> $enumClass
     */
    public static function enumProperty(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
    ): EnumPropertyMapping {
        return new EnumPropertyMapping($objectProperty, $resultSetColumn, $enumClass);
    }

    /**
     * @param class-string<BackedEnum> $enumClass
     * @param non-empty-string         $symbol
     */
    public static function smartEnumPropertiesSymbolSeparated(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
        string $symbol = ',',
    ): EnumPropertiesMapping {
        return EnumPropertiesMapping::fromSymbolSeparatedValues(
            $objectProperty,
            $resultSetColumn,
            $enumClass,
            $symbol,
        );
    }

    /**
     * @param class-string<BackedEnum> $enumClass
     */
    public static function smartEnumPropertiesJson(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
    ): EnumPropertiesMapping {
        return EnumPropertiesMapping::fromJsonList($objectProperty, $resultSetColumn, $enumClass);
    }
}
