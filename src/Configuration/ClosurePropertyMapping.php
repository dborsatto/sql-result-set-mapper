<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use Closure;

/**
 * @template T
 *
 * @implements PropertyMappingConverterInterface<T>
 */
class ClosurePropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @param Closure(mixed): T $closure
     */
    public function __construct(string $objectProperty, string $resultSetColumn, private Closure $closure)
    {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    public function convert(mixed $value): mixed
    {
        $closure = $this->closure;

        return $closure($value);
    }
}
