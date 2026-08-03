# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Fork CMS — an open-source PHP CMS built on Symfony 5.4 (LTS), with a custom module system predating full Symfony bundle conventions. PHP ^8.5, Doctrine ORM, Twig templates.

## Commands

### PHP dependencies & app bootstrap
```bash
composer install                      # installs deps, then runs auto-scripts (cache:clear, cache:warmup)
php bin/console forkcms:cache:clear   # ForkCMS-specific cache clear (module/theme metadata)
php bin/console cache:clear --no-warmup
php bin/console cache:warmup
```

### Tests (PHPUnit via Symfony's phpunit-bridge)
```bash
composer test                                   # runs ./bin/simple-phpunit (all suites)
php bin/simple-phpunit --testsuite=unit
php bin/simple-phpunit --testsuite=functional
php bin/simple-phpunit --testsuite=installer
php bin/simple-phpunit --filter testMethodName
php bin/simple-phpunit src/Backend/Modules/Blog/Tests/Actions/IndexTest.php
```
Test env vars come from `phpunit.xml.dist` (`FORK_ENV=test`, `APP_ENV=test`). CI seeds a MySQL DB from `tests/data/test_db.sql` before running — a local run needs a working DB configured in `app/config/parameters.yml`.

### Static analysis & code style (PHP)
```bash
php bin/phpstan analyse                 # level 1, configured in phpstan.dist.neon, scoped to src/
php bin/phpcs                           # PSR2, scoped to src/, cache in .phpcs-cache
php bin/phpcbf                          # auto-fix phpcs violations
php bin/twig-cs-fixer lint templates/   # Twig template lint (Symfony + Twig standards)
```

### Frontend assets
```bash
npm ci
npm run build           # webpack --config webpack.prod.js
npm run watch           # webpack --watch --config webpack.dev.js
node_modules/.bin/gulp build   # legacy gulp pipeline, still used in CI build stage
npm test                # runs `standard` (StandardJS linter, see package.json "standard" config for excluded legacy files)
stylelint .              # SCSS/CSS lint, config in .stylelintrc
```

### Console utilities of note (`bin/`)
`bin/console` (Symfony console), `bin/doctrine` / `bin/doctrine-dbal`, `bin/phpstan`, `bin/phpcs` / `bin/phpcbf`, `bin/twigcs`, `bin/sql-formatter`, `bin/generate-parameters-gitlab` (used by CI to build `parameters.yml`).

## Architecture

### Two front-ends, one kernel
The app is split into two independently-routed applications sharing one Symfony kernel (`app/AppKernel.php` / `app/Kernel.php`):
- **`src/Backend`** — the CMS administration interface, mounted at `/private/{_locale}/{module}/{action}`.
- **`src/Frontend`** — the public website, catch-all route `/{route}`.

Both are dispatched through a single Symfony controller, `ForkCMS\App\ForkController` (see `app/config/routing.yml`): `backendController`, `backendAjaxController`, `frontendController`, `frontendAjaxController`. Symfony routing resolves to this controller, which then hands off to ForkCMS's own module/action resolution — most business logic is NOT reached via normal Symfony controller routing/services, but via the module system below.

### Module system (the core convention to understand)
Both `src/Backend/Modules/*` and `src/Frontend/Modules/*` follow the same per-module layout. Take `src/Backend/Modules/Blog` as a representative example:
- `info.xml` — module metadata (name, version, description, authors).
- `Config.php` — extends `Backend\Core\Engine\Base\Config`; declares the module's default action and access control.
- `Actions/*.php` — one class per admin screen/operation (`Index.php`, `Add.php`, `Edit.php`, `Delete.php`, ...), each extending a base action in `src/Backend/Core/Engine/Base/Action.php` (or `ActionIndex`, `ActionAdd`, `ActionEdit`, `ActionDelete` convenience subclasses).
- `Ajax/*.php` — AJAX endpoints, extending `Backend\Core\Engine\Base\AjaxAction`.
- `Engine/Model.php` — static-method "model" class holding the module's DB queries/business logic (not a Doctrine entity — this is the older Fork CMS data-access convention; some newer modules mix in Doctrine).
- `Widgets/*.php` — reusable blocks embeddable elsewhere (e.g. on Frontend pages), extending `Backend\Core\Engine\Base\Widget`.
- `Installer/Installer.php` + `Installer/Data/` — module install/upgrade logic (creates DB tables, default settings, locale entries) run by the extensions installer.
- `Form/`, `Layout/` (templates), `Js/`, `DataFixtures/`, `Tests/` — as named.

