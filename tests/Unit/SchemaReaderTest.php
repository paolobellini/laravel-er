<?php

use Illuminate\Support\Facades\Schema;
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

    expect($schema)->toBeArray()->toBeEmpty();
});

it('reads table with columns', function (): void {
    Schema::create('posts', function ($table): void {
        $table->id();
        $table->string('title');
        $table->text('body');
        $table->timestamps();
    });

    $schema = $this->reader->read();

    expect($schema)
        ->toHaveKey('posts')
        ->and($schema['posts'])->toHaveKey('columns')
        ->and($schema['posts']['columns'])->not->toBeEmpty();

    $columnNames = array_column($schema['posts']['columns'], 'name');
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

    expect($schema['posts']['foreignKeys'])->not->toBeEmpty();

    $fk = $schema['posts']['foreignKeys'][0];
    expect($fk['foreign_table'])->toBe('users')
        ->and($fk['columns'])->toContain('user_id');
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

    expect($schema)
        ->not->toHaveKey('migrations')
        ->toHaveKey('posts');
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

    expect($schema)
        ->toHaveKey('users')
        ->toHaveKey('posts')
        ->toHaveCount(2);
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

    foreach ($excludedTables as $table) {
        expect($schema)->not->toHaveKey($table);
    }

    expect($schema)->toHaveKey('products');
});
