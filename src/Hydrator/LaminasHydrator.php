<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeHydratedException;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\CollectionStrategy;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Override;
use ReflectionClass;
use Throwable;

final class LaminasHydrator implements HydratorInterface
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
    #[Override]
    public function hydrate(ClassMapping $classMapping, array $items): array
    {
        $hydrator = new ReflectionHydrator();
        $this->configureHydrator($hydrator, $classMapping);

        try {
            $reflectionClass = new ReflectionClass($classMapping->targetClass);
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
        foreach ($classMapping->propertyMappings as $propertyMapping) {
            if ($propertyMapping instanceof PropertyMappingConverterInterface) {
                $hydrator->addStrategy(
                    $propertyMapping->objectProperty,
                    new LaminasConvertedPropertyStrategy($propertyMapping),
                );
            }
        }

        foreach ($classMapping->relationMappings as $relationMapping) {
            $relationHydrator = new ReflectionHydrator();
            $innerClassMapping = $relationMapping->classMapping;

            $strategy = $relationMapping->isMultiple
                ? new CollectionStrategy($relationHydrator, $innerClassMapping->targetClass)
                : new HydratorStrategy($relationHydrator, $innerClassMapping->targetClass);

            $hydrator->addStrategy($relationMapping->objectProperty, $strategy);

            $this->configureHydrator($relationHydrator, $innerClassMapping);
        }
    }
}
