<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Hydrator;

use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use Laminas\Hydrator\Strategy\StrategyInterface;
use Override;
use Throwable;

final class LaminasConvertedPropertyStrategy implements StrategyInterface
{
    public function __construct(private PropertyMappingConverterInterface $mapping)
    {
    }

    #[Override]
    public function extract($value, ?object $object = null): mixed
    {
        return $value;
    }

    /**
     * @throws SqlResultSetValueCouldNotBeConvertedException
     */
    #[Override]
    public function hydrate($value, ?array $data): mixed
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
