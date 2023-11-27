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
     * @param Closure(bool|float|int|string|null): T $closure
     */
    public function __construct(string $objectProperty, string $resultSetColumn, private Closure $closure)
    {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    public function convert(null|bool|float|int|string $value): mixed
    {
        $closure = $this->closure;

        return $closure($value);
    }
}
