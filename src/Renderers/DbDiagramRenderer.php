<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Data\Column;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Table;
use PaoloBellini\LaravelEr\Support\ColumnAttributes;

final class DbDiagramRenderer extends AbstractRenderer
{
    public function wrapOutput(string $content): string
    {
        return "```dbdiagram\n{$content}\n```\n";
    }

    protected function renderTable(Table $table): string
    {
        $lines = [sprintf('Table %s {', $table->name)];

        foreach ($table->columns as $column) {
            $lines[] = $this->renderColumn($column, $table);
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    private function renderColumn(Column $column, Table $table): string
    {
        $attributes = $this->columnAttributes($column, $table);
        $attributePart = $attributes !== [] ? ' ['.implode(', ', $attributes).']' : '';

        return sprintf('  %s %s%s', $column->name, $column->type, $attributePart);
    }

    /**
     * @return list<string>
     */
    private function columnAttributes(Column $column, Table $table): array
    {
        $isPrimaryKey = ColumnAttributes::isPrimaryKey($column->name, $table->indexes);

        return array_values(array_filter([
            $isPrimaryKey ? 'primary key' : null,
            $column->nullable ? 'null' : 'not null',
            ! $isPrimaryKey && ColumnAttributes::isUnique($column->name, $table->indexes) ? 'unique' : null,
            ColumnAttributes::hasDefault($column) ? sprintf("default: '%s'", $column->default) : null,
        ]));
    }

    protected function renderRelationship(Table $table, ForeignKey $fk): string
    {
        $fkColumn = $fk->columns[0];

        $isUnique = ColumnAttributes::isUnique($fkColumn, $table->indexes);

        $symbol = $isUnique ? '-' : '>';

        return sprintf('Ref: %s.%s %s %s.id', $table->name, $fkColumn, $symbol, $fk->foreignTable);
    }
}
