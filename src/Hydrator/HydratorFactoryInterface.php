<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use Laminas\Hydrator\HydratorInterface;

interface HydratorFactoryInterface
{
    public function create(): HydratorInterface;
}
