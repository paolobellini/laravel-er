<?php

use PaoloBellini\LaravelEr\Context;
use PaoloBellini\LaravelEr\Renderers\DbDiagramRenderer;
use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;
use PaoloBellini\LaravelEr\SchemaReader;

beforeEach(function (): void {
    $this->context = new Context(new SchemaReader);
});

it('executes strategy for mermaid renderer', function (): void {
    $strategy = app(MermaidRenderer::class);

    $this->context->setStrategy($strategy);

    $result = $this->context->executeStrategy();

    expect($result)->toBe("erDiagram\n");
});

it('executes strategy for dbdiagram renderer', function (): void {
    $strategy = app(DbDiagramRenderer::class);

    $this->context->setStrategy($strategy);

    $result = $this->context->executeStrategy();

    expect($result)->toBe('dbdiagram path');
});
