<?php

use Illuminate\Support\ServiceProvider;
use PaoloBellini\LaravelEr\LaravelErServiceProvider;

it('is registered', function (): void {
    expect($this->app->getLoadedProviders())
        ->toHaveKey(LaravelErServiceProvider::class);
});

it('merges config', function (): void {
    $config = config('er');

    expect($config)
        ->toBeArray()
        ->toHaveKey('output_path')
        ->toHaveKey('excluded_tables');
});

it('has default excluded tables', function (): void {
    $excludedTables = config('er.excluded_tables');

    expect($excludedTables)
        ->toBeArray()
        ->toContain('migrations')
        ->toContain('failed_jobs')
        ->toContain('sessions');
});

it('registers commands', function (): void {
    $this->artisan('list')
        ->expectsOutputToContain('er:generate')
        ->assertSuccessful();
});

it('config is publishable', function (): void {
    $publishable = ServiceProvider::pathsToPublish(
        LaravelErServiceProvider::class,
        'er-config'
    );

    expect($publishable)->not->toBeEmpty()
        ->and(array_key_first($publishable))->toEndWith('config/er.php');
});
