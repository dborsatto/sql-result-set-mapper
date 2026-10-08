<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeHydratedException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use DBorsatto\SqlResultSetMapper\Hydrator\ReflectionHydrator;
use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Tests\Model\Author;
use DBorsatto\SqlResultSetMapper\Tests\Model\BlogPost;
use DBorsatto\SqlResultSetMapper\Tests\Model\Email;
use DBorsatto\SqlResultSetMapper\Tests\Model\Token;
use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function is_string;
use function str_contains;

final class ReflectionHydratorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHydration(): void
    {
        $items = DataSet::getNormalizedArrayDataSet();

        $mapping = DataSet::getMapping();

        $hydrator = new ReflectionHydrator();

        $expected = DataSet::getObjectResultSet();

        $this->assertEquals($expected, $hydrator->hydrate($mapping, $items));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHydrationDoesNotCallConstructor(): void
    {
        $mapping = Map::create(Token::class, 'tokenId', [
            Map::property('value', 'tokenValue'),
        ]);

        $hydrator = new ReflectionHydrator();

        $tokens = $hydrator->hydrate($mapping, [['value' => 'abc']]);

        $this->assertCount(1, $tokens);
        $this->assertSame('abc', $tokens[0]->value);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConversionFailureIsWrapped(): void
    {
        $mapping = Map::create(Author::class, 'userId', [
            Map::propertyConversion(
                'email',
                'userEmail',
                static fn (bool|float|int|string|null $value): Email => is_string($value) && str_contains($value, '@')
                    ? new Email($value)
                    : throw new RuntimeException('Invalid email'),
            ),
        ]);

        $hydrator = new ReflectionHydrator();

        try {
            $hydrator->hydrate($mapping, [['email' => 'not-an-email']]);
            $this->fail('Hydration should have failed.');
        } catch (SqlResultSetCouldNotBeHydratedException $exception) {
            $conversionException = $exception->getPrevious();
            $this->assertInstanceOf(SqlResultSetValueCouldNotBeConvertedException::class, $conversionException);
            $this->assertInstanceOf(RuntimeException::class, $conversionException->getPrevious());
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRelationWithInvalidValueFails(): void
    {
        $mapping = Map::create(Author::class, 'userId', [
            Map::multipleRelation('blogPosts', BlogPost::class, 'blogPostId', [
                Map::property('title', 'blogPostTitle'),
            ]),
        ]);

        $hydrator = new ReflectionHydrator();

        try {
            $hydrator->hydrate($mapping, [['blogPosts' => 'not-a-list']]);
            $this->fail('Hydration should have failed.');
        } catch (SqlResultSetCouldNotBeHydratedException $exception) {
            $this->assertInstanceOf(Exception::class, $exception->getPrevious());
        }
    }
}
