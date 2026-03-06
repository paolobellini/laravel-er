# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added
- ER diagram generation from Laravel database schema via `php artisan er:generate`
- Mermaid renderer with full ER diagram syntax
- dbdiagram.io renderer with full DBML syntax
- Primary key, foreign key, and unique constraint detection
- Nullable and default value rendering
- Column type details (length, precision, enum values)
- Relationship detection (one-to-one, one-to-many) from foreign keys
- Configurable output path and filename
- Configurable table exclusion list
- `--format` option to choose between renderers
- Spinner feedback during diagram generation
- Auto-discovery of the service provider
- CI workflow with GitHub Actions
- Dependabot configuration for Composer and GitHub Actions
- Bug report issue template
