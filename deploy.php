<?php

namespace Deployer;

require 'recipe/symfony3.php';
require 'recipe/cachetool.php';
require 'recipe/sentry.php';
// TODO DB stuff from deployer-sumo

// Define some variables
set('client', 'sumocoders');
set('project', 'forkdeployer');
set('repository', 'git@github.com:davysumo/forkcms.git');
set('release', 'current');
set('production_url', '$productionUrl');
set('sentry_organization', '$sentryOrganization');
set('sentry_project_slug', '$sentryProjectSlug');
set('sentry_token', '$sentryToken');

// Define staging
host('dev02.sumocoders.eu')
    ->user('sites')
    ->stage('staging')
    ->set('deploy_path', '~/apps/{{client}}/{{project}}')
    ->set('branch', 'staging')
    ->set('bin/php', 'php7.4')
    ->set('bin/composer', '{{bin/php}} /home/sites/apps/{{client}}/{{project}}/shared/composer.phar')
    ->set('cachetool', '/var/run/php_74_fpm_sites.sock')
    ->set('document_root', '~/php74/{{client}}/{{project}}');

// TODO install composer on staging shared

// Define production
//host('$host')
//    ->user('sites')
//    ->stage('production')
//    ->set('deploy_path', '~/apps/{{client}}/{{project}}')
//    ->set('branch', 'master')
//    ->set('bin/php', '$phpBinary')
//    ->set('cachetool', '$socketPath')
//    ->set('document_root', '~/php74/{{client}}/{{project}}');

/*************************
 * No need to edit below *
 *************************/

set('use_relative_symlink', false);

// Shared files/dirs between deploys
add('shared_files', ['.env.local', 'app/config/parameters.yml']);
add('shared_dirs', ['src/Frontend/Files']);

// Writable dirs by web server
add('writable_dirs', []);

// Disallow stats
set('allow_anonymous_stats', false);

// Composer
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest');

// Sentry
set(
    'sentry',
    [
        'organization' => get('sentry_organization'),
        'projects' => [
            get('sentry_project_slug'),
        ],
        'token' => get('sentry_token')
    ]
);

/*****************
 * Task sections *
 *****************/
// Build tasks
task(
    'gulp:build',
    function () {
        run('gulp build');
    }
)
    ->desc('Run the build script.')
    ->local();
after('deploy:update_code', 'gulp:build');

// Upload tasks
task(
    'upload:assets',
    function () {
        $packageFile = file_get_contents('package.json');
        $package = json_decode($packageFile, true);

        if (!array_key_exists('theme', $package)) {
            writeln(
                [
                    '<comment>No theme found in package.json</comment>',
                ]
            );

            return;
        }

        $theme = $package['theme'];
        $remotePath = '{{release_path}}/src/Frontend/Themes/' . $theme;

        upload(__DIR__ . '/src/Frontend/Themes/' . $theme . '/Core', $remotePath);
    }
)
    ->desc('Uploads the assets');
after('gulp:build', 'upload:assets');

/**
 * Install assets from public dir of bundles
 * @Override from symfony.php - don't know if we need this for Fork
 */
task('deploy:assets:install', function () {
    // TODO do nothing for now
})->desc('Install bundle assets');

/**
 * Migrate database
 * @Override from symfony.php which executes doctrine:migrations
 */
task('database:migrate', function () {
    /* TODO
     * - execute update.sql scripts in migrations dir
     * - add dir name to executed_migrations
     * - migrate locale (different task?)
     */
})->desc('Migrate database');

task(
    'fork:cache:clear',
    function() {
        run('{{bin/php}} {{bin/console}} fork:cache:clear --env={{symfony_env}}');
        run('if [ -f {{deploy_path}}/shared/config/parameters.yml ]; then touch {{deploy_path}}/shared/config/parameters.yml; fi');
    }
)
    ->desc('Clear Fork CMS cache');
before('deploy:cache:clear', 'fork:cache:clear');

task(
    'sumo:symlink:document-root',
    function() {
        if (!get('document_root', false)) {
            return;
        }

        $publicPath = get('deploy_path') . '/current/';
        $currentSymlink = run(
            'if [ -L {{document_root}} ]; then readlink {{document_root}}; fi'
        );

        $expandedPath = str_replace(
            '~/',
            run('echo $HOME') . '/',
            $publicPath
        );

        // already linked, so we can stop here
        if ($currentSymlink === $expandedPath) {
            return;
        }

        // Show a warning when the document root exists. So we don't overwrite
        // existing stuff.
        if ($currentSymlink === '' && test('[ -e {{document_root}} ]')) {
            writeln(
                [
                    '<comment>Document root already exists</comment>',
                    '<comment>To link it, issue the following command:</comment>',
                    sprintf(
                        '<comment>ln -sf %1$s %2$s</comment>',
                        $publicPath,
                        get('document_root')
                    ),
                ]
            );
            return;
        }

        run(sprintf('mkdir -p %1$s', dirname(get('document_root'))));
        run('rm -f {{document_root}}');
        run(sprintf('{{bin/symlink}} %1$s {{document_root}}', $publicPath));
    }
)
    ->desc('Symlink the document root to the public folder');
after('deploy:symlink', 'sumo:symlink:document-root');

/**********************
 * Flow configuration *
 **********************/
// Clear the Opcache
after('deploy:symlink', 'cachetool:clear:opcache');
// Unlock the deploy when it failed
after('deploy:failed', 'deploy:unlock');
// Migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');
