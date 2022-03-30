<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Hydrator\LaminasHydrator;
use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Tests\Model\Author;
use DBorsatto\SqlResultSetMapper\Tests\Model\BlogPost;
use DBorsatto\SqlResultSetMapper\Tests\Model\Email;
use DBorsatto\SqlResultSetMapper\Tests\Model\Session;
use PHPUnit\Framework\TestCase;
use function is_string;

class LaminasHydratorTest extends TestCase
{
    public function testHydration(): void
    {
        $items = [
            [
                'id' => 1,
                'firstName' => 'John',
                'email' => null,
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
                'email' => 'jane.smith@example.com',
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
                'email' => null,
                'blogPosts' => [
                    [
                        'title' => 'What a title',
                        'body' => 'What a body',
                    ],
                ],
                'sessions' => [],
            ],
        ];

        $mapping = Map::root(Author::class, 'userId', [
            Map::property('id', 'userId'),
            Map::property('firstName', 'userFirstName'),
            Map::property(
                'email',
                'email',
                static fn (?string $value): ?Email => is_string($value) ? new Email($value) : null,
            ),
            Map::relation('blogPosts', BlogPost::class, 'blogPostId', [
                Map::property('title', 'blogPostTitle'),
                Map::property('body', 'blogPostBody'),
            ]),
            Map::relation('sessions', Session::class, 'sessionId', [
                Map::datetimeImmutableProperty('expiresAt', 'sessionExpiresAt'),
            ]),
        ]);

        $hydrator = new LaminasHydrator();

        $expected = [
            new Author(1, 'John', null, [
                new BlogPost('Some title', 'Some body'),
                new BlogPost('Another title', 'Another body'),
                new BlogPost('Some other title', 'Some other body'),
            ], [
                new Session(new DateTimeImmutable('2022-02-23 16:00:00')),
                new Session(new DateTimeImmutable('2022-02-25 16:00:00')),
            ]),
            new Author(2, 'Jane', new Email('jane.smith@example.com'), [], [
                new Session(new DateTimeImmutable('2022-02-27 16:00:00')),
            ]),
            new Author(3, 'Jimmy', null, [
                new BlogPost('What a title', 'What a body'),
            ], []),
        ];

        $this->assertEquals($expected, $hydrator->hydrate($mapping, $items));
    }

}
