<?php

use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Mapper;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}

class AuthorReadModel
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private ?Email $email;
    private array $blogPosts;
}

class BlogPostReadModel
{
    private string $title;
    private string $body;
}

$mapper = new Mapper();

$mapping = Map::root(AuthorReadModel::class, 'id', [
    Map::property('id', 'id'),
    Map::property('firstName', 'firstName'),
    Map::property('lastName', 'lastName'),
    Map::nullableProperty('email', 'email', static function (string $email): Email {
        return new Email($email);
    }),
    Map::relation('blogPosts', BlogPostReadModel::class, 'blogPostId', [
        Map::property('title', 'blogPostTitle'),
        Map::property('body', 'blogPostBody'),
    ]),
]);
$models = $mapper->map($mapping, $results);
