<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Normalizer;
use PHPUnit\Framework\TestCase;
use stdClass;

class NormalizerTest extends TestCase
{
    public function testNormalization(): void
    {
        $map = Map::root(stdClass::class, 'userId', [
            Map::property('id', 'userId'),
            Map::property('firstName', 'userFirstName'),
            Map::relation('blogPosts', stdClass::class, 'blogPostId', [
                Map::property('title', 'blogPostTitle'),
                Map::property('body', 'blogPostBody'),
            ]),
            Map::relation('sessions', stdClass::class, 'sessionId', [
                Map::property('expiresAt', 'sessionExpiresAt'),
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
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 2,
                'blogPostTitle' => 'Another title',
                'blogPostBody' => 'Another body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 3,
                'blogPostTitle' => 'Some other title',
                'blogPostBody' => 'Some other body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 1,
                'blogPostTitle' => 'Some title',
                'blogPostBody' => 'Some body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 2,
                'blogPostTitle' => 'Another title',
                'blogPostBody' => 'Another body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'blogPostId' => 3,
                'blogPostTitle' => 'Some other title',
                'blogPostBody' => 'Some other body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
            ],
            [
                'userId' => 2,
                'userFirstName' => 'Jane',
                'blogPostId' => null,
                'blogPostTitle' => null,
                'blogPostBody' => null,
                'sessionId' => 4,
                'sessionExpiresAt' => '2022-02-27 16:00:00',
            ],
            [
                'userId' => 3,
                'userFirstName' => 'Jimmy',
                'blogPostId' => 4,
                'blogPostTitle' => 'What a title',
                'blogPostBody' => 'What a body',
                'sessionId' => null,
                'sessionExpiresAt' => null,
            ],
        ];

        $normalizer = new Normalizer($map);

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
            ],
        ];

        $this->assertSame($expected, $normalizer->normalize($sqlResultSetRows));
    }
}
