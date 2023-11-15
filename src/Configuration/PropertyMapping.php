<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

class PropertyMapping
{
    public function __construct(private string $objectProperty, private string $resultSetColumn)
    {
    }

    public function getObjectProperty(): string
    {
        return $this->objectProperty;
    }

    public function getResultSetColumn(): string
    {
        return $this->resultSetColumn;
    }
}
