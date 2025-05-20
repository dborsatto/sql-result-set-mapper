<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeHydratedException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException;
use DBorsatto\SqlResultSetMapper\Hydrator\HydratorInterface;
use DBorsatto\SqlResultSetMapper\Hydrator\LaminasHydrator;

final class Mapper
{
    private HydratorInterface $hydrator;

    public function __construct(?HydratorInterface $hydrator = null)
    {
        $this->hydrator = $hydrator ?? new LaminasHydrator();
    }

    /**
     * @template T of object
     *
     * @param ClassMapping<T>                      $classMapping
     * @param list<array<string, string|int|null>> $rows
     *
     * @throws SqlResultSetCouldNotBeHydratedException
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException
     *
     * @return list<T>
     */
    public function map(ClassMapping $classMapping, array $rows): array
    {
        $normalizer = new Normalizer($classMapping);
        $items = $normalizer->normalize($rows);

        return $this->hydrator->hydrate($classMapping, $items);
    }
}
