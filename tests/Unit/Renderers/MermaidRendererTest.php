<?php

namespace PaoloBellini\LaravelEr\Tests\Unit\Renderers;

use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;
use PHPUnit\Framework\TestCase;

class MermaidRendererTest extends TestCase
{
    private MermaidRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new MermaidRenderer;
    }

    public function test_it_renders_empty_schema(): void
    {
        $output = $this->renderer->render([]);

        $this->assertStringContainsString('erDiagram', $output);
    }

    public function test_it_renders_table_with_columns(): void
    {
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

        $this->assertStringContainsString('users {', $output);
        $this->assertStringContainsString('integer id PK', $output);
        $this->assertStringContainsString('varchar name', $output);
        $this->assertStringContainsString('varchar email', $output);
    }

    public function test_it_marks_id_as_primary_key(): void
    {
        $schema = [
            'posts' => [
                'columns' => [
                    ['name' => 'id', 'type_name' => 'integer', 'nullable' => false],
                ],
                'foreignKeys' => [],
            ],
        ];

        $output = $this->renderer->render($schema);

        $this->assertStringContainsString('integer id PK', $output);
    }

    public function test_it_marks_uuid_as_primary_key(): void
    {
        $schema = [
            'orders' => [
                'columns' => [
                    ['name' => 'uuid', 'type_name' => 'char', 'nullable' => false],
                ],
                'foreignKeys' => [],
            ],
        ];

        $output = $this->renderer->render($schema);

        $this->assertStringContainsString('char uuid PK', $output);
    }

    public function test_it_marks_nullable_columns(): void
    {
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

        $this->assertStringContainsString('text bio nullable', $output);
        $this->assertStringNotContainsString('integer id nullable', $output);
    }

    public function test_it_combines_pk_and_nullable_attributes(): void
    {
        $schema = [
            'items' => [
                'columns' => [
                    ['name' => 'id', 'type_name' => 'integer', 'nullable' => true],
                ],
                'foreignKeys' => [],
            ],
        ];

        $output = $this->renderer->render($schema);

        $this->assertStringContainsString('integer id PK,nullable', $output);
    }

    public function test_it_renders_foreign_key_relationships(): void
    {
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

        $this->assertStringContainsString('users ||--o{ posts : "has many"', $output);
    }

    public function test_it_renders_multiple_tables(): void
    {
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

        $this->assertStringContainsString('users {', $output);
        $this->assertStringContainsString('posts {', $output);
    }

    public function test_regular_column_has_no_attributes(): void
    {
        $schema = [
            'tags' => [
                'columns' => [
                    ['name' => 'name', 'type_name' => 'varchar', 'nullable' => false],
                ],
                'foreignKeys' => [],
            ],
        ];

        $output = $this->renderer->render($schema);

        $this->assertMatchesRegularExpression('/varchar name\s*$/m', $output, 'Regular column should have no trailing attributes');
    }
}
