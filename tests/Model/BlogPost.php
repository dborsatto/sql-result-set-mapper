<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class BlogPost
{
    public function __construct(private string $title, private string $body)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