`Frontend` modules mirror this (`Actions/`, `Widgets/`, `Engine/Model.php`) but serve public pages instead of admin screens.

When adding a feature to an existing module, follow the existing sibling files in that module (e.g. copy the shape of `Edit.php`/`EditCategory.php` rather than inventing a new pattern). When behavior is core/shared rather than module-specific, it likely belongs in `src/Backend/Core/Engine/` or `src/Frontend/Core/Engine/` (base classes: `Action`, `Model`, `Header`, `Meta`, `Url`, `DataGrid*`, `TwigTemplate`, `Navigation`, `User`, `Authentication`, `Form`).

### Shared/common code
- `src/Common/` — code shared between Backend and Frontend that isn't module-specific: `Core/`, `Doctrine/` (custom types, subscribers), `EventListener/`, `Events/`, `Mailer/`, `Language.php`, `Locale.php`, `ModulesSettings.php`, `WebTestCase.php` (functional test base class).
- `src/ForkCMS/` — actual Symfony bundle territory: `Bundle/` (`InstallerBundle`, `CoreBundle`), plus `Google/`, `Imagine/`, `Privacy/`, `Utility/` integration code. This is where you'd add genuinely Symfony-bundle-shaped code (DI extensions, compiler passes).
- `src/Console/` — custom `bin/console` commands (`Core/`, `Locale/`, `Thumbnails/`).
- `app/` — kernel bootstrap (`AppKernel.php`, `Kernel.php`, `KernelLoader.php`), `BaseModel.php`, `ForkController.php` (the routing entrypoint described above), and `app/config/*.yml` (Symfony config split by env: `config.yml`, `config_dev.yml`, `config_prod.yml`, `config_test.yml`, `config_install.yml`; plus `routing*.yml`, `doctrine.yml`, `form.yml`, `parameters.yml` — copy `parameters.yml.dist` locally, never edit `.dist`).

### Installer
Fork CMS ships its own installer bundle (`src/ForkCMS/Bundle/InstallerBundle`) plus a per-module `Installer/Installer.php` convention — new modules need an installer class to create their schema/default data, not a Doctrine migration.

### Config/env
- `.env` / `.env.local` set `FORK_ENV` and `FORK_DEBUG` (Fork's own env flags, read alongside Symfony's `APP_ENV`/`APP_DEBUG`).
- `app/config/parameters.yml` holds DB credentials, `kernel.secret`, site settings (`site.protocol`, `site.domain`, `site.multilanguage`, ...), mailer DSN, Sentry DSN. Never commit real values — work from `parameters.yml.dist`.

## Code style specifics
- PHP: PSR2 via phpcs (`phpcs.xml.dist`), scoped to `src/` only (excludes `Cache/`, `Core/Js/ckeditor`, `Core/Js/ckfinder`).
- PHPStan level 1 (`phpstan.dist.neon`) against `src/`; a large `ignoreErrors` list suppresses false positives on Fork's legacy global constants (`BACKEND_PATH`, `FRONTEND_CACHE_PATH`, `SITE_URL`, etc.) — don't try to "fix" those by defining the constants, they're intentionally dynamic/runtime-injected.
- JS: StandardJS (`package.json` `"standard"` block has a long `ignore` list of legacy/vendored JS — don't lint-fix those files opportunistically).
- SCSS/CSS: stylelint-config-standard-scss with `at-rule-no-unknown`, `no-descending-specificity`, `scss/no-global-function-names` disabled.
- Twig: twig-cs-fixer with TwigCsFixer + Symfony + Twig standards combined.
- Indentation: 4 spaces for php/js/yml, 2 spaces for html/twig/xml/tpl (see `.editorconfig`), LF line endings, final newline required.
