<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

class PropertyMapping
{
    private string $objectProperty;
    private string $resultSetColumn;

    public function __construct(string $objectProperty, string $resultSetColumn)
    {
        $this->objectProperty = $objectProperty;
        $this->resultSetColumn = $resultSetColumn;
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
