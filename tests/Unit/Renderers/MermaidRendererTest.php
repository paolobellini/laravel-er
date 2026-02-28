<?php

use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;

beforeEach(function (): void {
    $this->renderer = new MermaidRenderer;
});

it('renders empty schema', function (): void {
    $output = $this->renderer->render([]);

    expect($output)->toContain('erDiagram');
});

it('renders table with columns', function (): void {
    $schema = [
        'users' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
                ['name' => 'name', 'type_name' => 'varchar', 'nullable' => false],
                ['name' => 'email', 'type_name' => 'varchar', 'nullable' => false],
            ],
            'foreignKeys' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('users {')
        ->toContain('integer id PK "not null"')
        ->toContain('varchar name "not null"')
        ->toContain('varchar email "not null"');
});

it('marks id as primary key', function (): void {
    $schema = [
        'posts' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
            ],
            'foreignKeys' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)->toContain('integer id PK "not null"');
});

it('marks uuid as primary key', function (): void {
    $schema = [
        'orders' => [
            'columns' => [
                ['name' => 'uuid', 'type_name' => 'char', 'nullable' => false],
            ],
            'foreignKeys' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)->toContain('char uuid PK "not null"');
});

it('marks nullable columns', function (): void {
    $schema = [
        'profiles' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
                ['name' => 'bio', 'type_name' => 'text', 'nullable' => true],
            ],
            'foreignKeys' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('text bio "nullable"')
        ->toContain('integer id PK "not null"');
});

it('combines pk and nullable attributes', function (): void {
    $schema = [
        'items' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => true],
            ],
            'foreignKeys' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)->toContain('integer id PK "nullable"');
});

it('renders foreign key relationships', function (): void {
    $schema = [
        'users' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
            ],
            'foreignKeys' => [],
        ],
        'posts' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
                ['name' => 'user_id', 'type_name' => 'integer', 'nullable' => false],
            ],
            'foreignKeys' => [
                ['foreign_table' => 'users', 'columns' => ['user_id']],
            ],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)->toContain('users ||--o{ posts : "has many"');
});

it('renders multiple tables', function (): void {
    $schema = [
        'users' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
            ],
            'foreignKeys' => [],
        ],
        'posts' => [
            'columns' => [
                ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
            ],
            'foreignKeys' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('users {')
        ->toContain('posts {');
});

it('regular column has no attributes', function (): void {
    $schema = [
        'tags' => [
            'columns' => [
                ['name' => 'name', 'type_name' => 'varchar', 'nullable' => false],
            ],
            'foreignKeys' => [],
        ],
    ];

    $output = $this->renderer->render($schema);

    expect($output)->toContain('varchar name "not null"');
});
