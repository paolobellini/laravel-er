<?php

use Illuminate\Support\Facades\Schema;
use PaoloBellini\LaravelEr\Data\Schema as SchemaData;
use PaoloBellini\LaravelEr\SchemaReader;

beforeEach(function (): void {
    $this->app['config']->set('database.default', 'testing');
    $this->app['config']->set('database.connections.testing', [
        'driver' => 'sqlite',
        'database' => ':memory:',
    ]);

    $this->reader = new SchemaReader;
});

it('returns empty schema when no tables exist', function (): void {
    $schema = $this->reader->read();

    expect($schema)
        ->toBeInstanceOf(SchemaData::class)
        ->and($schema->tables)->toBeEmpty();
});

it('reads table with columns', function (): void {
    Schema::create('posts', function ($table): void {
        $table->id();
        $table->string('title');
        $table->text('body');
        $table->timestamps();
    });

    $schema = $this->reader->read();

    expect($schema->tables)->toHaveCount(1)
        ->and($schema->tables[0]->name)->toBe('posts')
        ->and($schema->tables[0]->columns)->not->toBeEmpty();

    $columnNames = array_map(fn ($col) => $col->name, $schema->tables[0]->columns);
    expect($columnNames)
        ->toContain('id')
        ->toContain('title')
        ->toContain('body');
});

it('reads foreign keys', function (): void {
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

    $postsTable = collect($schema->tables)->first(fn ($t): bool => $t->name === 'posts');

    expect($postsTable->foreignKeys)->not->toBeEmpty();

    $fk = $postsTable->foreignKeys[0];
    expect($fk->foreignTable)->toBe('users')
        ->and($fk->columns)->toContain('user_id');
});

it('excludes configured tables', function (): void {
    Schema::create('migrations', function ($table): void {
        $table->id();
        $table->string('migration');
    });

    Schema::create('posts', function ($table): void {
        $table->id();
        $table->string('title');
    });

    $schema = $this->reader->read();

    $tableNames = array_map(fn ($t) => $t->name, $schema->tables);
    expect($tableNames)
        ->not->toContain('migrations')
        ->toContain('posts');
});

it('reads multiple tables', function (): void {
    Schema::create('users', function ($table): void {
        $table->id();
        $table->string('name');
    });

    Schema::create('posts', function ($table): void {
        $table->id();
        $table->string('title');
    });

    $schema = $this->reader->read();

    $tableNames = array_map(fn ($t) => $t->name, $schema->tables);
    expect($tableNames)
        ->toContain('users')
        ->toContain('posts')
        ->and($schema->tables)->toHaveCount(2);
});

it('excludes all default excluded tables', function (): void {
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

    $tableNames = array_map(fn ($t) => $t->name, $schema->tables);

    foreach ($excludedTables as $table) {
        expect($tableNames)->not->toContain($table);
    }

    expect($tableNames)->toContain('products');
});
