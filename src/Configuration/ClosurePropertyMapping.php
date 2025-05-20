<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration;

use Closure;
use Override;

/**
 * @template T
 *
 * @implements PropertyMappingConverterInterface<T>
 */
final readonly class ClosurePropertyMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @param Closure(bool|float|int|string|null): T $closure
     */
    public function __construct(string $objectProperty, string $resultSetColumn, private Closure $closure)
    {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    #[Override]
    public function convert(bool|float|int|string|null $value): mixed
    {
        $closure = $this->closure;

        return $closure($value);
    }
}
