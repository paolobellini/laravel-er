<?php

namespace PaoloBellini\LaravelEr\Tests\Unit\Commands;

use Illuminate\Support\Facades\Schema;
use PaoloBellini\LaravelEr\Tests\TestCase;

class GenerateErDiagramCommandTest extends TestCase
{
    private string $outputPath;

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $this->outputPath = sys_get_temp_dir().'/laravel-er-test-'.uniqid();
        mkdir($this->outputPath, 0755, true);

        $app['config']->set('er.output_path', $this->outputPath);
    }

    protected function tearDown(): void
    {
        $file = $this->outputPath.'/er-diagram.md';

        if (file_exists($file)) {
            unlink($file);
        }

        if (is_dir($this->outputPath)) {
            rmdir($this->outputPath);
        }

        parent::tearDown();
    }

    public function test_it_generates_er_diagram_file(): void
    {
        Schema::create('users', function ($table): void {
            $table->id();
            $table->string('name');
        });

        $this->artisan('er:generate')
            ->expectsOutputToContain('Generating ER diagram...')
            ->expectsOutputToContain('ER diagram saved to')
            ->assertSuccessful();

        $this->assertFileExists($this->outputPath.'/er-diagram.md');
    }

    public function test_it_wraps_output_in_mermaid_code_block(): void
    {
        Schema::create('posts', function ($table): void {
            $table->id();
            $table->string('title');
        });

        $this->artisan('er:generate')->assertSuccessful();

        $content = file_get_contents($this->outputPath.'/er-diagram.md');

        $this->assertStringStartsWith('```mermaid', $content);
        $this->assertStringEndsWith("```\n", $content);
    }

    public function test_it_returns_success_exit_code(): void
    {
        $this->artisan('er:generate')
            ->assertExitCode(0);
    }
}
