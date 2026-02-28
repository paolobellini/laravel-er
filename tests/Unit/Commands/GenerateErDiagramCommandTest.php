<?php

use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    $this->app['config']->set('database.default', 'testing');
    $this->app['config']->set('database.connections.testing', [
        'driver' => 'sqlite',
        'database' => ':memory:',
    ]);

    $outputPath = sys_get_temp_dir().'/laravel-er-test-'.uniqid();
    mkdir($outputPath, 0755, true);

    $this->app['config']->set('er.output_path', $outputPath);
});

afterEach(function (): void {
    $outputPath = config('er.output_path');
    $file = $outputPath.'/er-diagram.md';

    if (file_exists($file)) {
        unlink($file);
    }

    if (is_dir($outputPath)) {
        rmdir($outputPath);
    }
});

it('generates er diagram file', function (): void {
    Schema::create('users', function ($table): void {
        $table->id();
        $table->string('name');
    });

    $this->artisan('er:generate')
        ->expectsOutputToContain('Generating ER diagram...')
        ->expectsOutputToContain('ER diagram saved to')
        ->assertSuccessful();

    $outputPath = config('er.output_path');
    expect($outputPath.'/er-diagram.md')->toBeFile();
});

it('wraps output in mermaid code block', function (): void {
    Schema::create('posts', function ($table): void {
        $table->id();
        $table->string('title');
    });

    $this->artisan('er:generate')->assertSuccessful();

    $outputPath = config('er.output_path');
    $content = file_get_contents($outputPath.'/er-diagram.md');

    expect($content)
        ->toStartWith('```mermaid')
        ->toEndWith("```\n");
});

it('returns success exit code', function (): void {
    $this->artisan('er:generate')
        ->assertExitCode(0);
});
