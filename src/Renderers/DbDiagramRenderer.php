<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;
use PaoloBellini\LaravelEr\Data\Schema;

final readonly class DbDiagramRenderer implements SchemaRenderer
{
    public function render(Schema $schema): string
    {
        // TODO: Implement render() method.
        return sprintf($this->wrapOutput(), '');
    }

    public function wrapOutput(): string
    {
        return '%s';
    }

    public function outputExtension(): string
    {
        return 'dbml';
    }
}
