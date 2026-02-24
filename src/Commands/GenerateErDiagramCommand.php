<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Commands;

use Illuminate\Console\Command;
use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;
use PaoloBellini\LaravelEr\SchemaReader;

final class GenerateErDiagramCommand extends Command
{
    protected $signature = 'er:generate';

    protected $description = 'Generate an ER diagram from your database schema';

    public function handle(SchemaReader $reader, MermaidRenderer $renderer): int
    {
        $this->info('Generating ER diagram...');

        $schema = $reader->read();
        $output = $renderer->render($schema);

        /** @var string $outputPath */
        $outputPath = config('er.output_path');
        $path = $outputPath.'/er-diagram.md';

        file_put_contents($path, "```mermaid\n{$output}```\n");

        $this->info('ER diagram saved to '.$path);

        return self::SUCCESS;
    }
}
