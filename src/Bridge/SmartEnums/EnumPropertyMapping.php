<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper\Bridge\SmartEnums;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SqlResultSetMapper\Configuration\PropertyMapping;

class EnumPropertyMapping extends PropertyMapping
{
    /**
     * @param class-string<EnumInterface> $enumClass
     */
    public function __construct(string $objectProperty, string $resultSetColumn, string $enumClass)
    {
        parent::__construct(
            $objectProperty,
            $resultSetColumn,
            static function (?string $value) use ($enumClass): ?EnumInterface {
                if ($value === null) {
                    return null;
                }

                $factory = new EnumFactory($enumClass);

                return $factory->fromValue($value);
            },
        );
    }
}
