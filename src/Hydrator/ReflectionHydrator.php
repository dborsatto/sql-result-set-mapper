<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Configuration\RelationMapping;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeHydratedException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Throwable;

use function array_key_exists;
use function array_keys;
use function is_array;
use function is_scalar;

final class ReflectionHydrator implements HydratorInterface
{
    public function hydrate(ClassMapping $classMapping, array $items): array
    {
        try {
            return $this->hydrateItems($classMapping, $items);
        } catch (SqlResultSetValueCouldNotBeConvertedException $exception) {
            throw SqlResultSetCouldNotBeHydratedException::create($exception);
        }
    }

    /**
     * @template T of object
     *
     * @param ClassMapping<T> $classMapping
     * @param list<array>     $items
     *
     * @throws SqlResultSetCouldNotBeHydratedException
     * @throws SqlResultSetValueCouldNotBeConvertedException
     *
     * @return list<T>
     */
    private function hydrateItems(ClassMapping $classMapping, array $items): array
    {
        try {
            $reflectionClass = new ReflectionClass($classMapping->targetClass);
        } catch (ReflectionException $exception) {
            throw SqlResultSetCouldNotBeHydratedException::create($exception);
        }

        $reflectionProperties = [];
        // Only properties visible from the class itself are returned,
        // so private properties of parent classes are not included.
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!$reflectionProperty->isStatic()) {
                $reflectionProperties[$reflectionProperty->getName()] = $reflectionProperty;
            }
        }

        $objects = [];
        foreach ($items as $item) {
            $objects[] = $this->hydrateItem($classMapping, $reflectionClass, $reflectionProperties, $item);
        }

        return $objects;
    }

    /**
     * @template T of object
     *
     * @param ClassMapping<T>                   $classMapping
     * @param ReflectionClass<T>                $reflectionClass
     * @param array<string, ReflectionProperty> $reflectionProperties
     *
     * @throws SqlResultSetCouldNotBeHydratedException
     * @throws SqlResultSetValueCouldNotBeConvertedException
     *
     * @return T
     */
    private function hydrateItem(
        ClassMapping $classMapping,
        ReflectionClass $reflectionClass,
        array $reflectionProperties,
        array $item,
    ): object {
        try {
            $object = $reflectionClass->newInstanceWithoutConstructor();
        } catch (ReflectionException $exception) {
            throw SqlResultSetCouldNotBeHydratedException::create($exception);
        }

        // Values for properties that the class does not declare are skipped.
        foreach ($classMapping->propertyMappings as $propertyMapping) {
            $property = $propertyMapping->objectProperty;
            if (!array_key_exists($property, $item) || !array_key_exists($property, $reflectionProperties)) {
                continue;
            }

            $reflectionProperties[$property]->setValue(
                $object,
                $propertyMapping instanceof PropertyMappingConverterInterface
                    ? $this->convert($propertyMapping, $item[$property])
                    : $item[$property],
            );
        }

        foreach ($classMapping->relationMappings as $relationMapping) {
            $property = $relationMapping->objectProperty;
            if (!array_key_exists($property, $item) || !array_key_exists($property, $reflectionProperties)) {
                continue;
            }

            $reflectionProperties[$property]->setValue(
                $object,
                $this->hydrateRelation($relationMapping, $item[$property]),
            );
        }

        return $object;
    }

    /**
     * @throws SqlResultSetCouldNotBeHydratedException
     * @throws SqlResultSetValueCouldNotBeConvertedException
     *
     * @return list<object>|object|null
     */
    private function hydrateRelation(RelationMapping $relationMapping, mixed $value): array|object|null
    {
        $classMapping = $relationMapping->classMapping;

        if (!$relationMapping->isMultiple && $value === null) {
            return null;
        }

        if (!is_array($value)) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        if (!$relationMapping->isMultiple) {
            return $this->hydrateItems($classMapping, [$value])[0];
        }

        $items = [];
        foreach (array_keys($value) as $key) {
            if (!is_array($value[$key])) {
                throw SqlResultSetValueCouldNotBeConvertedException::create($value);
            }

            $items[] = $value[$key];
        }

        return $this->hydrateItems($classMapping, $items);
    }

    /**
     * @throws SqlResultSetValueCouldNotBeConvertedException
     */
    private function convert(PropertyMappingConverterInterface $converter, mixed $value): mixed
    {
        if ($value !== null && !is_scalar($value)) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        try {
            return $converter->convert($value);
        } catch (SqlResultSetValueCouldNotBeConvertedException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value, $exception);
        }
    }
}
