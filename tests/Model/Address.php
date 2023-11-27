<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class Address
{
    /**
     * @param list<AddressLine> $lines
     */
    public function __construct(
        private string $description,
        private array $lines,
        private null|AddressCoordinates $coordinates,
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function getCoordinates(): null|AddressCoordinates
    {
        return $this->coordinates;
    }
}
