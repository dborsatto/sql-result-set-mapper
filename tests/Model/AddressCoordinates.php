<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class AddressCoordinates
{
    public function __construct(private float $longitude, private float $latitude)
    {
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }
}
