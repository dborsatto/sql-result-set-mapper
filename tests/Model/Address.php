<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class Address
{
    private string $description;
    /**
     * @var list<AddressLine>
     */
    private array $lines;
    private ?AddressCoordinates $coordinates;

    /**
     * @param list<AddressLine> $lines
     */
    public function __construct(string $description, array $lines, ?AddressCoordinates $coordinates)
    {
        $this->description = $description;
        $this->lines = $lines;
        $this->coordinates = $coordinates;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function getCoordinates(): ?AddressCoordinates
    {
        return $this->coordinates;
    }
}
