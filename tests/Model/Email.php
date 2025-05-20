<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

final readonly class Email
{
    public function __construct(public string $value)
    {
    }
}
