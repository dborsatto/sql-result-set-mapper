<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

use DateTimeImmutable;

class Session
{
    private ?DateTimeImmutable $expiresAt;

    public function __construct(?DateTimeImmutable $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
