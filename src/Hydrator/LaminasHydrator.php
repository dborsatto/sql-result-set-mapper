<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeHydratedException;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\CollectionStrategy;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use ReflectionClass;
use Throwable;

class LaminasHydrator implements HydratorInterface
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
    public function hydrate(ClassMapping $classMapping, array $items): array
    {
        $hydrator = new ReflectionHydrator();
        $this->configureHydrator($hydrator, $classMapping);

        try {
            $reflectionClass = new ReflectionClass($classMapping->getTargetClass());
            $convertedItems = [];
            foreach ($items as $item) {
                /** @var T $object */
                $object = $reflectionClass->newInstanceWithoutConstructor();
                $hydrator->hydrate($item, $object);
                $convertedItems[] = $object;
            }

            return $convertedItems;
        } catch (Throwable $exception) {
            throw SqlResultSetCouldNotBeHydratedException::create($exception);
        }
    }

    private function configureHydrator(ReflectionHydrator $hydrator, ClassMapping $classMapping): void
    {
        foreach ($classMapping->getPropertyMappings() as $propertyMapping) {
            if ($propertyMapping instanceof PropertyMappingConverterInterface) {
                $hydrator->addStrategy(
                    $propertyMapping->getObjectProperty(),
                    new LaminasConvertedPropertyStrategy($propertyMapping),
                );
            }
        }

        foreach ($classMapping->getRelationMappings() as $relationMapping) {
            $relationHydrator = new ReflectionHydrator();
            $innerClassMapping = $relationMapping->getClassMapping();

            $strategy = $relationMapping->isMultiple()
                ? new CollectionStrategy($relationHydrator, $innerClassMapping->getTargetClass())
                : new HydratorStrategy($relationHydrator, $innerClassMapping->getTargetClass());

            $hydrator->addStrategy($relationMapping->getObjectProperty(), $strategy);

            $this->configureHydrator($relationHydrator, $innerClassMapping);
        }
    }
}
