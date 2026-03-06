# Contributing to Laravel ER

Thank you for considering contributing to Laravel ER! Every contribution is appreciated.

## Getting Started

1. Fork the repository
2. Clone your fork locally
3. Install dependencies:

```bash
composer install
```

## Development Workflow

### Branch Naming

Create a branch from `main` using the following conventions:

- `feat/description` for new features
- `fix/description` for bug fixes
- `docs/description` for documentation changes

### Coding Standards

This project uses [Laravel Pint](https://laravel.com/docs/pint) for code formatting. Run the formatter before committing:

```bash
composer lint
```

### Running Tests

Always make sure the full test suite passes before submitting a pull request:

```bash
composer test
```

This runs all checks in order:

| Command | Description |
|---|---|
| `composer test:lint` | Code style (Pint) |
| `composer test:type-coverage` | 100% type coverage (Pest) |
| `composer test:unit` | Unit tests with 100% code coverage (Pest) |
| `composer test:types` | Static analysis (PHPStan) |
| `composer test:refactor` | Automated refactoring checks (Rector) |

You can run each check individually during development.

### Static Analysis

The project uses PHPStan for static analysis. Make sure your code passes:

```bash
composer test:types
```

### Refactoring

Rector is configured to suggest automated improvements. Check for suggestions with:

```bash
composer test:refactor
```

Apply them with:

```bash
composer refactor
```

## Submitting a Pull Request

1. Create your feature branch (`git checkout -b feat/my-feature`)
2. Make your changes
3. Run the full test suite (`composer test`)
4. Commit your changes (`git commit -m 'feat: Add my feature'`)
5. Push to the branch (`git push origin feat/my-feature`)
6. Open a Pull Request against the `main` branch

### Commit Messages

Use [Conventional Commits](https://www.conventionalcommits.org/) format:

- `feat: Add new feature`
- `fix: Fix specific bug`
- `docs: Update documentation`
- `refactor: Refactor code without changing behavior`
- `test: Add or update tests`

### Pull Request Guidelines

- Keep pull requests focused on a single change
- Include tests for new functionality
- Update documentation if needed
- Make sure all CI checks pass

## Reporting Issues

Found a bug or have a feature request? [Open an issue](https://github.com/paolobellini/laravel-er/issues) on GitHub.

When reporting a bug, please include:

- PHP and Laravel versions
- Steps to reproduce
- Expected vs actual behavior
