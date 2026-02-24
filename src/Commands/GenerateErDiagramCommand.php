<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Commands;

use Illuminate\Console\Command;

final class GenerateErDiagramCommand extends Command
{
    protected $signature = 'er:generate';

    protected $description = 'Generate an ER diagram from your database schema';

    public function handle(): int
    {
        $this->info('Generating ER diagram...');

        // TODO: the actual logic will go here

        $this->info('Done!');

        return self::SUCCESS;
    }
}
