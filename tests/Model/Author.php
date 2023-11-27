<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class Author
{
    /**
     * @param list<BlogPost>          $blogPosts
     * @param list<Session>           $sessions
     * @param list<ConcreteEnum>|null $enumSerializedArray
     * @param list<ConcreteEnum>|null $enumSymbolSeparated
     * @param list<ConcreteEnum>|null $enumJson
     */
    public function __construct(
        private int $id,
        private string $firstName,
        private null|Email $email,
        private array $blogPosts,
        private array $sessions,
        private null|Address $address,
        private null|ConcreteEnum $enumValue,
        private null|array $enumSerializedArray,
        private null|array $enumSymbolSeparated,
        private null|array $enumJson,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getEmail(): null|Email
    {
        return $this->email;
    }

    /**
     * @return list<BlogPost>
     */
    public function getBlogPosts(): array
    {
        return $this->blogPosts;
    }

    /**
     * @return list<Session>
     */
    public function getSessions(): array
    {
        return $this->sessions;
    }

    public function getAddress(): null|Address
    {
        return $this->address;
    }

    public function getEnumValue(): null|ConcreteEnum
    {
        return $this->enumValue;
    }

    public function getEnumSerializedArray(): null|array
    {
        return $this->enumSerializedArray;
    }

    public function getEnumSymbolSeparated(): null|array
    {
        return $this->enumSymbolSeparated;
    }

    public function getEnumJson(): null|array
    {
        return $this->enumJson;
    }
}
