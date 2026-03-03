<?php

use PaoloBellini\LaravelEr\Data\Column;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Index;
use PaoloBellini\LaravelEr\Data\Schema;
use PaoloBellini\LaravelEr\Data\Table;
use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;

beforeEach(function (): void {
    $this->renderer = new MermaidRenderer;
});

it('renders empty schema with only header', function (): void {
    expect($this->renderer->render(new Schema([])))->toBe("```mermaid\nerDiagram\n\n```\n");
});

it('renders a single table with columns', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [
                new Column('id', 'integer'),
                new Column('name', 'varchar'),
                new Column('email', 'varchar', nullable: true),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('erDiagram')
        ->toContain('users {')
        ->toContain('integer id PK "not null"')
        ->toContain('varchar name "not null"')
        ->toContain('varchar email "nullable"');
});

it('renders PK and FK markers on columns', function (): void {
    $schema = new Schema([
        new Table(
            name: 'posts',
            columns: [
                new Column('id', 'integer'),
                new Column('user_id', 'integer'),
                new Column('title', 'varchar'),
            ],
            foreignKeys: [
                new ForeignKey(columns: ['user_id'], foreignTable: 'users'),
            ],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('integer id PK "not null"')
        ->toContain('integer user_id FK "not null"')
        ->toContain('varchar title "not null"');
});

it('renders foreign key relationships', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [
                new Column('id', 'integer'),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
        new Table(
            name: 'posts',
            columns: [
                new Column('id', 'integer'),
                new Column('user_id', 'integer'),
            ],
            foreignKeys: [
                new ForeignKey(columns: ['user_id'], foreignTable: 'users'),
            ],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)->toContain('users ||--o{ posts : "has many"');
});

it('renders nullable columns correctly', function (): void {
    $schema = new Schema([
        new Table(
            name: 'profiles',
            columns: [
                new Column('id', 'integer'),
                new Column('bio', 'text', nullable: true),
            ],
            foreignKeys: [],
            indexes: [],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('integer id "not null"')
        ->toContain('text bio "nullable"');
});

it('returns markdown wrap output', function (): void {
    expect($this->renderer->wrapOutput("erDiagram\n"))->toBe("```mermaid\nerDiagram\n\n```\n");
});

it('returns md output extension', function (): void {
    expect($this->renderer->outputExtension())->toBe('md');
});
