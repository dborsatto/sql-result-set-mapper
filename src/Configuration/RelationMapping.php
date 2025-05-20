<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

final readonly class RelationMapping
{
    private function __construct(
        public string $objectProperty,
        public bool $isMultiple,
        public ClassMapping $classMapping,
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
}
