<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr;

use Illuminate\Support\Facades\Schema;

final readonly class SchemaReader
{
    /**
     * @return array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}>
     */
    public function read(): array
    {
        /** @var list<string> $allSchemaTables */
        $allSchemaTables = Schema::getTableListing(schemaQualified: false);
        /** @var list<string> $excluded */
        $excluded = config('er.excluded_tables', []);

        /** @var array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}> */
        return collect($allSchemaTables)
            ->reject(fn (string $table): bool => in_array($table, $excluded))
            ->mapWithKeys(fn (string $table): array => [
                $table => [
                    'columns' => Schema::getColumns($table),
                    'foreignKeys' => Schema::getForeignKeys($table),
                    'indexes' => Schema::getIndexes($table),
                ],
            ])
            ->all();
    }
}
