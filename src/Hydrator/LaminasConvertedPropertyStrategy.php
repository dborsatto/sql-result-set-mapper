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

    public function extract($value, null|object $object = null): mixed
    {
        return $value;
    }

    /**
     * @throws SqlResultSetValueCouldNotBeConvertedException
     */
    public function hydrate($value, null|array $data): mixed
    {
        /** @var bool|float|int|string|null $value */
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
