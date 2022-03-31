<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMappingInterface;
use DBorsatto\SqlResultSetMapper\Configuration\RootMapping;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException;
use function array_key_exists;
use function array_values;

/**
 * @template T of object
 */
class Normalizer
{
    /**
     * @var RootMapping<T>
     */
    private RootMapping $rootMapping;

    /**
     * @param RootMapping<T> $rootMapping
     */
    public function __construct(RootMapping $rootMapping)
    {
        $this->rootMapping = $rootMapping;
    }

    /**
     * @param list<array<string, string|int|float|null>> $sqlResultSetRows
     *
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException
     *
     * @return list<array>
     */
    public function normalize(array $sqlResultSetRows): array
    {
        return $this->normalizeWithMapping($sqlResultSetRows, $this->rootMapping);
    }

    /**
     * @param list<array<string, string|int|float|null>> $sqlResultSetRows
     *
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException
     *
     * @return list<array>
     */
    private function normalizeWithMapping(array $sqlResultSetRows, ClassMappingInterface $classMapping): array
    {
        $dataForCurrentConfiguration = [];

        $configurationIdColumn = $classMapping->getResultSetIdColumn();
        foreach ($sqlResultSetRows as $sqlResultSetRow) {
            $extractedRowData = [];

            if (!array_key_exists($configurationIdColumn, $sqlResultSetRow)) {
                throw SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException::create(
                    $configurationIdColumn,
                );
            }

            // If the ID is missing, we might in the result of a LEFT JOIN
            // with no joined results, so we just move on to the next row.
            if ($sqlResultSetRow[$configurationIdColumn] === null) {
                continue;
            }

            $rowDataId = $sqlResultSetRow[$configurationIdColumn];

            foreach ($classMapping->getPropertyMappings() as $propertyMapping) {
                $propertyResultSetColumn = $propertyMapping->getResultSetColumn();
                if (!array_key_exists($propertyResultSetColumn, $sqlResultSetRow)) {
                    throw SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException::create(
                        $propertyResultSetColumn,
                    );
                }

                $extractedRowData[$propertyMapping->getObjectProperty()] = $sqlResultSetRow[$propertyResultSetColumn];
            }

            foreach ($classMapping->getRelationMappings() as $relationMapping) {
                $extractedRowData[$relationMapping->getObjectProperty()] = $this->normalizeWithMapping(
                    $this->filterRows($sqlResultSetRows, $configurationIdColumn, $rowDataId),
                    $relationMapping,
                );
            }

            $dataForCurrentConfiguration[$rowDataId] = $extractedRowData;
        }

        /** @psalm-suppress InvalidScalarArgument */
        return array_values($dataForCurrentConfiguration);
    }

    /**
     * @param list<array<string, string|int|float|null>> $rows
     * @param string                                     $column
     * @param string|int|float                           $value
     *
     * @return list<array<string, string|int|float|null>>
     */
    private function filterRows(array $rows, string $column, $value): array
    {
        $filtered = [];
        $found = false;
        foreach ($rows as $row) {
            if ($row[$column] !== $value) {
                if ($found) {
                    return $filtered;
                }

                continue;
            }

            $filtered[] = $row;
            $found = true;
        }

        return $filtered;
    }
}
