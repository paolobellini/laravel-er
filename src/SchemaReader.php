<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr;

use Illuminate\Support\Facades\Schema;
use PaoloBellini\LaravelEr\Data\Column;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Index;
use PaoloBellini\LaravelEr\Data\Schema as SchemaData;
use PaoloBellini\LaravelEr\Data\Table;

final readonly class SchemaReader
{
    public function read(): SchemaData
    {
        /** @var list<string> $allSchemaTables */
        $allSchemaTables = Schema::getTableListing(schemaQualified: false);
        /** @var list<string> $excluded */
        $excluded = config('er.excluded_tables', []);

        /** @var list<Table> $tables */
        $tables = collect($allSchemaTables)
            ->reject(fn (string $table): bool => in_array($table, $excluded))
            ->map(function (string $tableName): Table {
                /** @var list<array{name: string, type_name: string, nullable: bool}> $rawColumns */
                $rawColumns = Schema::getColumns($tableName);
                /** @var list<array{columns: list<string>, foreign_table: string}> $rawForeignKeys */
                $rawForeignKeys = Schema::getForeignKeys($tableName);
                /** @var list<array{columns: list<string>, primary: bool, unique: bool}> $rawIndexes */
                $rawIndexes = Schema::getIndexes($tableName);

                return new Table(
                    name: $tableName,
                    columns: array_map(
                        fn (array $col): Column => new Column(
                            name: $col['name'],
                            typeName: $col['type_name'],
                            nullable: $col['nullable'],
                        ),
                        $rawColumns,
                    ),
                    foreignKeys: array_map(
                        fn (array $fk): ForeignKey => new ForeignKey(
                            columns: $fk['columns'],
                            foreignTable: $fk['foreign_table'],
                        ),
                        $rawForeignKeys,
                    ),
                    indexes: array_map(
                        fn (array $idx): Index => new Index(
                            columns: $idx['columns'],
                            primary: $idx['primary'],
                            unique: $idx['unique'],
                        ),
                        $rawIndexes,
                    ),
                );
            })
            ->values()
            ->all();

        return new SchemaData($tables);
    }
}
