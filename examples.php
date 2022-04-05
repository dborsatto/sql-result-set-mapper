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
    private string $name;
    private ?Email $email;
    private array $blogPosts;
}

class BlogPostReadModel
{
    private string $title;
    private string $body;
}

$sql = <<<'SQL'
        SELECT
            a.id AS authorId,
            a.name AS authorName,
            a.email AS authorEmail,
            bp.id AS blogPostId,
            bp.title AS blogPostTitle,
            bp.body AS blogPostBody
        FROM authors a
        LEFT JOIN blog_posts bp ON bp.author_id = author.id
    SQL;

// Execute query

$mapper = new Mapper();

$mapping = Map::root(AuthorReadModel::class, 'id', [
    Map::property('id', 'authorId'),
    Map::property('name', 'authorName'),
    Map::property('email', 'authorEmail', static function (string $email): Email {
        return new Email($email);
    }),
    Map::relation('blogPosts', BlogPostReadModel::class, 'blogPostId', [
        Map::property('title', 'blogPostTitle'),
        Map::property('body', 'blogPostBody'),
    ]),
]);
$models = $mapper->map($mapping, $results);
