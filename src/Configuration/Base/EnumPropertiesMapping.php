<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Configuration\Base;

use BackedEnum;
use Closure;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMappingConverterInterface;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetValueCouldNotBeConvertedException;
use JsonException;
use Override;
use ValueError;

use function array_is_list;
use function explode;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * @template T of BackedEnum
 *
 * @implements PropertyMappingConverterInterface<list<T>>
 */
final readonly class EnumPropertiesMapping extends PropertyMapping implements PropertyMappingConverterInterface
{
    /**
     * @param class-string<T>                        $enumClass
     * @param Closure(non-empty-string): list<mixed> $enumListConverter
     */
    private function __construct(
        string $objectProperty,
        string $resultSetColumn,
        private string $enumClass,
        private Closure $enumListConverter,
    ) {
        parent::__construct($objectProperty, $resultSetColumn);
    }

    /**
     * @template T1 of BackedEnum
     *
     * @param class-string<T1> $enumClass
     * @param non-empty-string $symbol
     */
    public static function fromSymbolSeparatedValues(
        string $objectProperty,
        string $resultSetColumn,
        string $enumClass,
        string $symbol = ',',
    ): self {
        return new self(
            $objectProperty,
            $resultSetColumn,
            $enumClass,
            static function (string $value) use ($symbol): array {
                /** @var non-empty-string $value */
                return explode($symbol, $value);
            },
        );
    }

    /**
     * @template T1 of BackedEnum
     *
     * @param class-string<T1> $enumClass
     */
    public static function fromJsonList(string $objectProperty, string $resultSetColumn, string $enumClass): self
    {
        return new self(
            $objectProperty,
            $resultSetColumn,
            $enumClass,
            static function (string $value): array {
                try {
                    /** @var array $decoded */
                    $decoded = json_decode($value, associative: true, flags: JSON_THROW_ON_ERROR);
                } catch (JsonException $exception) {
                    throw SqlResultSetValueCouldNotBeConvertedException::create($value, $exception);
                }

                if (!array_is_list($decoded)) {
                    throw SqlResultSetValueCouldNotBeConvertedException::create($value);
                }

                return $decoded;
            },
        );
    }

    #[Override]
    public function convert(bool|float|int|string|null $value): ?array
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

        $enumListConverter = $this->enumListConverter;
        $enumClass = $this->enumClass;

        $array = $enumListConverter($value);

        $enums = [];
        foreach ($array as $item) {
            if (!is_string($item)) {
                throw SqlResultSetValueCouldNotBeConvertedException::create($value);
            }

            try {
                $enums[] = $enumClass::from($item);
            } catch (ValueError $exception) {
                throw SqlResultSetValueCouldNotBeConvertedException::create($value, $exception);
            }
        }

        return $enums;
    }
}
