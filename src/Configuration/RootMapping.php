<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use function array_filter;
use function array_values;

/**
 * @template T of object
 */
class RootMapping implements ClassMappingInterface
{
    /**
     * @var class-string
     */
    private string $targetClass;
    private string $resultSetIdColumn;
    /**
     * @var list<MappingInterface>
     */
    private array $mappings;

    /**
     * @param class-string<T>        $targetClass
     * @param list<MappingInterface> $mappings
     */
    public function __construct(string $targetClass, string $resultSetIdColumn, array $mappings)
    {
        $this->targetClass = $targetClass;
        $this->resultSetIdColumn = $resultSetIdColumn;
        $this->mappings = $mappings;
    }

    public function getTargetClass(): string
    {
        return $this->targetClass;
    }

    public function getResultSetIdColumn(): string
    {
        return $this->resultSetIdColumn;
    }

    public function getRelationMappings(): array
    {
        return array_values(array_filter(
            $this->mappings,
            static fn (MappingInterface $mapping): bool => $mapping instanceof RelationMapping,
        ));
    }

    public function getPropertyMappings(): array
    {
        return array_values(array_filter(
            $this->mappings,
            static fn (MappingInterface $mapping): bool => $mapping instanceof PropertyMapping,
        ));
    }
}
