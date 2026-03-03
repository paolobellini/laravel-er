<?php

use PaoloBellini\LaravelEr\Data\Schema;
use PaoloBellini\LaravelEr\Renderers\DbDiagramRenderer;

beforeEach(function (): void {
    $this->renderer = new DbDiagramRenderer;
});

it('renders empty string', function (): void {
    expect($this->renderer->render(new Schema([])))->toBe('dbdiagram path');
});

it('returns empty wrap output', function (): void {
    expect($this->renderer->wrapOutput())->toBe('dbdiagram wrap');
});

it('returns empty output extension', function (): void {
    expect($this->renderer->outputExtension())->toBe('dbdiagram extension');
});
