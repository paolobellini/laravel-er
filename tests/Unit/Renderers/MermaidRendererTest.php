<?php

use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;

beforeEach(function (): void {
    $this->renderer = new MermaidRenderer;
});

it('renders empty schema with only header', function (): void {
    expect($this->renderer->render([]))->toBe("erDiagram\n");
});

it('renders a single table with columns', function (): void {
    $schema = [
        'users' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false, 'auto_increment' => true],
                ['name' => 'name', 'type_name' => 'varchar', 'nullable' => false],
                ['name' => 'email', 'type_name' => 'varchar', 'nullable' => true],
            ],
            'foreignKeys' => [],
            'indexes' => [
                ['name' => 'users_id_primary', 'columns' => ['id'], 'unique' => true, 'primary' => true],
            ],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('erDiagram')
        ->toContain('users {')
        ->toContain('integer id PK "not null"')
        ->toContain('varchar name "not null"')
        ->toContain('varchar email "nullable"');
});

it('renders PK and FK markers on columns', function (): void {
    $schema = [
        'posts' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
                ['name' => 'user_id', 'type_name' => 'integer', 'nullable' => false],
                ['name' => 'title', 'type_name' => 'varchar', 'nullable' => false],
            ],
            'foreignKeys' => [
                ['columns' => ['user_id'], 'foreign_table' => 'users', 'foreign_columns' => ['id'], 'on_update' => 'no action', 'on_delete' => 'no action'],
            ],
            'indexes' => [
                ['name' => 'posts_id_primary', 'columns' => ['id'], 'unique' => true, 'primary' => true],
            ],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('integer id PK "not null"')
        ->toContain('integer user_id FK "not null"')
        ->toContain('varchar title "not null"');
});

it('renders foreign key relationships', function (): void {
    $schema = [
        'users' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
            ],
            'foreignKeys' => [],
            'indexes' => [
                ['name' => 'users_id_primary', 'columns' => ['id'], 'unique' => true, 'primary' => true],
            ],
        ],
        'posts' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
                ['name' => 'user_id', 'type_name' => 'integer', 'nullable' => false],
            ],
            'foreignKeys' => [
                ['columns' => ['user_id'], 'foreign_table' => 'users', 'foreign_columns' => ['id'], 'on_update' => 'no action', 'on_delete' => 'no action'],
            ],
            'indexes' => [
                ['name' => 'posts_id_primary', 'columns' => ['id'], 'unique' => true, 'primary' => true],
            ],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)->toContain('users ||--o{ posts : "has many"');
});

it('renders nullable columns correctly', function (): void {
    $schema = [
        'profiles' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
                ['name' => 'bio', 'type_name' => 'text', 'nullable' => true],
            ],
            'foreignKeys' => [],
            'indexes' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('integer id "not null"')
        ->toContain('text bio "nullable"');
});

it('returns markdown wrap output', function (): void {
    expect($this->renderer->wrapOutput())->toBe("```mermaid\n%s\n```\n");
});

it('returns md output extension', function (): void {
    expect($this->renderer->outputExtension())->toBe('md');
});
