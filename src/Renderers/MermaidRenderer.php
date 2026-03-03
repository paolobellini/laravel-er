<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Data\Column;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Table;
use PaoloBellini\LaravelEr\Support\ColumnAttributes;

final class MermaidRenderer extends AbstractRenderer
{
    protected function renderHeader(): string
    {
        return 'erDiagram';
    }

    public function wrapOutput(string $content): string
    {
        return "```mermaid\n{$content}\n```\n";
    }

    public function outputExtension(): string
    {
        return 'md';
    }

    protected function renderTable(Table $table): string
    {
        $lines = [sprintf('    %s {', $table->name)];

        foreach ($table->columns as $column) {
            $lines[] = $this->renderColumn($column, $table);
        }

        $lines[] = '    }';

        return implode("\n", $lines);
    }

    private function renderColumn(Column $column, Table $table): string
    {
        $parts = array_filter([
            sprintf('        %s %s', $column->typeName, $column->name),
            $this->columnAttributes($column, $table),
            '"'.$this->columnComment($column).'"',
        ]);

        return implode(' ', $parts);
    }

    private function columnAttributes(Column $column, Table $table): string
    {
        $markers = array_filter([
            ColumnAttributes::isPrimaryKey($column->name, $table->indexes) ? 'PK' : null,
            ColumnAttributes::isForeignKey($column->name, $table->foreignKeys) ? 'FK' : null,
            ColumnAttributes::isUnique($column->name, $table->indexes) ? 'UK' : null,
        ]);

        return implode(',', $markers);
    }

    private function columnComment(Column $column): string
    {
        return $column->nullable ? 'nullable' : 'not null';
    }

    protected function renderRelationship(Table $table, ForeignKey $fk): string
    {
        $fkColumn = $fk->columns[0];

        $isNullable = ColumnAttributes::isNullable($fkColumn, $table->columns);
        $isUnique = ColumnAttributes::isUnique($fkColumn, $table->indexes);

        $leftSymbol = $isNullable ? 'o|' : '||';
        $rightSymbol = $isUnique ? ($isNullable ? 'o|' : '||') : 'o{';
        $label = $isUnique ? 'has one' : 'has many';

        return sprintf('    %s %s--%s %s : "%s"', $fk->foreignTable, $leftSymbol, $rightSymbol, $table->name, $label);
    }
}
