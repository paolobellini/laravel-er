<?php

use PaoloBellini\LaravelEr\Context;
use PaoloBellini\LaravelEr\Renderers\DbDiagramRenderer;
use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;
use PaoloBellini\LaravelEr\SchemaReader;

beforeEach(function (): void {
    $this->context = new Context(new SchemaReader);
    $this->outputPath = sys_get_temp_dir();
    config(['er.output_path' => $this->outputPath]);
    config(['er.output_filename' => 'er-diagram']);
});

it('executes strategy for mermaid renderer and writes file', function (): void {
    $strategy = app(MermaidRenderer::class);

    $this->context->setStrategy($strategy);

    $result = $this->context->executeStrategy();

    $expectedPath = $this->outputPath.'/er-diagram.md';

    expect($result)->toBe($expectedPath)
        ->and(file_exists($expectedPath))->toBeTrue()
        ->and(file_get_contents($expectedPath))->toContain('erDiagram');

    @unlink($expectedPath);
});

it('executes strategy for dbdiagram renderer and writes file', function (): void {
    $strategy = app(DbDiagramRenderer::class);

    $this->context->setStrategy($strategy);

    $result = $this->context->executeStrategy();

    $expectedPath = $this->outputPath.'/er-diagram.md';

    expect($result)->toBe($expectedPath)
        ->and(file_exists($expectedPath))->toBeTrue();

    @unlink($expectedPath);
});
