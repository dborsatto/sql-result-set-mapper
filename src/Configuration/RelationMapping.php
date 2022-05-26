<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use function array_filter;
use function array_values;

class RelationMapping implements ClassMappingInterface
{
    private string $objectProperty;

    /**
     * @var class-string
     */
    private string $targetClass;
    private string $resultSetIdColumn;
    /**
     * @var list<MappingInterface>
     */
    private array $mappings;
    private bool $isMultiple;

    /**
     * @param class-string           $targetClass
     * @param list<MappingInterface> $mappings
     */
    public function __construct(
        string $objectProperty,
        string $targetClass,
        string $resultSetIdColumn,
        array $mappings,
        bool $isMultiple = true
    ) {
        $this->objectProperty = $objectProperty;
        $this->targetClass = $targetClass;
        $this->resultSetIdColumn = $resultSetIdColumn;
        $this->mappings = $mappings;
        $this->isMultiple = $isMultiple;
    }

    /**
     * @param class-string           $targetClass
     * @param list<MappingInterface> $mappings
     */
    public static function single(
        string $objectProperty,
        string $targetClass,
        string $resultSetIdColumn,
        array $mappings
    ): self {
        return new self($objectProperty, $targetClass, $resultSetIdColumn, $mappings, false);
    }

    /**
     * @param class-string           $targetClass
     * @param list<MappingInterface> $mappings
     */
    public static function multiple(
        string $objectProperty,
        string $targetClass,
        string $resultSetIdColumn,
        array $mappings
    ): self {
        return new self($objectProperty, $targetClass, $resultSetIdColumn, $mappings, true);
    }

    public function getObjectProperty(): string
    {
        return $this->objectProperty;
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
            static fn (MappingInterface $mapping): bool => $mapping instanceof self,
        ));
    }

    public function getPropertyMappings(): array
    {
        return array_values(array_filter(
            $this->mappings,
            static fn (MappingInterface $mapping): bool => $mapping instanceof PropertyMapping,
        ));
    }

    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }
}
