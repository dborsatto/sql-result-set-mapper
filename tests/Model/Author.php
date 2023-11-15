<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

class Author
{
    private int $id;
    private string $firstName;
    private ?Email $email;
    /**
     * @var list<BlogPost>
     */
    private array $blogPosts;
    /**
     * @var list<Session>
     */
    private array $sessions;
    private ?Address $address;

    private ?ConcreteEnum $enumValue;
    /**
     * @var list<ConcreteEnum>|null
     */
    private ?array $enumSerializedArray;
    /**
     * @var list<ConcreteEnum>|null
     */
    private ?array $enumSymbolSeparated;
    /**
     * @var list<ConcreteEnum>|null
     */
    private ?array $enumJson;

    /**
     * @param list<BlogPost>          $blogPosts
     * @param list<Session>           $sessions
     * @param list<ConcreteEnum>|null $enumSerializedArray
     * @param list<ConcreteEnum>|null $enumSymbolSeparated
     * @param list<ConcreteEnum>|null $enumJson
     */
    public function __construct(
        int $id,
        string $firstName,
        ?Email $email,
        array $blogPosts,
        array $sessions,
        ?Address $address,
        ?ConcreteEnum $enumValue,
        ?array $enumSerializedArray,
        ?array $enumSymbolSeparated,
        ?array $enumJson,
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->blogPosts = $blogPosts;
        $this->sessions = $sessions;
        $this->address = $address;
        $this->enumValue = $enumValue;
        $this->enumSerializedArray = $enumSerializedArray;
        $this->enumSymbolSeparated = $enumSymbolSeparated;
        $this->enumJson = $enumJson;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getEmail(): ?Email
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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function getEnumValue(): ?ConcreteEnum
    {
        return $this->enumValue;
    }

    public function getEnumSerializedArray(): ?array
    {
        return $this->enumSerializedArray;
    }

    public function getEnumSymbolSeparated(): ?array
    {
        return $this->enumSymbolSeparated;
    }

    public function getEnumJson(): ?array
    {
        return $this->enumJson;
    }
}
