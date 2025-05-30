<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests;

use DateTimeImmutable;
use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Tests\Model\Address;
use DBorsatto\SqlResultSetMapper\Tests\Model\AddressCoordinates;
use DBorsatto\SqlResultSetMapper\Tests\Model\AddressLine;
use DBorsatto\SqlResultSetMapper\Tests\Model\Author;
use DBorsatto\SqlResultSetMapper\Tests\Model\BlogPost;
use DBorsatto\SqlResultSetMapper\Tests\Model\ConcreteEnum;
use DBorsatto\SqlResultSetMapper\Tests\Model\Email;
use DBorsatto\SqlResultSetMapper\Tests\Model\Session;

use function is_string;

final class DataSet
{
    /**
     * @return ClassMapping<Author>
     */
    public static function getMapping(): ClassMapping
    {
        return Map::create(Author::class, 'userId', [
            Map::property('id', 'userId'),
            Map::property('firstName', 'userFirstName'),
            Map::propertyConversion(
                'email',
                'userEmail',
                static fn (bool|float|int|string|null $value): ?Email => is_string($value) ? new Email($value) : null,
            ),
            Map::multipleRelation('blogPosts', BlogPost::class, 'blogPostId', [
                Map::property('title', 'blogPostTitle'),
                Map::property('body', 'blogPostBody'),
            ]),
            Map::multipleRelation('sessions', Session::class, 'sessionId', [
                Map::datetimeImmutableProperty('expiresAt', 'sessionExpiresAt'),
            ]),
            Map::singleRelation('address', Address::class, 'addressId', [
                Map::property('description', 'addressDescription'),
                Map::multipleRelation('lines', AddressLine::class, 'addressLineId', [
                    Map::property('line', 'addressLine'),
                ]),
                Map::singleRelation('coordinates', AddressCoordinates::class, 'addressCoordinatesId', [
                    Map::property('longitude', 'addressCoordinatesLongitude'),
                    Map::property('latitude', 'addressCoordinatesLatitude'),
                ]),
            ]),
            Map::enumProperty('enumValue', 'enumValue', ConcreteEnum::class),
            Map::smartEnumPropertiesSymbolSeparated('enumSymbolSeparated', 'enumSymbolSeparated', ConcreteEnum::class),
            Map::smartEnumPropertiesJson('enumJson', 'enumJson', ConcreteEnum::class),
        ]);
    }

    /**
     * @return list<array<string, string|int|null>>
     */
    public static function getSqlResultSet(): array
    {
        return [
            [
                'userId' => 1,
                'userFirstName' => 'John',
                'userEmail' => null,
                'blogPostId' => 1,
                'blogPostTitle' => 'Some title',
                'blogPostBody' => 'Some body',
                'sessionId' => 1,
                'sessionExpiresAt' => '2022-02-23 16:00:00',
                'addressId' => null,
                'addressDescription' => null,
                'addressLineId' => null,
                'addressLine' => null,
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => 'value1',
                'enumSymbolSeparated' => 'value1,value2',
                'enumJson' => '["value1"]',
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
                'addressId' => null,
                'addressDescription' => null,
                'addressLineId' => null,
                'addressLine' => null,
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => 'value1',
                'enumSymbolSeparated' => 'value1,value2',
                'enumJson' => '["value1"]',
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
                'addressId' => null,
                'addressDescription' => null,
                'addressLineId' => null,
                'addressLine' => null,
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => 'value1',
                'enumSymbolSeparated' => 'value1,value2',
                'enumJson' => '["value1"]',
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
                'addressId' => null,
                'addressDescription' => null,
                'addressLineId' => null,
                'addressLine' => null,
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => 'value1',
                'enumSymbolSeparated' => 'value1,value2',
                'enumJson' => '["value1"]',
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
                'addressId' => null,
                'addressDescription' => null,
                'addressLineId' => null,
                'addressLine' => null,
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => 'value1',
                'enumSymbolSeparated' => 'value1,value2',
                'enumJson' => '["value1"]',
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
                'addressId' => null,
                'addressDescription' => null,
                'addressLineId' => null,
                'addressLine' => null,
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => 'value1',
                'enumSymbolSeparated' => 'value1,value2',
                'enumJson' => '["value1"]',
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
                'addressId' => 1,
                'addressDescription' => 'Rome, Italy',
                'addressLineId' => null,
                'addressLine' => null,
                'addressCoordinatesId' => 1,
                'addressCoordinatesLongitude' => '12.5',
                'addressCoordinatesLatitude' => '26.7',
                'enumValue' => null,
                'enumSymbolSeparated' => null,
                'enumJson' => null,
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
                'addressId' => 2,
                'addressDescription' => 'Paris, France',
                'addressLineId' => 1,
                'addressLine' => '1st Avenue',
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => null,
                'enumSymbolSeparated' => null,
                'enumJson' => null,
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
                'addressId' => 2,
                'addressDescription' => 'Paris, France',
                'addressLineId' => 2,
                'addressLine' => '2nd Avenue',
                'addressCoordinatesId' => null,
                'addressCoordinatesLongitude' => null,
                'addressCoordinatesLatitude' => null,
                'enumValue' => null,
                'enumSymbolSeparated' => null,
                'enumJson' => null,
            ],
        ];
    }

