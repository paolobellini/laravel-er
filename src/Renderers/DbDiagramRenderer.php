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
        return 'dbdiagram path';
    }

    public function wrapOutput(): string
    {
        // TODO: Implement wrapOutput() method.
        return 'dbdiagram wrap';
    }

    public function outputExtension(): string
    {
        // TODO: Implement outputExtension() method.
        return 'dbdiagram extension';
    }
}
