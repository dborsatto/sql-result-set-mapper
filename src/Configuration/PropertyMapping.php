<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use Closure;

class PropertyMapping implements MappingInterface
{
    private string $objectProperty;
    private string $resultSetColumn;
    private ?Closure $conversionClosure;

    public function __construct(string $objectProperty, string $resultSetColumn, Closure $conversionClosure = null)
    {
        $this->objectProperty = $objectProperty;
        $this->resultSetColumn = $resultSetColumn;
        $this->conversionClosure = $conversionClosure;
    }

    public function getObjectProperty(): string
    {
        return $this->objectProperty;
    }

    public function getResultSetColumn(): string
    {
        return $this->resultSetColumn;
    }

    public function getConversionClosure(): ?Closure
    {
        return $this->conversionClosure;
    }
}
