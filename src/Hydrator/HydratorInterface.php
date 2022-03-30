<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\RootMapping;

interface HydratorInterface
{
    /**
     * @template T of object
     *
     * @param RootMapping<T> $rootMapping
     * @param list<array>    $items
     *
     * @return list<T>
     */
    public function hydrate(RootMapping $rootMapping, array $items): array;
}
