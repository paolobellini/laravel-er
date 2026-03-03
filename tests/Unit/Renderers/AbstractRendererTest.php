<?php

use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Schema;
use PaoloBellini\LaravelEr\Data\Table;
use PaoloBellini\LaravelEr\Renderers\AbstractRenderer;

it('renders with default empty header', function (): void {
    $renderer = new class extends AbstractRenderer
    {
        protected function renderTable(Table $table): string
        {
            return $table->name;
        }

        protected function renderRelationship(Table $table, ForeignKey $fk): string
        {
            return '';
        }

        public function outputExtension(): string
        {
            return '';
        }

        public function wrapOutput(): string
        {
            return '';
        }
    };

    $schema = new Schema([
        new Table('users', [], [], []),
    ]);

    expect($renderer->render($schema))->toBe("users\n");
});
