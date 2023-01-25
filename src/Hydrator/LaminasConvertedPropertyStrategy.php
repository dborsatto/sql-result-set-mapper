<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use Laminas\Hydrator\Strategy\StrategyInterface;
use Throwable;

class LaminasConvertedPropertyStrategy implements StrategyInterface
{
    private PropertyMappingConverterInterface $mapping;

    public function __construct(PropertyMappingConverterInterface $mapping)
    {
        $this->mapping = $mapping;
    }

    public function extract($value, ?object $object = null)
    {
        return $value;
    }

    /**
     * @throws Throwable
     */
    public function hydrate($value, ?array $data)
    {
        /** @var string|null $value */
        return $this->mapping->convert($value);
    }
}
