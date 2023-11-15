<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use function array_filter;
use function array_values;

/**
 * @template T of object
 */
class ClassMapping
{
    /**
     * @param class-string<T>                       $targetClass
     * @param list<PropertyMapping|RelationMapping> $mappings
     */
    public function __construct(
        private string $targetClass,
        private string $resultSetIdColumn,
        private array $mappings,
    ) {
    }

    /**
     * @return class-string<T>
     */
    public function getTargetClass(): string
    {
        return $this->targetClass;
    }

    public function getResultSetIdColumn(): string
    {
        return $this->resultSetIdColumn;
    }

    /**
     * @return list<RelationMapping>
     */
    public function getRelationMappings(): array
    {
        return array_values(array_filter(
            $this->mappings,
            static fn ($mapping): bool => $mapping instanceof RelationMapping,
        ));
    }

    /**
     * @return list<PropertyMapping>
     */
    public function getPropertyMappings(): array
    {
        return array_values(array_filter(
            $this->mappings,
            static fn ($mapping): bool => $mapping instanceof PropertyMapping,
        ));
    }
}
