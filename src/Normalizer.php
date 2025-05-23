<?php

declare(strict_types=1);

namespace DBorsatto\SqlResultSetMapper;

use DBorsatto\SqlResultSetMapper\Configuration\ClassMapping;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException;
use DBorsatto\SqlResultSetMapper\Exception\SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException;

use function array_key_exists;
use function array_values;

/**
 * @template T of object
 */
final class Normalizer
{
    /**
     * @param ClassMapping<T> $classMapping
     */
    public function __construct(private ClassMapping $classMapping)
    {
    }

    /**
     * @param list<array<string, string|int|null>> $sqlResultSetRows
     *
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException
     *
     * @return list<array>
     */
    public function normalize(array $sqlResultSetRows): array
    {
        return $this->normalizeWithMapping($sqlResultSetRows, $this->classMapping);
    }

    /**
     * @param list<array<string, string|int|null>> $sqlResultSetRows
     * @param array<string, string|int>            $currentIds
     *
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException
     *
     * @return list<array>
     */
    private function normalizeWithMapping(
        array $sqlResultSetRows,
        ClassMapping $classMapping,
        array $currentIds = [],
    ): array {
        $dataForCurrentConfiguration = [];

        $configurationIdColumn = $classMapping->resultSetIdColumn;
        foreach ($sqlResultSetRows as $currentIndex => $sqlResultSetRow) {
            $extractedRowData = [];

            $rowDataId = $this->getRowId($sqlResultSetRow, $classMapping);

            // If the ID is missing, we might be in the result of a LEFT JOIN
            // with no joined results, so we just move on to the next row.
            if ($rowDataId === null) {
                continue;
            }

            // If the ID is already present, it means we already processed the current result row
            // and we're dealing with a JOIN duplicate.
            if (array_key_exists($rowDataId, $dataForCurrentConfiguration)) {
                continue;
            }

            $currentIds[$configurationIdColumn] = $rowDataId;

            foreach ($classMapping->propertyMappings as $propertyMapping) {
                $propertyResultSetColumn = $propertyMapping->resultSetColumn;
                if (!array_key_exists($propertyResultSetColumn, $sqlResultSetRow)) {
                    throw SqlResultSetCouldNotBeNormalizedBecauseItIsMissingConfiguredPropertyColumnException::create(
                        $propertyResultSetColumn,
                    );
                }

                $extractedRowData[$propertyMapping->objectProperty] = $sqlResultSetRow[$propertyResultSetColumn];
            }

            foreach ($classMapping->relationMappings as $relationMapping) {
                $extractedRowDataForRelation = $this->normalizeWithMapping(
                    $this->filterSqlResultSetRows($sqlResultSetRows, $currentIndex, $currentIds),
                    $relationMapping->classMapping,
                    $currentIds,
                );

                $extractedRowData[$relationMapping->objectProperty] = $relationMapping->isMultiple
                    ? $extractedRowDataForRelation
                    : ($extractedRowDataForRelation[0] ?? null);
            }

            $dataForCurrentConfiguration[$rowDataId] = $extractedRowData;
        }

        /** @psalm-suppress InvalidScalarArgument */
        return array_values($dataForCurrentConfiguration);
    }

    /**
     * @param array<string, string|int|null> $sqlResultSetRow
     *
     * @throws SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException
     *
     * @return int|string|null
     */
    private function getRowId(array $sqlResultSetRow, ClassMapping $classMapping)
    {
        $configurationIdColumn = $classMapping->resultSetIdColumn;
        if (!array_key_exists($configurationIdColumn, $sqlResultSetRow)) {
            throw SqlResultSetCouldNotBeNormalizedBecauseItIsMissingRequiredIdColumnException::create(
                $configurationIdColumn,
            );
        }

        // If the ID is missing, we might be in the result of a LEFT JOIN
        // with no joined results, so we just move on to the next row.
        if ($sqlResultSetRow[$configurationIdColumn] === null) {
            return null;
        }

        return $sqlResultSetRow[$configurationIdColumn];
    }

    /**
     * @param list<array<string, string|int|null>> $sqlResultSetRows
     * @param array<string, string|int>            $currentIds
     *
     * @return list<array<string, string|int|null>>
     */
    private function filterSqlResultSetRows(array $sqlResultSetRows, int $startingIndex, array $currentIds): array
    {
        $filteredSqlResultSetRows = [];
        $currentIndex = $startingIndex;

        while (array_key_exists($currentIndex, $sqlResultSetRows)) {
            $sqlResultSetRow = $sqlResultSetRows[$currentIndex];
            if (!$this->hasSqlResultSetRowCurrentIds($sqlResultSetRow, $currentIds)) {
                return $filteredSqlResultSetRows;
            }

            $filteredSqlResultSetRows[] = $sqlResultSetRow;

            $currentIndex++;
        }

        return $filteredSqlResultSetRows;
    }

    /**
     * @param array<string, string|int|null> $sqlResultSetRow
     * @param array<string, string|int>      $currentIds
     */
    private function hasSqlResultSetRowCurrentIds(array $sqlResultSetRow, array $currentIds): bool
    {
        foreach ($currentIds as $idColumn => $idValue) {
            if (!array_key_exists($idColumn, $sqlResultSetRow)) {
                return false;
            }

            if ($sqlResultSetRow[$idColumn] !== $idValue) {
                return false;
            }
        }

        return true;
    }
}
