<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DateTime;
use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Tests\Model\Email;
use PHPUnit\Framework\TestCase;
use stdClass;
use function is_string;

class MapTest extends TestCase
{
    public function testMappings(): void
    {
        $mapping = Map::root(stdClass::class, 'idColumn', [
            Map::property(
                'objectProperty',
                'propertyColumn',
                static fn (?string $value): ?Email => is_string($value) ? new Email($value) : null,
            ),
            Map::datetimeImmutableProperty('datetimeImmutableProperty', 'datetimeImmutableColumn'),
            Map::datetimeProperty('datetimeProperty', 'datetimeColumn'),
            Map::relation('relationProperty', stdClass::class, 'relationColumnId', [
                Map::property('relationObjectProperty', 'relationPropertyColumn'),
            ]),
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
        $this->assertCount(3, $propertyMappings);

        $this->assertSame('objectProperty', $propertyMappings[0]->getObjectProperty());
        $this->assertSame('propertyColumn', $propertyMappings[0]->getResultSetColumn());
        $closure = $propertyMappings[0]->getConversionClosure();
        $this->assertNotNull($closure);
        $this->assertNull($closure(null));
        $this->assertEquals(new Email('email@example.com'), $closure('email@example.com'));

        $this->assertSame('datetimeImmutableProperty', $propertyMappings[1]->getObjectProperty());
        $this->assertSame('datetimeImmutableColumn', $propertyMappings[1]->getResultSetColumn());
        $closure = $propertyMappings[1]->getConversionClosure();
        $this->assertNotNull($closure);
        $this->assertNull($closure(null));
        $this->assertEquals(new DateTimeImmutable('2022-03-31 09:45:10'), $closure('2022-03-31 09:45:10'));

        $this->assertSame('datetimeProperty', $propertyMappings[2]->getObjectProperty());
        $this->assertSame('datetimeColumn', $propertyMappings[2]->getResultSetColumn());
        $closure = $propertyMappings[2]->getConversionClosure();
        $this->assertNotNull($closure);
        $this->assertNull($closure(null));
        $this->assertEquals(new DateTime('2022-03-31 09:45:15'), $closure('2022-03-31 09:45:15'));

        $relationMappings = $mapping->getRelationMappings();
        $this->assertCount(3, $relationMappings);

        $relationMapping = $relationMappings[0];
        $this->assertSame('relationProperty', $relationMapping->getObjectProperty());
        $this->assertSame(stdClass::class, $relationMapping->getTargetClass());

        $this->assertCount(0, $relationMapping->getRelationMappings());
        $relationPropertyMappings = $relationMapping->getPropertyMappings();
        $this->assertCount(1, $relationPropertyMappings);
        $this->assertSame('relationObjectProperty', $relationPropertyMappings[0]->getObjectProperty());
        $this->assertSame('relationPropertyColumn', $relationPropertyMappings[0]->getResultSetColumn());
        $this->assertNull($relationPropertyMappings[0]->getConversionClosure());

        $multipleRelationMapping = $relationMappings[1];
        $this->assertSame('multipleRelationProperty', $multipleRelationMapping->getObjectProperty());
        $this->assertSame(stdClass::class, $multipleRelationMapping->getTargetClass());

        $this->assertCount(0, $multipleRelationMapping->getRelationMappings());
        $multipleRelationPropertyMappings = $multipleRelationMapping->getPropertyMappings();
        $this->assertCount(1, $multipleRelationPropertyMappings);
        $this->assertSame('multipleRelationObjectProperty', $multipleRelationPropertyMappings[0]->getObjectProperty());
        $this->assertSame('multipleRelationPropertyColumn', $multipleRelationPropertyMappings[0]->getResultSetColumn());
        $this->assertNull($multipleRelationPropertyMappings[0]->getConversionClosure());

        $singleRelationMapping = $relationMappings[2];
        $this->assertSame('singleRelationProperty', $singleRelationMapping->getObjectProperty());
        $this->assertSame(stdClass::class, $singleRelationMapping->getTargetClass());

        $this->assertCount(0, $singleRelationMapping->getRelationMappings());
        $singleRelationPropertyMappings = $singleRelationMapping->getPropertyMappings();
        $this->assertCount(1, $singleRelationPropertyMappings);
        $this->assertSame('singleRelationObjectProperty', $singleRelationPropertyMappings[0]->getObjectProperty());
        $this->assertSame('singleRelationPropertyColumn', $singleRelationPropertyMappings[0]->getResultSetColumn());
        $this->assertNull($singleRelationPropertyMappings[0]->getConversionClosure());
    }
}
