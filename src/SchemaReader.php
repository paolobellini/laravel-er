<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr;

use Illuminate\Support\Facades\Schema;

final readonly class SchemaReader
{
    /**
     * @return array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>}>
     */
    public function read(): array
    {
        /** @var array<int, array<string, mixed>> $tables */
        $tables = Schema::getTables();
        $schema = [];

        foreach ($tables as $table) {
            /** @var string $tableName */
            $tableName = $table['name'];

            if ($this->isExcluded($tableName)) {
                continue;
            }

            /** @var array<int, array<string, mixed>> $columns */
            $columns = Schema::getColumns($tableName);

            /** @var array<int, array<string, mixed>> $foreignKeys */
            $foreignKeys = Schema::getForeignKeys($tableName);

            $schema[$tableName] = [
                'columns' => $columns,
                'foreignKeys' => $foreignKeys,
            ];
        }

        return $schema;
    }

    private function isExcluded(string $table): bool
    {
        /** @var list<string> $excluded */
        $excluded = config('er.excluded_tables', []);

        return in_array($table, $excluded);
    }
}
