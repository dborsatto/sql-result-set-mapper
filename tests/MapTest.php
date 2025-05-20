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

final class MapTest extends TestCase
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
                static fn (bool|float|int|string|null $value): ?Email => is_string($value) ? new Email($value) : null,
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

        $this->assertSame(stdClass::class, $mapping->targetClass);
        $this->assertSame('idColumn', $mapping->resultSetIdColumn);

        $this->assertCount(4, $mapping->propertyMappings);

        $propertyMapping = $mapping->propertyMappings[0];
        $this->assertSame('objectProperty', $propertyMapping->objectProperty);
        $this->assertSame('propertyColumn', $propertyMapping->resultSetColumn);
        $this->assertNotInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);

        $propertyMapping = $mapping->propertyMappings[1];
        $this->assertSame('objectPropertyWithConversion', $propertyMapping->objectProperty);
        $this->assertSame('propertyColumnWithConversion', $propertyMapping->resultSetColumn);
        $this->assertInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);
        /** @var PropertyMappingConverterInterface $propertyMapping */
        $this->assertNull($propertyMapping->convert(null));
        $this->assertEquals(new Email('email@example.com'), $propertyMapping->convert('email@example.com'));

        $propertyMapping = $mapping->propertyMappings[2];
        $this->assertSame('datetimeImmutableProperty', $propertyMapping->objectProperty);
        $this->assertSame('datetimeImmutableColumn', $propertyMapping->resultSetColumn);
        $this->assertInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);
        /** @var PropertyMappingConverterInterface $propertyMapping */
        $this->assertNull($propertyMapping->convert(null));
        $this->assertEquals(new DateTimeImmutable('2022-03-31 09:45:10'), $propertyMapping->convert('2022-03-31 09:45:10'));

        $propertyMapping = $mapping->propertyMappings[3];
        $this->assertSame('datetimeProperty', $propertyMapping->objectProperty);
        $this->assertSame('datetimeColumn', $propertyMapping->resultSetColumn);
        $this->assertInstanceOf(PropertyMappingConverterInterface::class, $propertyMapping);
        /** @var PropertyMappingConverterInterface $propertyMapping */
        $this->assertNull($propertyMapping->convert(null));
        $this->assertEquals(new DateTime('2022-03-31 09:45:15'), $propertyMapping->convert('2022-03-31 09:45:15'));

        $this->assertCount(2, $mapping->relationMappings);

        $multipleRelationMapping = $mapping->relationMappings[0];
        $this->assertSame('multipleRelationProperty', $multipleRelationMapping->objectProperty);
        $multipleRelationClassMapping = $multipleRelationMapping->classMapping;
        $this->assertSame(stdClass::class, $multipleRelationClassMapping->targetClass);
        $this->assertCount(0, $multipleRelationClassMapping->relationMappings);
        $multipleRelationPropertyMappings = $multipleRelationClassMapping->propertyMappings;
        $this->assertCount(1, $multipleRelationPropertyMappings);
        $this->assertSame('multipleRelationObjectProperty', $multipleRelationPropertyMappings[0]->objectProperty);
        $this->assertSame('multipleRelationPropertyColumn', $multipleRelationPropertyMappings[0]->resultSetColumn);
        $this->assertNotInstanceOf(PropertyMappingConverterInterface::class, $multipleRelationPropertyMappings[0]);

        $singleRelationMapping = $mapping->relationMappings[1];
        $this->assertSame('singleRelationProperty', $singleRelationMapping->objectProperty);
        $singleRelationClassMapping = $singleRelationMapping->classMapping;
        $this->assertSame(stdClass::class, $singleRelationClassMapping->targetClass);
        $this->assertCount(0, $singleRelationClassMapping->relationMappings);
        $singleRelationPropertyMappings = $singleRelationClassMapping->propertyMappings;
        $this->assertCount(1, $singleRelationPropertyMappings);
        $this->assertSame('singleRelationObjectProperty', $singleRelationPropertyMappings[0]->objectProperty);
        $this->assertSame('singleRelationPropertyColumn', $singleRelationPropertyMappings[0]->resultSetColumn);
        $this->assertNotInstanceOf(PropertyMappingConverterInterface::class, $singleRelationPropertyMappings[0]);
    }
}
