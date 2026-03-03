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

        public function wrapOutput(string $content): string
        {
            return "---\n{$content}---\n";
        }
    };

    $schema = new Schema([
        new Table('users', [], [], []),
    ]);

    expect($renderer->render($schema))->toBe("---\nusers\n---\n");
});

it('returns raw content when wrapOutput is passthrough', function (): void {
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

        public function wrapOutput(string $content): string
        {
            return $content;
        }
    };

    $schema = new Schema([
        new Table('users', [], [], []),
    ]);

    expect($renderer->render($schema))->toBe("users\n");
});
