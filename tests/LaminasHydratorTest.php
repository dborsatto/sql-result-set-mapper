<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DBorsatto\SqlResultSetMapper\Hydrator\LaminasHydrator;
use PHPUnit\Framework\TestCase;

class LaminasHydratorTest extends TestCase
{
    public function testHydration(): void
    {
        $items = DataSet::getNormalizedArrayDataSet();

        $mapping = DataSet::getMapping();

        $hydrator = new LaminasHydrator();

        $expected = DataSet::getObjectResultSet();

        $this->assertEquals($expected, $hydrator->hydrate($mapping, $items));
    }
}
