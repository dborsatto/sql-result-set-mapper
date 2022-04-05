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
     * @param list<array<string, string|int|null>> $sqlResultSetRows
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
        ClassMappingInterface $classMapping,
        array $currentIds = []
    ): array {
        $dataForCurrentConfiguration = [];

        $configurationIdColumn = $classMapping->getResultSetIdColumn();
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
                    $this->filterSqlResultSetRows($sqlResultSetRows, $currentIndex, $currentIds),
                    $relationMapping,
                    $currentIds,
                );
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
    private function getRowId(array $sqlResultSetRow, ClassMappingInterface $classMapping)
    {
        $configurationIdColumn = $classMapping->getResultSetIdColumn();
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
