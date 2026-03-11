<?php

use PaoloBellini\LaravelEr\Data\Column;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Index;
use PaoloBellini\LaravelEr\Data\Schema;
use PaoloBellini\LaravelEr\Data\Table;
use PaoloBellini\LaravelEr\Renderers\DbDiagramRenderer;

beforeEach(function (): void {
    $this->renderer = new DbDiagramRenderer;
});

it('renders empty schema', function (): void {
    expect($this->renderer->render(new Schema([])))->toBe("```dbdiagram\n\n\n```\n");
});

it('returns passthrough wrap output', function (): void {
    expect($this->renderer->wrapOutput('test'))->toBe("```dbdiagram\ntest\n```\n");
});

it('renders a single table with columns', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('name', 'varchar(255)', false, null),
                new Column('email', 'varchar(255)', true, null),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('Table users {')
        ->toContain('  id integer [primary key, not null]')
        ->toContain('  name varchar(255) [not null]')
        ->toContain('  email varchar(255) [null]');
});

it('renders PK and FK markers on columns', function (): void {
    $schema = new Schema([
        new Table(
            name: 'posts',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('user_id', 'integer', false, null),
                new Column('title', 'varchar', false, null),
            ],
            foreignKeys: [
                new ForeignKey(columns: ['user_id'], foreignTable: 'users'),
            ],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('  id integer [primary key, not null]')
        ->toContain('  user_id integer [not null]')
        ->toContain('  title varchar [not null]');
});

it('renders foreign key relationships', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [
                new Column('id', 'integer', false, null),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
        new Table(
            name: 'posts',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('user_id', 'integer', false, null),
            ],
            foreignKeys: [
                new ForeignKey(columns: ['user_id'], foreignTable: 'users'),
            ],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)->toContain('Ref: posts.user_id > users.id');
});

it('renders nullable columns correctly', function (): void {
    $schema = new Schema([
        new Table(
            name: 'profiles',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('bio', 'text', true, null),
            ],
            foreignKeys: [],
            indexes: [],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('  id integer [not null]')
        ->toContain('  bio text [null]');
});

it('renders unique attribute on columns', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('email', 'varchar', false, null),
                new Column('name', 'varchar', false, null),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
                new Index(columns: ['email'], unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain('  id integer [primary key, not null]')
        ->toContain('  email varchar [not null, unique]')
        ->toContain('  name varchar [not null]');
});

it('renders default value in column attributes', function (): void {
    $schema = new Schema([
        new Table(
            name: 'posts',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('status', 'varchar', false, 'draft'),
                new Column('views', 'integer', false, '0'),
                new Column('deleted_at', 'timestamp', true, 'NULL'),
                new Column('title', 'varchar', false, null),
            ],
            foreignKeys: [],
            indexes: [],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)
        ->toContain("  status varchar [not null, default: 'draft']")
        ->toContain("  views integer [not null, default: '0']")
        ->toContain("  deleted_at timestamp [null, default: 'NULL']")
        ->toContain('  title varchar [not null]');
});

it('renders each table exactly once', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [new Column('id', 'integer', false, null)],
            foreignKeys: [],
            indexes: [],
        ),
        new Table(
            name: 'posts',
            columns: [new Column('id', 'integer', false, null)],
            foreignKeys: [],
            indexes: [],
        ),
        new Table(
            name: 'comments',
            columns: [new Column('id', 'integer', false, null)],
            foreignKeys: [],
            indexes: [],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect(substr_count((string) $output, 'Table users {'))->toBe(1)
        ->and(substr_count((string) $output, 'Table posts {'))->toBe(1)
        ->and(substr_count((string) $output, 'Table comments {'))->toBe(1);
});

it('renders one-to-one relationship when FK is unique', function (): void {
    $schema = new Schema([
        new Table(
            name: 'users',
            columns: [
                new Column('id', 'integer', false, null),
            ],
            foreignKeys: [],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
            ],
        ),
        new Table(
            name: 'profiles',
            columns: [
                new Column('id', 'integer', false, null),
                new Column('user_id', 'integer', false, null),
            ],
            foreignKeys: [
                new ForeignKey(columns: ['user_id'], foreignTable: 'users'),
            ],
            indexes: [
                new Index(columns: ['id'], primary: true, unique: true),
                new Index(columns: ['user_id'], unique: true),
            ],
        ),
    ]);

    $output = $this->renderer->render($schema);

    expect($output)->toContain('Ref: profiles.user_id - users.id');
});
