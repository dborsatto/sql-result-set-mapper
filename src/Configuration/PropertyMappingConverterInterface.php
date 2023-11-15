<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use Throwable;

/**
 * @template T
 */
interface PropertyMappingConverterInterface
{
    /**
     * @throws Throwable
     *
     * @return T|null
     */
    public function convert(mixed $value): mixed;
}
