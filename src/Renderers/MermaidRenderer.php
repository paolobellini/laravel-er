<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Support\ColumnAttributes;

final class MermaidRenderer extends AbstractRenderer
{
    protected function header(): string
    {
        return 'erDiagram';
    }

    public function wrapOutput(): string
    {
        return "```mermaid\n%s\n```\n";
    }

    public function outputExtension(): string
    {
        return 'md';
    }

    /**
     * @param  array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}  $tableData
     */
    protected function renderTable(string $tableName, array $tableData): string
    {
        $indexes = $tableData['indexes'];
        $foreignKeys = $tableData['foreignKeys'];

        $lines = ["    {$tableName} {"];

        foreach ($tableData['columns'] as $column) {
            $lines[] = $this->renderColumn($column, $indexes, $foreignKeys);
        }

        $lines[] = '    }';

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $column
     * @param  array<int, array<string, mixed>>  $indexes
     * @param  array<int, array<string, mixed>>  $foreignKeys
     */
    private function renderColumn(array $column, array $indexes, array $foreignKeys): string
    {
        /** @var string $name */
        $name = $column['name'];
        /** @var string $type */
        $type = $column['type_name'];

        $parts = array_filter([
            "        {$type} {$name}",
            $this->columnAttributes($column, $indexes, $foreignKeys),
            '"'.$this->columnComment($column).'"',
        ]);

        return implode(' ', $parts);
    }

    /**
     * @param  array<string, mixed>  $column
     * @param  array<int, array<string, mixed>>  $indexes
     * @param  array<int, array<string, mixed>>  $foreignKeys
     */
    private function columnAttributes(array $column, array $indexes, array $foreignKeys): string
    {
        $markers = array_filter([
            ColumnAttributes::isPrimaryKey($column['name'], $indexes) ? 'PK' : null,
            ColumnAttributes::isForeignKey($column['name'], $foreignKeys) ? 'FK' : null,
        ]);

        return implode(',', $markers);
    }

    /**
     * @param  array<string, mixed>  $column
     */
    private function columnComment(array $column): string
    {
        /** @var bool $nullable */
        $nullable = $column['nullable'] ?? false;

        return $nullable ? 'nullable' : 'not null';
    }

    /**
     * @param  array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}  $tableData
     * @param  array<string, mixed>  $fk
     */
    protected function renderRelationship(string $tableName, array $tableData, array $fk): string
    {
        $foreignTable = $fk['foreign_table'];
        $fkColumn = $fk['columns'][0];

        $isNullable = ColumnAttributes::isNullable($fkColumn, $tableData['columns']);
        $isUnique = ColumnAttributes::isUnique($fkColumn, $tableData['indexes']);

        $leftSymbol = $isNullable ? 'o|' : '||';
        $rightSymbol = $isUnique ? ($isNullable ? 'o|' : '||') : 'o{';
        $label = $isUnique ? 'has one' : 'has many';

        return "    {$foreignTable} {$leftSymbol}--{$rightSymbol} {$tableName} : \"{$label}\"";
    }
}
