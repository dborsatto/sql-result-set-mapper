<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

final readonly class Address
{
    /**
     * @param list<AddressLine> $lines
     */
    public function __construct(
        public string $description,
        public array $lines,
        public ?AddressCoordinates $coordinates,
    ) {
    }
}
