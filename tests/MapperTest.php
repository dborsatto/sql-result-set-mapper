<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DBorsatto\SqlResultSetMapper\Mapper;
use PHPUnit\Framework\TestCase;

final class MapperTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMapping(): void
    {
        $mapping = DataSet::getMapping();

        $mapper = new Mapper();

        $expected = DataSet::getObjectResultSet();

        $sqlResultSetRows = DataSet::getSqlResultSet();

        $this->assertEquals($expected, $mapper->map($mapping, $sqlResultSetRows));
    }
}
