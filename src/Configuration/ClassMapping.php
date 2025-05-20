<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use function array_filter;
use function array_values;

/**
 * @template T of object
 */
final readonly class ClassMapping
{
    /**
     * @var list<RelationMapping>
     */
    public array $relationMappings;

    /**
     * @var list<PropertyMapping>
     */
    public array $propertyMappings;

    /**
     * @param class-string<T>                       $targetClass
     * @param list<PropertyMapping|RelationMapping> $mappings
     */
    public function __construct(
        public string $targetClass,
        public string $resultSetIdColumn,
        array $mappings,
    ) {
        $this->relationMappings = array_values(array_filter(
            $mappings,
            static fn ($mapping): bool => $mapping instanceof RelationMapping,
        ));
        $this->propertyMappings = array_values(array_filter(
            $mappings,
            static fn ($mapping): bool => $mapping instanceof PropertyMapping,
        ));
    }
}
