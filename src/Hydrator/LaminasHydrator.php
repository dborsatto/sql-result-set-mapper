<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMappingInterface;
use DBorsatto\SqlResultSetMapper\Configuration\RootMapping;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\ClosureStrategy;
use Laminas\Hydrator\Strategy\CollectionStrategy;
use ReflectionClass;

class LaminasHydrator implements HydratorInterface
{
    /**
     * @template T of object
     *
     * @param RootMapping<T> $rootMapping
     * @param list<array>    $items
     *
     * @return list<T>
     */
    public function hydrate(RootMapping $rootMapping, array $items): array
    {
        $hydrator = new ReflectionHydrator();
        $this->configureHydrator($hydrator, $rootMapping);

        $reflectionClass = new ReflectionClass($rootMapping->getTargetClass());
        $convertedItems = [];
        foreach ($items as $item) {
            /** @var T $object */
            $object = $reflectionClass->newInstanceWithoutConstructor();
            $hydrator->hydrate($item, $object);
            $convertedItems[] = $object;
        }

        return $convertedItems;
    }

    private function configureHydrator(ReflectionHydrator $hydrator, ClassMappingInterface $classMapping): void
    {
        foreach ($classMapping->getPropertyMappings() as $propertyMapping) {
            $callable = $propertyMapping->getConversionClosure();
            if ($callable !== null) {
                $hydrator->addStrategy(
                    $propertyMapping->getObjectProperty(),
                    new ClosureStrategy(null, $callable),
                );
            }
        }

        foreach ($classMapping->getRelationMappings() as $relationMapping) {
            $relationHydrator = new ReflectionHydrator();
            $hydrator->addStrategy(
                $relationMapping->getObjectProperty(),
                new CollectionStrategy($relationHydrator, $relationMapping->getTargetClass()),
            );

            $this->configureHydrator($relationHydrator, $relationMapping);
        }
    }
}
