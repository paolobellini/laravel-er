<?php

namespace PaoloBellini\LaravelEr\Tests\Unit\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use PaoloBellini\LaravelEr\LaravelErServiceProvider;
use PaoloBellini\LaravelEr\Tests\TestCase;

class LaravelErTest extends TestCase
{
    public function test_it_is_registered(): void
    {
        $this->assertArrayHasKey(
            LaravelErServiceProvider::class,
            $this->app->getLoadedProviders()
        );
    }

    public function test_it_merges_config(): void
    {
        $config = config('er');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('output_path', $config);
        $this->assertArrayHasKey('excluded_tables', $config);
    }

    public function test_it_has_default_excluded_tables(): void
    {
        $excludedTables = config('er.excluded_tables');

        $this->assertIsArray($excludedTables);
        $this->assertContains('migrations', $excludedTables);
        $this->assertContains('failed_jobs', $excludedTables);
        $this->assertContains('sessions', $excludedTables);
    }

    public function test_it_registers_commands(): void
    {
        $this->artisan('list')
            ->expectsOutputToContain('er:generate')
            ->assertSuccessful();
    }

    public function test_config_is_publishable(): void
    {
        $publishable = ServiceProvider::pathsToPublish(
            LaravelErServiceProvider::class,
            'er-config'
        );

        $this->assertNotEmpty($publishable);
        $this->assertStringEndsWith('config/er.php', array_key_first($publishable));
    }
}
