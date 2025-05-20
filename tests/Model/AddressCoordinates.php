<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

final readonly class AddressCoordinates
{
    public function __construct(public float $longitude, public float $latitude)
    {
    }
}
