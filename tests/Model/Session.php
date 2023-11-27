<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

use DateTimeImmutable;

class Session
{
    public function __construct(private null|DateTimeImmutable $expiresAt)
    {
    }

    public function getExpiresAt(): null|DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
