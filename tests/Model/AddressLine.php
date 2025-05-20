<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

final readonly class AddressLine
{
    public function __construct(public string $line)
    {
    }
}
