<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper;

use DBorsatto\SqlResultSetMapper\Configuration\RootMapping;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredColumnException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException;
use DBorsatto\SqlResultSetMapper\Hydrator\HydratorInterface;
use DBorsatto\SqlResultSetMapper\Hydrator\LaminasHydrator;

class Mapper
{
    private HydratorInterface $hydrator;

    public function __construct(HydratorInterface $hydrator = null)
    {
        $this->hydrator = $hydrator ?? new LaminasHydrator();
    }

    /**
     * @template T of object
     *
     * @param RootMapping<T>                             $rootMapping
     * @param list<array<string, string|int|float|null>> $rows
     *
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredColumnException
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException
     *
     * @return list<T>
     */
    public function map(RootMapping $rootMapping, array $rows): array
    {
        $normalizer = new Normalizer($rootMapping);
        $items = $normalizer->normalize($rows);

        return $this->hydrator->hydrate($rootMapping, $items);
    }
}
