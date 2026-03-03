<?php

use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    $this->app['config']->set('database.default', 'testing');
    $this->app['config']->set('database.connections.testing', [
        'driver' => 'sqlite',
        'database' => ':memory:',
    ]);
    $this->app['config']->set('er.output_path', sys_get_temp_dir());
    $this->app['config']->set('er.output_filename', 'er-diagram');
});

afterEach(function (): void {
    @unlink(sys_get_temp_dir().'/er-diagram.md');
    @unlink(sys_get_temp_dir().'/er-diagram.dbml');
});

it('outputs generating and saved messages', function (): void {
    Schema::create('users', function ($table): void {
        $table->id();
        $table->string('name');
    });

    $this->artisan('er:generate')
        ->expectsOutputToContain('Generating ER diagram...')
        ->expectsOutputToContain('ER diagram saved to')
        ->assertSuccessful();
});

it('returns success exit code', function (): void {
    $this->artisan('er:generate')
        ->assertExitCode(0);
});

it('uses config default renderer when no format is passed', function (): void {
    $this->artisan('er:generate')
        ->assertSuccessful();
});

it('accepts mermaid format', function (): void {
    $this->artisan('er:generate --format=mermaid')
        ->assertSuccessful();
});

it('accepts dbdiagram format', function (): void {
    $this->artisan('er:generate --format=dbdiagram')
        ->assertSuccessful();
});
