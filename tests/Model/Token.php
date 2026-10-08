<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Tests\Model;

use Exception;

final readonly class Token
{
    /**
     * @throws Exception
     */
    public function __construct(
        public string $value,
    ) {
        throw new Exception('The constructor must not be called during hydration.');
    }
}
