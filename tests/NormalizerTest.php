<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException;
use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Normalizer;
use PHPUnit\Framework\TestCase;
use stdClass;

class NormalizerTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNormalization(): void
    {
        $mapping = DataSet::getMapping();

        $sqlResultSetRows = DataSet::getSqlResultSet();

        $normalizer = new Normalizer($mapping);

        $expected = DataSet::getNormalizedArrayDataSet();

        $this->assertSame($expected, $normalizer->normalize($sqlResultSetRows));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMissingIdColumn(): void
    {
        $this->expectException(SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException::class);

        $mapping = Map::create(stdClass::class, 'userId', []);

        $sqlResultSetRows = [
            [
                'userFirstName' => 'John',
            ],
        ];

        $normalizer = new Normalizer($mapping);
        $normalizer->normalize($sqlResultSetRows);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMissingPropertyColumn(): void
    {
        $this->expectException(SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException::class);

        $mapping = Map::create(stdClass::class, 'userId', [
            Map::property('', 'userFirstName'),
        ]);

        $sqlResultSetRows = [
            [
                'userId' => 1,
            ],
        ];

        $normalizer = new Normalizer($mapping);
        $normalizer->normalize($sqlResultSetRows);
    }
}
