<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Support;

final class ColumnAttributes
{
    /**
     * @param  array<int, array<string, mixed>>  $indexes
     */
    public static function isPrimaryKey(string $columnName, array $indexes): bool
    {
        foreach ($indexes as $index) {
            if (! empty($index['primary']) && in_array($columnName, (array) $index['columns'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, array<string, mixed>>  $foreignKeys
     */
    public static function isForeignKey(string $columnName, array $foreignKeys): bool
    {
        foreach ($foreignKeys as $fk) {
            if (in_array($columnName, (array) $fk['columns'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, array<string, mixed>>  $indexes
     */
    public static function isUnique(string $columnName, array $indexes): bool
    {
        foreach ($indexes as $index) {
            if (! empty($index['unique']) && in_array($columnName, (array) $index['columns'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, array<string, mixed>>  $foreignKeys
     */
    public static function getForeignTable(string $columnName, array $foreignKeys): ?string
    {
        foreach ($foreignKeys as $fk) {
            if (in_array($columnName, (array) $fk['columns'], true)) {
                /** @var string */
                return $fk['foreign_table'] ?? null;
            }
        }

        return null;
    }

    /**
     * @param  array<int, array<string, mixed>>  $columns
     */
    public static function isNullable(string $columnName, array $columns): bool
    {
        foreach ($columns as $column) {
            if ($column['name'] === $columnName) {
                return (bool) ($column['nullable'] ?? false);
            }
        }

        return false;
    }
}
