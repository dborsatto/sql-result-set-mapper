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
     * @var Closure(string|null): T
     */
    private Closure $closure;

    /**
     * @param Closure(string|null): T $closure
     */
    public function __construct(string $objectProperty, string $resultSetColumn, Closure $closure)
    {
        parent::__construct($objectProperty, $resultSetColumn);

        $this->closure = $closure;
    }

    public function convert(?string $value)
    {
        $closure = $this->closure;

        return $closure($value);
    }
}
