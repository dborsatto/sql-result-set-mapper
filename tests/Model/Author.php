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

    /**
     * @param list<BlogPost> $blogPosts
     * @param list<Session>  $sessions
     */
    public function __construct(
        int $id,
        string $firstName,
        ?Email $email,
        array $blogPosts,
        array $sessions,
        ?Address $address
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->blogPosts = $blogPosts;
        $this->sessions = $sessions;
        $this->address = $address;
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
}
