# Deployment

## `clear_paths`: hide non-public files from the document root

The webroot is the full release root (no `public`/`pub` subfolder — `index.php` lives at the repo root), so
anything not needed at runtime is web-accessible unless removed. `.htaccess` already blocks web access to most
of these (extension and path rules), but that's a single point of failure (misconfigured vhost, non-Apache
webserver) — the release itself should not contain these files at all.

`deploy.php` requires Deployer's built-in `recipe/deploy/clear_paths.php` and sets `clear_paths` to the list of
root-level files/dirs not needed at runtime (dev tooling configs, CI configs, build configs, `tests/`, `docs/`,
`migrations/`, `deploy.php` itself, license/readme files, etc. — some entries are currently no-ops for repos where
that dir doesn't exist, harmless since `rm -rf` on a missing path is a no-op). Hooked with
`after('database:migrate', 'deploy:clear_paths')` — `database:migrate` itself is a no-op override hooked
`before('deploy:symlink', ...)`, so `deploy:clear_paths` still runs after migrations/cache-clear (which may
still need `bin/console` or `migrations/`) and right before the release goes live.

**Never add to `clear_paths`:** `.env*`, `app/config/parameters.yml` (shared-file symlinks, read at boot),
`bin/` (needed by `bin/console` at runtime and by later deploy tasks), `composer.json`/`composer.lock` (kept
per explicit instruction — not removed even though `.htaccess` already blocks them), `templates/` (Twig-loaded
at runtime), any shared/writable dir (`var/cache`, `var/logs`, `src/Frontend/Files`, etc.).

Building the list: use `git ls-files` (not `ls`) to get only what's actually tracked/deployed — local-only
clutter (`.idea/`, `node_modules/`, caches) is already gitignored and never reaches the release, so it doesn't
belong in the list.

List format: directories first, then files, each group alphabetical.
