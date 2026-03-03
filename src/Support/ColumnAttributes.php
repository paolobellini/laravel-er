<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Support;

use PaoloBellini\LaravelEr\Data\Column;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Index;

final class ColumnAttributes
{
    /**
     * @param  list<Index>  $indexes
     */
    public static function isPrimaryKey(string $columnName, array $indexes): bool
    {
        foreach ($indexes as $index) {
            if ($index->primary && in_array($columnName, $index->columns, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<ForeignKey>  $foreignKeys
     */
    public static function isForeignKey(string $columnName, array $foreignKeys): bool
    {
        foreach ($foreignKeys as $fk) {
            if (in_array($columnName, $fk->columns, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<Index>  $indexes
     */
    public static function isUnique(string $columnName, array $indexes): bool
    {
        foreach ($indexes as $index) {
            if ($index->unique && in_array($columnName, $index->columns, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<ForeignKey>  $foreignKeys
     */
    public static function getForeignTable(string $columnName, array $foreignKeys): ?string
    {
        foreach ($foreignKeys as $fk) {
            if (in_array($columnName, $fk->columns, true)) {
                return $fk->foreignTable;
            }
        }

        return null;
    }

    public static function hasDefault(Column $column): bool
    {
        return $column->default !== null;
    }

    /**
     * @param  list<Column>  $columns
     */
    public static function isNullable(string $columnName, array $columns): bool
    {
        foreach ($columns as $column) {
            if ($column->name === $columnName) {
                return $column->nullable;
            }
        }

        return false;
    }
}
