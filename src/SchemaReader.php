<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr;

use Illuminate\Support\Facades\Schema;

final readonly class SchemaReader
{
    public function read(): array
    {
        $tables = $this->getTables();
        $schema = [];

        foreach ($tables as $table) {
            $tableName = $table['name'];

            if ($this->isExcluded($tableName)) {
                continue;
            }

            $schema[$tableName] = [
                'columns' => $this->getColumns($tableName),
                'foreignKeys' => $this->getForeignKeys($tableName),
            ];
        }

        return $schema;
    }

    protected function getTables(): array
    {
        return Schema::getTables();
    }

    protected function getColumns(string $table): array
    {
        return Schema::getColumns($table);
    }

    protected function getForeignKeys(string $table): array
    {
        return Schema::getForeignKeys($table);
    }

    protected function isExcluded(string $table): bool
    {
        return in_array($table, config('er.excluded_tables', []));
    }
}