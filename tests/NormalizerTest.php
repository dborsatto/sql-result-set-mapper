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
    public function testNormalization(): void
    {
        $mapping = Map::root(stdClass::class, 'userId', [
            Map::property('id', 'userId'),
            Map::property('firstName', 'userFirstName'),
            Map::relation('blogPosts', stdClass::class, 'blogPostId', [
                Map::property('title', 'blogPostTitle'),
                Map::property('body', 'blogPostBody'),
            ]),
            Map::multipleRelation('sessions', stdClass::class, 'sessionId', [
                Map::property('expiresAt', 'sessionExpiresAt'),
            ]),
            Map::singleRelation('address', stdClass::class, 'addressId', [
                Map::property('description', 'addressDescription'),
            ]),
        ]);

        $sqlResultSetRows = [
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 1,
                'blogPostTitle' => 'Some title',
                'blogPostBody' => 'Some body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
                'addressId' => null,
                'addressDescription' => null,
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 2,
                'blogPostTitle' => 'Another title',
                'blogPostBody' => 'Another body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
                'addressId' => null,
                'addressDescription' => null,
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 3,
                'blogPostTitle' => 'Some other title',
                'blogPostBody' => 'Some other body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
                'addressId' => null,
                'addressDescription' => null,
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 1,
                'blogPostTitle' => 'Some title',
                'blogPostBody' => 'Some body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
                'addressId' => null,
                'addressDescription' => null,
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 2,
                'blogPostTitle' => 'Another title',
                'blogPostBody' => 'Another body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
                'addressId' => null,
                'addressDescription' => null,
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 3,
                'blogPostTitle' => 'Some other title',
                'blogPostBody' => 'Some other body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
                'addressId' => null,
                'addressDescription' => null,
            ],
            [
                'userId' => 2,
                'userFirstName' => 'Jane',
                'blogPostId' => null,
                'blogPostTitle' => null,
                'blogPostBody' => null,
                'sessionId' => 4,
                'sessionExpiresAt' => '2022-02-27 16:00:00',
                'addressId' => 1,
                'addressDescription' => 'Rome, Italy',
            ],
            [
                'userId' => 3,
                'userFirstName' => 'Jimmy',
                'blogPostId' => 4,
                'blogPostTitle' => 'What a title',
                'blogPostBody' => 'What a body',
                'sessionId' => null,
                'sessionExpiresAt' => null,
                'addressId' => 2,
                'addressDescription' => 'Paris, France',
            ],
        ];

        $normalizer = new Normalizer($mapping);

        $expected = [
            [
                'id' => 1,
                'firstName' => 'John',
                'blogPosts' => [
                    [
                        'title' => 'Some title',
                        'body' => 'Some body',
                    ],
                    [
                        'title' => 'Another title',
                        'body' => 'Another body',
                    ],
                    [
                        'title' => 'Some other title',
                        'body' => 'Some other body',
                    ],
                ],
                'sessions' => [
                    [
                        'expiresAt' => '2022-02-23 16:00:00',
                    ],
                    [
                        'expiresAt' => '2022-02-25 16:00:00',
                    ],
                ],
                'address' => null,
            ],
            [
                'id' => 2,
                'firstName' => 'Jane',
                'blogPosts' => [],
                'sessions' => [
                    [
                        'expiresAt' => '2022-02-27 16:00:00',
                    ],
                ],
                'address' => [
                    'description' => 'Rome, Italy',
                ],
            ],
            [
                'id' => 3,
                'firstName' => 'Jimmy',
                'blogPosts' => [
                    [
                        'title' => 'What a title',
                        'body' => 'What a body',
                    ],
                ],
                'sessions' => [],
                'address' => [
                    'description' => 'Paris, France',
                ],
            ],
        ];

        $this->assertSame($expected, $normalizer->normalize($sqlResultSetRows));
    }

    public function testMissingIdColumn(): void
    {
        $this->expectException(SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException::class);

        $mapping = Map::root(stdClass::class, 'userId', []);

        $sqlResultSetRows = [
            [
                'userFirstName' => 'John',
            ],
        ];

        $normalizer = new Normalizer($mapping);
        $normalizer->normalize($sqlResultSetRows);
    }

    public function testMissingPropertyColumn(): void
    {
        $this->expectException(SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException::class);

        $mapping = Map::root(stdClass::class, 'userId', [
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
