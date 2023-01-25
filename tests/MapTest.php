<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DateTime;
use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Tests\Model\Email;
use PHPUnit\Framework\TestCase;
use stdClass;
use function is_string;

class MapTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMappings(): void
    {
        $mapping = Map::create(stdClass::class, 'idColumn', [
            Map::property('objectProperty', 'propertyColumn'),
            Map::propertyConversion(
                'objectPropertyWithConversion',
                'propertyColumnWithConversion',
                static fn (?string $value): ?Email => is_string($value) ? new Email($value) : null,
            ),
            Map::datetimeImmutableProperty('datetimeImmutableProperty', 'datetimeImmutableColumn'),
            Map::datetimeProperty('datetimeProperty', 'datetimeColumn'),
            Map::multipleRelation('multipleRelationProperty', stdClass::class, 'multipleRelationColumnId', [
                Map::property('multipleRelationObjectProperty', 'multipleRelationPropertyColumn'),
            ]),
            Map::singleRelation('singleRelationProperty', stdClass::class, 'singleRelationColumnId', [
                Map::property('singleRelationObjectProperty', 'singleRelationPropertyColumn'),
            ]),
        ]);

        $this->assertSame(stdClass::class, $mapping->getTargetClass());
        $this->assertSame('idColumn', $mapping->getResultSetIdColumn());

        $propertyMappings = $mapping->getPropertyMappings();
        $this->assertCount(4, $propertyMappings);

        $propertyMapping = $propertyMappings[0];
        $this->assertSame('objectProperty', $propertyMapping->getObjectProperty());
        $this->assertSame('propertyColumn', $propertyMapping->getResultSetColumn());
        $this->assertNotInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);

        $propertyMapping = $propertyMappings[1];
        $this->assertSame('objectPropertyWithConversion', $propertyMapping->getObjectProperty());
        $this->assertSame('propertyColumnWithConversion', $propertyMapping->getResultSetColumn());
        $this->assertInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);
        /** @var PropertyMappingConverterInterface $propertyMapping */
        $this->assertNull($propertyMapping->convert(null));
        $this->assertEquals(new Email('email@example.com'), $propertyMapping->convert('email@example.com'));

        $propertyMapping = $propertyMappings[2];
        $this->assertSame('datetimeImmutableProperty', $propertyMapping->getObjectProperty());
        $this->assertSame('datetimeImmutableColumn', $propertyMapping->getResultSetColumn());
        $this->assertInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);
        /** @var PropertyMappingConverterInterface $propertyMapping */
        $this->assertNull($propertyMapping->convert(null));
        $this->assertEquals(new DateTimeImmutable('2022-03-31 09:45:10'), $propertyMapping->convert('2022-03-31 09:45:10'));

        $propertyMapping = $propertyMappings[3];
        $this->assertSame('datetimeProperty', $propertyMapping->getObjectProperty());
        $this->assertSame('datetimeColumn', $propertyMapping->getResultSetColumn());
        $this->assertInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);
        /** @var PropertyMappingConverterInterface $propertyMapping */
        $this->assertNull($propertyMapping->convert(null));
        $this->assertEquals(new DateTime('2022-03-31 09:45:15'), $propertyMapping->convert('2022-03-31 09:45:15'));

        $relationMappings = $mapping->getRelationMappings();
        $this->assertCount(2, $relationMappings);

        $multipleRelationMapping = $relationMappings[0];
        $this->assertSame('multipleRelationProperty', $multipleRelationMapping->getObjectProperty());
        $multipleRelationClassMapping = $multipleRelationMapping->getClassMapping();
        $this->assertSame(stdClass::class, $multipleRelationClassMapping->getTargetClass());
        $this->assertCount(0, $multipleRelationClassMapping->getRelationMappings());
        $multipleRelationPropertyMappings = $multipleRelationClassMapping->getPropertyMappings();
        $this->assertCount(1, $multipleRelationPropertyMappings);
        $this->assertSame('multipleRelationObjectProperty', $multipleRelationPropertyMappings[0]->getObjectProperty());
        $this->assertSame('multipleRelationPropertyColumn', $multipleRelationPropertyMappings[0]->getResultSetColumn());
        $this->assertNotInstanceOf(PropertyMappingConverterInterface::class, $multipleRelationPropertyMappings[0]);

        $singleRelationMapping = $relationMappings[1];
        $this->assertSame('singleRelationProperty', $singleRelationMapping->getObjectProperty());
        $singleRelationClassMapping = $singleRelationMapping->getClassMapping();
        $this->assertSame(stdClass::class, $singleRelationClassMapping->getTargetClass());
        $this->assertCount(0, $singleRelationClassMapping->getRelationMappings());
        $singleRelationPropertyMappings = $singleRelationClassMapping->getPropertyMappings();
        $this->assertCount(1, $singleRelationPropertyMappings);
        $this->assertSame('singleRelationObjectProperty', $singleRelationPropertyMappings[0]->getObjectProperty());
        $this->assertSame('singleRelationPropertyColumn', $singleRelationPropertyMappings[0]->getResultSetColumn());
        $this->assertNotInstanceOf(PropertyMappingConverterInterface::class, $singleRelationPropertyMappings[0]);
    }
}
