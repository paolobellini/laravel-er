<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use PaoloBellini\LaravelEr\Context;
use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;

final class GenerateErDiagramCommand extends Command implements Isolatable
{
    protected $signature = 'er:generate {--format= : The output format (mermaid, dbdiagram)}';

    protected $description = 'Generate an ER diagram from your database schema';

    public function handle(Context $context): int
    {
        $this->info('Generating ER diagram...');

        /** @var string $format */
        $format = $this->option('format') ?? config('er.renderer');
        /** @var class-string<SchemaRenderer> $rendererClass */
        $rendererClass = config('er.renderers.'.$format);

        $context->setStrategy(new $rendererClass);

        $path = $context->executeStrategy();

        $this->info('ER diagram saved to '.$path);

        return self::SUCCESS;
    }
}
