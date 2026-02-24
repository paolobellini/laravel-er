<?php

namespace PaoloBellini\LaravelEr\Tests\Unit;

use Illuminate\Support\Facades\Schema;
use PaoloBellini\LaravelEr\SchemaReader;
use PaoloBellini\LaravelEr\Tests\TestCase;

class SchemaReaderTest extends TestCase
{
    private SchemaReader $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reader = new SchemaReader;
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    public function test_it_returns_empty_schema_when_no_tables_exist(): void
    {
        $schema = $this->reader->read();

        $this->assertIsArray($schema);
        $this->assertEmpty($schema);
    }

    public function test_it_reads_table_with_columns(): void
    {
        Schema::create('posts', function ($table): void {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        $schema = $this->reader->read();

        $this->assertArrayHasKey('posts', $schema);
        $this->assertArrayHasKey('columns', $schema['posts']);
        $this->assertNotEmpty($schema['posts']['columns']);

        $columnNames = array_column($schema['posts']['columns'], 'name');
        $this->assertContains('id', $columnNames);
        $this->assertContains('title', $columnNames);
        $this->assertContains('body', $columnNames);
    }

    public function test_it_reads_foreign_keys(): void
    {
        Schema::create('users', function ($table): void {
            $table->id();
            $table->string('name');
        });

        Schema::create('posts', function ($table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
        });

        $schema = $this->reader->read();

        $this->assertArrayHasKey('foreignKeys', $schema['posts']);
        $this->assertNotEmpty($schema['posts']['foreignKeys']);

        $fk = $schema['posts']['foreignKeys'][0];
        $this->assertEquals('users', $fk['foreign_table']);
        $this->assertContains('user_id', $fk['columns']);
    }

    public function test_it_excludes_configured_tables(): void
    {
        Schema::create('migrations', function ($table): void {
            $table->id();
            $table->string('migration');
        });

        Schema::create('posts', function ($table): void {
            $table->id();
            $table->string('title');
        });

        $schema = $this->reader->read();

        $this->assertArrayNotHasKey('migrations', $schema);
        $this->assertArrayHasKey('posts', $schema);
    }

    public function test_it_reads_multiple_tables(): void
    {
        Schema::create('users', function ($table): void {
            $table->id();
            $table->string('name');
        });

        Schema::create('posts', function ($table): void {
            $table->id();
            $table->string('title');
        });

        $schema = $this->reader->read();

        $this->assertArrayHasKey('users', $schema);
        $this->assertArrayHasKey('posts', $schema);
        $this->assertCount(2, $schema);
    }

    public function test_it_excludes_all_default_excluded_tables(): void
    {
        $excludedTables = config('er.excluded_tables');

        foreach ($excludedTables as $table) {
            Schema::create($table, function ($table): void {
                $table->id();
            });
        }

        Schema::create('products', function ($table): void {
            $table->id();
            $table->string('name');
        });

        $schema = $this->reader->read();

        foreach ($excludedTables as $table) {
            $this->assertArrayNotHasKey($table, $schema);
        }

        $this->assertArrayHasKey('products', $schema);
    }
}
