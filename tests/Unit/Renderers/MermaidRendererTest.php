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
                new Column('id', 'integer', false, null),
                new Column('name', 'varchar', false, null),
                new Column('email', 'varchar', true, null),
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
                new Column('id', 'integer', false, null),
                new Column('user_id', 'integer', false, null),
                new Column('title', 'varchar', false, null),
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
                new Column('id', 'integer', false, null),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
        new Table(
            name: 'posts',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('user_id', 'integer', false, null),
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
                new Column('id', 'integer', false, null),
                new Column('bio', 'text', true, null),
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

it('renders UK marker on unique columns', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('email', 'varchar', false, null),
                new Column('name', 'varchar', false, null),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
                new Index(columns: ['email'], unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('integer id PK "not null"')
        ->toContain('varchar email UK "not null"')
        ->toContain('varchar name "not null"');
});

it('renders default value in column comment', function (): void {
    $schema = new Schema([
        new Table(
            name: 'posts',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('status', 'varchar', false, 'draft'),
                new Column('views', 'integer', false, '0'),
                new Column('deleted_at', 'timestamp', true, 'NULL'),
                new Column('title', 'varchar', false, null),
            ],
            foreignKeys: [],
            indexes: [],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('varchar status "not null, default: draft"')
        ->toContain('integer views "not null, default: 0"')
        ->toContain('timestamp deleted_at "nullable, default: NULL"')
        ->toContain('varchar title "not null"');
});

it('returns markdown wrap output', function (): void {
    expect($this->renderer->wrapOutput("erDiagram\n"))->toBe("```mermaid\nerDiagram\n\n```\n");
});

it('returns md output extension', function (): void {
    expect($this->renderer->outputExtension())->toBe('md');
});

it('renders each table exactly once', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [new Column('id', 'integer', false, null)],
            foreignKeys: [],
            indexes: [],
        ),
        new Table(
            name: 'posts',
            columns: [new Column('id', 'integer', false, null)],
            foreignKeys: [],
            indexes: [],
        ),
        new Table(
            name: 'comments',
            columns: [new Column('id', 'integer', false, null)],
            foreignKeys: [],
            indexes: [],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect(substr_count((string) $output, 'users {'))->toBe(1)
        ->and(substr_count((string) $output, 'posts {'))->toBe(1)
        ->and(substr_count((string) $output, 'comments {'))->toBe(1)
        ->and(substr_count((string) $output, 'erDiagram'))->toBe(1);
});
