<?php

use DBorsatto\SqlResultSetMapper\Map;
use DBorsatto\SqlResultSetMapper\Mapper;

readonly class Email
{
    public function __construct(public string $value)
    {
    }
}

readonly class AuthorReadModel
{
    public int $id;
    public string $name;
    public Email|null $email;
    public array $blogPosts;
}

readonly class BlogPostReadModel
{
    public string $title;
    public string $body;
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

$mapping = Map::create(AuthorReadModel::class, 'id', [
    Map::property('id', 'authorId'),
    Map::property('name', 'authorName'),
    Map::propertyConversion('email', 'authorEmail', static function (string $email): Email {
        return new Email($email);
    }),
    Map::multipleRelation('blogPosts', BlogPostReadModel::class, 'blogPostId', [
        Map::property('title', 'blogPostTitle'),
        Map::property('body', 'blogPostBody'),
    ]),
]);
$models = $mapper->map($mapping, $results);
