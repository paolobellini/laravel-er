<?php

namespace PaoloBellini\LaravelEr\Tests\Unit\Commands;

use PaoloBellini\LaravelEr\Tests\TestCase;

class GenerateErDiagramCommandTest extends TestCase
{
    public function test_it_has_correct_signature(): void
    {
        $command = $this->artisan('er:generate');

        $command->expectsOutputToContain('Generating ER diagram...');
        $command->expectsOutputToContain('Done!');
        $command->assertSuccessful();
    }

    public function test_it_returns_success_exit_code(): void
    {
        $this->artisan('er:generate')
            ->assertExitCode(0);
    }
}
