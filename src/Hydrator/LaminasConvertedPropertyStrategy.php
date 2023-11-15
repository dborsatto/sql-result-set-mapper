<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use Laminas\Hydrator\Strategy\StrategyInterface;
use Throwable;

class LaminasConvertedPropertyStrategy implements StrategyInterface
{
    public function __construct(private PropertyMappingConverterInterface $mapping)
    {
    }

    public function extract($value, ?object $object = null): mixed
    {
        return $value;
    }

    /**
     * @throws SqlResultSetValueCouldNotBeConvertedException
     */
    public function hydrate($value, ?array $data): mixed
    {
        try {
            return $this->mapping->convert($value);
        } catch (Throwable $exception) {
            if ($exception instanceof SqlResultSetValueCouldNotBeConvertedException) {
                throw $exception;
            }

            throw SqlResultSetValueCouldNotBeConvertedException::create($value, $exception);
        }
    }
}
