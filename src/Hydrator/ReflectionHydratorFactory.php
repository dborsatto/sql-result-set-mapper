<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use Laminas\Hydrator\HydratorInterface;
use Laminas\Hydrator\ReflectionHydrator;

class ReflectionHydratorFactory implements HydratorFactoryInterface
{
    public function create(): HydratorInterface
    {
        return new ReflectionHydrator();
    }
}
