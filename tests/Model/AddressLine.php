<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class AddressLine
{
    public function __construct(private string $line)
    {
    }

    public function getLine(): string
    {
        return $this->line;
    }
}
