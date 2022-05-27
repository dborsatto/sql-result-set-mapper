<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class AddressLine
{
    private string $line;

    public function __construct(string $line)
    {
        $this->line = $line;
    }

    public function getLine(): string
    {
        return $this->line;
    }
}
