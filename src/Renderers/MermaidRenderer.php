<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Renderers;

final readonly class MermaidRenderer
{
    /**
     * @param  array<string, array{columns: array<int, array<string, mixed>>, foreignKeys: array<int, array<string, mixed>>}>  $schema
     */
    public function render(array $schema): string
    {
        $lines = ['erDiagram'];

        foreach ($schema as $tableName => $tableData) {
            $lines[] = '';
            $lines[] = sprintf('    %s {', $tableName);

            foreach ($tableData['columns'] as $column) {
                /** @var string $typeName */
                $typeName = $column['type_name'];
                /** @var string $name */
                $name = $column['name'];
                $lines[] = sprintf('        %s %s', $typeName, $name).$this->getColumnAttributes($column);
            }

            $lines[] = '    }';
        }

        $lines[] = '';

        foreach ($schema as $tableName => $tableData) {
            foreach ($tableData['foreignKeys'] as $fk) {
                /** @var string $foreignTable */
                $foreignTable = $fk['foreign_table'];
                $lines[] = sprintf('    %s ||--o{ %s : "has many"', $foreignTable, $tableName);
            }
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * @param  array<string, mixed>  $column
     */
    private function getColumnAttributes(array $column): string
    {
        $attributes = [];

        if (in_array($column['name'], ['id', 'uuid'])) {
            $attributes[] = 'PK';
        }

        if ($column['nullable']) {
            $attributes[] = 'nullable';
        }

        if ($attributes !== []) {
            return ' '.implode(',', $attributes);
        }

        return '';
    }
}
