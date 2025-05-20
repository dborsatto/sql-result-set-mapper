<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

final readonly class Author
{
    /**
     * @param list<BlogPost>          $blogPosts
     * @param list<Session>           $sessions
     * @param list<ConcreteEnum>|null $enumSymbolSeparated
     * @param list<ConcreteEnum>|null $enumJson
     */
    public function __construct(
        public int $id,
        public string $firstName,
        public ?Email $email,
        public array $blogPosts,
        public array $sessions,
        public ?Address $address,
        public ?ConcreteEnum $enumValue,
        public ?array $enumSymbolSeparated,
        public ?array $enumJson,
    ) {
    }
}
