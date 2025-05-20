<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

final readonly class BlogPost
{
    public function __construct(public string $title, public string $body)
    {
    }
}
