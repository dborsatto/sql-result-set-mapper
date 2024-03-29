<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

class RelationMapping
{
    private function __construct(
        private string $objectProperty,
        private bool $isMultiple,
        private ClassMapping $classMapping,
    ) {
    }

    public static function single(string $objectProperty, ClassMapping $classMapping): self
    {
        return new self($objectProperty, false, $classMapping);
    }

    public static function multiple(string $objectProperty, ClassMapping $classMapping): self
    {
        return new self($objectProperty, true, $classMapping);
    }

    public function getObjectProperty(): string
    {
        return $this->objectProperty;
    }

    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }

    public function getClassMapping(): ClassMapping
    {
        return $this->classMapping;
    }
}
