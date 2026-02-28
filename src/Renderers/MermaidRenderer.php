<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;

final readonly class MermaidRenderer implements SchemaRenderer
{
    /**
     * @param array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>}> $schema
     */
    public function render(array $schema): string
    {
        $lines = ['erDiagram'];

        foreach ($schema as $tableName => $tableData) {
            $lines[] = '';
            $lines[] = sprintf('    %s {', $tableName);

            $foreignKeyColumns = array_map(
                static function (array $fk): string {
                    /** @var array<int, string> $columns */
                    $columns = $fk['columns'];

                    return $columns[0];
                },
                $tableData['foreignKeys'],
            );

            foreach ($tableData['columns'] as $column) {
                /** @var string $typeName */
                $typeName = $column['type_name'];
                /** @var string $name */
                $name = $column['name'];
                $isFk = in_array($name, $foreignKeyColumns, true);
                $lines[] = sprintf('        %s %s', $typeName, $name) . $this->getColumnAttributes($column, $isFk);
            }

            $lines[] = '    }';
        }

        $lines[] = '';

        foreach ($schema as $tableName => $tableData) {
            foreach ($tableData['foreignKeys'] as $fk) {
                /** @var string $foreignTable */
                $foreignTable = $fk['foreign_table'];
                $lines[] = sprintf('    %s ||--o{ %s : "has many"', $foreignTable, $tableName);
            }
        }

        return implode("\n", $lines) . "\n";
    }

    public function wrapOutput(): string
    {
        // TODO: Implement wrapOutput() method.
        return '';
    }

    public function outputExtension(): string
    {
        // TODO: Implement outputExtension() method.
        return '';
    }

}