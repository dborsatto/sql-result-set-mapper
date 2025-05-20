<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

readonly class PropertyMapping
{
    public function __construct(public string $objectProperty, public string $resultSetColumn)
    {
    }
}
