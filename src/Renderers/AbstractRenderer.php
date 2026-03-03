<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;

abstract class AbstractRenderer implements SchemaRenderer
{
    /**
     * @param  array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}>  $schema
     */
    public function render(array $schema): string
    {
        $sections = array_filter([
            $this->renderHeader(),
            $this->renderTables($schema),
            $this->renderRelationships($schema),
        ]);

        return implode("\n", $sections)."\n";
    }

    protected function renderHeader(): string
    {
        return method_exists($this, 'header') ? $this->header() : '';
    }

    /**
     * @param  array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}>  $schema
     */
    protected function renderTables(array $schema): string
    {
        $lines = [];

        foreach ($schema as $tableName => $tableData) {
            $lines[] = $this->renderTable($tableName, $tableData);
        }

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}>  $schema
     */
    protected function renderRelationships(array $schema): string
    {
        $lines = [];

        foreach ($schema as $tableName => $tableData) {
            foreach ($tableData['foreignKeys'] as $fk) {
                $lines[] = $this->renderRelationship($tableName, $tableData, $fk);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * @param  array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}  $tableData
     */
    abstract protected function renderTable(string $tableName, array $tableData): string;

    /**
     * @param  array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>, indexes: array<int, array<string, mixed>>}  $tableData
     * @param  array<string, mixed>  $fk
     */
    abstract protected function renderRelationship(string $tableName, array $tableData, array $fk): string;
}
