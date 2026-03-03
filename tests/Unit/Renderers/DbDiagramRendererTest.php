<?php

use PaoloBellini\LaravelEr\Data\Schema;
use PaoloBellini\LaravelEr\Renderers\DbDiagramRenderer;

beforeEach(function (): void {
    $this->renderer = new DbDiagramRenderer;
});

it('renders empty string', function (): void {
    expect($this->renderer->render(new Schema([])))->toBe('');
});

it('returns passthrough wrap output', function (): void {
    expect($this->renderer->wrapOutput())->toBe('%s');
});

it('returns dbml output extension', function (): void {
    expect($this->renderer->outputExtension())->toBe('dbml');
});
