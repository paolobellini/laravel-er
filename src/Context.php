<?php

namespace PaoloBellini\LaravelEr;

use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;

final class Context
{
    private SchemaRenderer $strategy;

    public function __construct(private readonly SchemaReader $reader) {}

    public function setStrategy(SchemaRenderer $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function executeStrategy(): string
    {
        $schema = $this->reader->read();

        $content = $this->strategy->render($schema);

        /** @var string $outputPath */
        $outputPath = config('er.output_path');
        /** @var string $outputFilename */
        $outputFilename = config('er.output_filename');

        $filePath = $outputPath.'/'.$outputFilename.'.'.$this->strategy->outputExtension();

        file_put_contents($filePath, $content);

        return $filePath;
    }
}
