<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

use DateTimeImmutable;

final readonly class Session
{
    public function __construct(public ?DateTimeImmutable $expiresAt)
    {
    }
}
