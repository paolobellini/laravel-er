<?php

use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;

beforeEach(function (): void {
    $this->renderer = new MermaidRenderer;
});

it('renders empty string', function (): void {
    expect($this->renderer->render([]))->toBe('mermaid path');
});

it('returns empty wrap output', function (): void {
    expect($this->renderer->wrapOutput())->toBe('mermaid wrap');
});

it('returns empty output extension', function (): void {
    expect($this->renderer->outputExtension())->toBe('mermaid extension');
});
