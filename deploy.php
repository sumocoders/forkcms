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

// TODO gulp build?
/*
task(
    'build:assets:npm',
    function () {
        if (commandExist('nvm')) {
            run('nvm install');
            run('nvm exec npm run build');
        } else {
            run('npm run build');
        }
    }
)
    ->desc('Run the build script which will build our needed assets.')
    ->local();
*/

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

// TODO Symlinks

// Upload tasks
/*task(
    'upload:assets',
    function () {
        upload(__DIR__ . '/public/build', '{{release_path}}/public');
    }
)
    ->desc('Uploads the assets')
    ->addBefore('build:assets:npm');
after('deploy:update_code', 'upload:assets');*/

/**********************
 * Flow configuration *
 **********************/
// Clear the Opcache
after('deploy:symlink', 'cachetool:clear:opcache');
// Unlock the deploy when it failed
after('deploy:failed', 'deploy:unlock');
// Migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');
