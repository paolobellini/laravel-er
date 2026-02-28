<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;

final readonly class MermaidRenderer implements SchemaRenderer
{
    /**
     * @param  array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>}>  $schema
     */
    public function render(array $schema): string
    {
        // TODO: Implement wrapOutput() method.
        return 'mermaid path';
    }

    public function wrapOutput(): string
    {
        // TODO: Implement wrapOutput() method.
        return 'mermaid wrap';
    }

    public function outputExtension(): string
    {
        // TODO: Implement outputExtension() method.
        return 'mermaid extension';
    }
}
