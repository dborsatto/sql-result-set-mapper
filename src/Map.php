<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper;

use Closure;
use DateTime;
use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Configuration\MappingInterface;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\RelationMapping;
use DBorsatto\SqlResultSetMapper\Configuration\RootMapping;
use function is_string;

class Map
{
    /**
     * @template T of object
     *
     * @param class-string<T>        $targetClass
     * @param list<MappingInterface> $mappings
     *
     * @return RootMapping<T>
     */
    public static function root(string $targetClass, string $resultSetIdColumn, array $mappings): RootMapping
    {
        return new RootMapping($targetClass, $resultSetIdColumn, $mappings);
    }

    /**
     * @param class-string           $targetClass
     * @param list<MappingInterface> $mappings
     */
    public static function relation(
        string $objectProperty,
        string $targetClass,
        string $resultSetIdColumn,
        array $mappings
    ): RelationMapping {
        return new RelationMapping($objectProperty, $targetClass, $resultSetIdColumn, $mappings);
    }

    public static function property(
        string $objectProperty,
        string $resultSetColumn,
        Closure $conversionClosure = null
    ): PropertyMapping {
        return new PropertyMapping($objectProperty, $resultSetColumn, $conversionClosure);
    }

    public static function datetimeImmutableProperty(
        string $objectProperty,
        string $resultSetColumn
    ): PropertyMapping {
        return new PropertyMapping(
            $objectProperty,
            $resultSetColumn,
            static fn (?string $value): ?DateTimeImmutable => is_string($value) ? new DateTimeImmutable($value) : null,
        );
    }

    public static function datetimeProperty(
        string $objectProperty,
        string $resultSetColumn
    ): PropertyMapping {
        return new PropertyMapping(
            $objectProperty,
            $resultSetColumn,
            static fn (?string $value): ?DateTime => is_string($value) ? new DateTime($value) : null,
        );
    }
}
