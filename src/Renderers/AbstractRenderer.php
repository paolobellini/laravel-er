<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Schema;
use PaoloBellini\LaravelEr\Data\Table;

abstract class AbstractRenderer implements SchemaRenderer
{
    public function render(Schema $schema): string
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
        return '';
    }

    protected function renderTables(Schema $schema): string
    {
        $lines = [];

        foreach ($schema->tables as $table) {
            $lines[] = $this->renderTable($table);
        }

        return implode("\n", $lines);
    }

    protected function renderRelationships(Schema $schema): string
    {
        $lines = [];

        foreach ($schema->tables as $table) {
            foreach ($table->foreignKeys as $fk) {
                $lines[] = $this->renderRelationship($table, $fk);
            }
        }

        return implode("\n", $lines);
    }

    abstract protected function renderTable(Table $table): string;

    abstract protected function renderRelationship(Table $table, ForeignKey $fk): string;
}