    /**
     * @return list<array>
     */
    public static function getNormalizedArrayDataSet(): array
    {
        return [
            [
                'id' => 1,
                'firstName' => 'John',
                'email' => null,
                'enumValue' => 'value1',
                'enumSymbolSeparated' => 'value1,value2',
                'enumJson' => '["value1"]',
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
                'email' => 'jane.smith@example.com',
                'enumValue' => null,
                'enumSymbolSeparated' => null,
                'enumJson' => null,
                'blogPosts' => [],
                'sessions' => [
                    [
                        'expiresAt' => '2022-02-27 16:00:00',
                    ],
                ],
                'address' => [
                    'description' => 'Rome, Italy',
                    'lines' => [],
                    'coordinates' => [
                        'longitude' => '12.5',
                        'latitude' => '26.7',
                    ],
                ],
            ],
            [
                'id' => 3,
                'firstName' => 'Jimmy',
                'email' => null,
                'enumValue' => null,
                'enumSymbolSeparated' => null,
                'enumJson' => null,
                'blogPosts' => [
                    [
                        'title' => 'What a title',
                        'body' => 'What a body',
                    ],
                ],
                'sessions' => [],
                'address' => [
                    'description' => 'Paris, France',
                    'lines' => [
                        [
                            'line' => '1st Avenue',
                        ],
                        [
                            'line' => '2nd Avenue',
                        ],
                    ],
                    'coordinates' => null,
                ],
            ],
        ];
    }

    public static function getObjectResultSet(): array
    {
        return [
            new Author(
                1,
                'John',
                null,
                [
                    new BlogPost('Some title', 'Some body'),
                    new BlogPost('Another title', 'Another body'),
                    new BlogPost('Some other title', 'Some other body'),
                ],
                [
                    new Session(new DateTimeImmutable('2022-02-23 16:00:00')),
                    new Session(new DateTimeImmutable('2022-02-25 16:00:00')),
                ],
                null,
                ConcreteEnum::Value1,
                [ConcreteEnum::Value1, ConcreteEnum::Value2],
                [ConcreteEnum::Value1],
            ),
            new Author(
                2,
                'Jane',
                new Email('jane.smith@example.com'),
                [],
                [
                    new Session(new DateTimeImmutable('2022-02-27 16:00:00')),
                ],
                new Address('Rome, Italy', [], new AddressCoordinates(12.5, 26.7)),
                null,
                null,
                null,
            ),
            new Author(
                3,
                'Jimmy',
                null,
                [
                    new BlogPost('What a title', 'What a body'),
                ],
                [],
                new Address(
                    'Paris, France',
                    [
                        new AddressLine('1st Avenue'),
                        new AddressLine('2nd Avenue'),
                    ],
                    null,
                ),
                null,
                null,
                null,
            ),
        ];
    }
}
