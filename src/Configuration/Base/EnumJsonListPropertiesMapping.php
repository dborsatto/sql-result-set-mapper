<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration\Base;

use BackedEnum;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use JsonException;
use Throwable;

use function array_is_list;
use function is_int;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * @template T of BackedEnum
 *
 * @implements PropertyMappingConverterInterface<list<T>>
 */
final readonly class EnumJsonListPropertiesMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @param class-string<T> $enumClass
     */
    public function __construct(
        string $objectProperty,
        string $resultSetColumn,
        private string $enumClass,
    ) {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    public function convert(bool|float|int|string|null $value): array|null
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        if ($value === '') {
            return [];
        }

        try {
            /** @var array $decoded */
            $decoded = json_decode($value, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value, $exception);
        }

        if (!array_is_list($decoded)) {
            throw SqlResultSetValueCouldNotBeConvertedException::create($value);
        }

        $enumClass = $this->enumClass;

        $enums = [];
        foreach ($decoded as $item) {
            if (!is_string($item) && !is_int($item)) {
                throw SqlResultSetValueCouldNotBeConvertedException::create($value);
            }

            try {
                $enums[] = $enumClass::from($item);
            } catch (Throwable $exception) {
                throw SqlResultSetValueCouldNotBeConvertedException::create($value, $exception);
            }
        }

        return $enums;
    }
}
