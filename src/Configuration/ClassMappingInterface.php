<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

interface ClassMappingInterface extends MappingInterface
{
    /**
     * @return class-string
     */
    public function getTargetClass(): string;

    public function getResultSetIdColumn(): string;

    /**
     * @return list<RelationMapping>
     */
    public function getRelationMappings(): array;

    /**
     * @return list<PropertyMapping>
     */
    public function getPropertyMappings(): array;
}
