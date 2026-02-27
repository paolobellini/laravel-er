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

            $foreignKeyColumns = array_map(
                static function (array $fk): string {
                    /** @var array<int, string> $columns */
                    $columns = $fk['columns'];

                    return $columns[0];
                },
                $tableData['foreignKeys'],
            );

            foreach ($tableData['columns'] as $column) {
                /** @var string $rawType */
                $rawType = $column['type_name'];
                $typeName = $this->normalizeType($rawType);
                /** @var string $name */
                $name = $column['name'];
                $isFk = in_array($name, $foreignKeyColumns, true);
                $lines[] = sprintf('        %s %s', $typeName, $name).$this->getColumnAttributes($column, $isFk);
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

    private function normalizeType(string $type): string
    {
        return match ($type) {
            'uuid', 'ulid' => 'varchar',
            default => $type,
        };
    }

    /**
     * @param  array<string, mixed>  $column
     */
    private function getColumnAttributes(array $column, bool $isFk): string
    {
        $parts = [];

        if (in_array($column['name'], ['id', 'uuid'])) {
            $parts[] = 'PK';
        }

        if ($isFk) {
            $parts[] = 'FK';
        }

        $comment = $column['nullable'] ? 'nullable' : 'not null';

        $result = '';

        if ($parts !== []) {
            $result .= ' '.implode(',', $parts);
        }

        return $result.sprintf(' "%s"', $comment);
    }
}
