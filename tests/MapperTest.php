<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Mapper;
use DBorsatto\SqlResultSetMapper\Tests\Model\Author;
use DBorsatto\SqlResultSetMapper\Tests\Model\BlogPost;
use DBorsatto\SqlResultSetMapper\Tests\Model\Email;
use DBorsatto\SqlResultSetMapper\Tests\Model\Session;
use PHPUnit\Framework\TestCase;
use function is_string;

class MapperTest extends TestCase
{
    public function testMapping(): void
    {
        $mapping = Map::root(Author::class, 'userId', [
            Map::property('id', 'userId'),
            Map::property('firstName', 'userFirstName'),
            Map::property(
                'email',
                'userEmail',
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

        $mapper = new Mapper();

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

        $sqlResultSetRows = [
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'userEmail' => null,
                'blogPostId' => 1,
                'blogPostTitle' => 'Some title',
                'blogPostBody' => 'Some body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'userEmail' => null,
                'blogPostId' => 2,
                'blogPostTitle' => 'Another title',
                'blogPostBody' => 'Another body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'userEmail' => null,
                'blogPostId' => 3,
                'blogPostTitle' => 'Some other title',
                'blogPostBody' => 'Some other body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'userEmail' => null,
                'blogPostId' => 1,
                'blogPostTitle' => 'Some title',
                'blogPostBody' => 'Some body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'userEmail' => null,
                'blogPostId' => 2,
                'blogPostTitle' => 'Another title',
                'blogPostBody' => 'Another body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
            ],
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'userEmail' => null,
                'blogPostId' => 3,
                'blogPostTitle' => 'Some other title',
                'blogPostBody' => 'Some other body',
                'sessionId' => 2,
                'sessionExpiresAt' => '2022-02-25 16:00:00',
            ],
            [
                'userId' => 2,
                'userFirstName' => 'Jane',
                'userEmail' => 'jane.smith@example.com',
                'blogPostId' => null,
                'blogPostTitle' => null,
                'blogPostBody' => null,
                'sessionId' => 4,
                'sessionExpiresAt' => '2022-02-27 16:00:00',
            ],
            [
                'userId' => 3,
                'userFirstName' => 'Jimmy',
                'userEmail' => null,
                'blogPostId' => 4,
                'blogPostTitle' => 'What a title',
                'blogPostBody' => 'What a body',
                'sessionId' => null,
                'sessionExpiresAt' => null,
            ],
        ];

        $this->assertEquals($expected, $mapper->map($mapping, $sqlResultSetRows));
    }
}
