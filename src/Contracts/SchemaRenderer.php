<?php

namespace PaoloBellini\LaravelEr\Contracts;

use PaoloBellini\LaravelEr\Data\Schema;

interface SchemaRenderer
{
    public function render(Schema $schema): string;

    public function outputExtension(): string;

    public function wrapOutput(): string;
}
