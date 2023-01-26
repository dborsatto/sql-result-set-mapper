<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeHydratedException;

interface HydratorInterface
{
    /**
     * @template T of object
     *
     * @param ClassMapping<T> $classMapping
     * @param list<array>     $items
     *
     * @throws SqlResultSetCouldNotBeHydratedException
     *
     * @return list<T>
     */
    public function hydrate(ClassMapping $classMapping, array $items): array;
}
