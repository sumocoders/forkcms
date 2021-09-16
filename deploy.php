<?php

namespace Deployer;

use Symfony\Component\Yaml\Yaml;

require 'recipe/symfony3.php';
require 'recipe/cachetool.php';
require 'recipe/sentry.php';
require __DIR__ . '/vendor/tijsverkoyen/deployer-sumo/sumo.php';

// Define some variables
set('client', '$client');
set('project', '$project');
set('repository', '$repository');
set('user', '$user');
set('release', 'current');
set('production_url', '$productionUrl');
set('sentry_organization', '$sentryOrganization');
set('sentry_project_slug', '$sentryProjectSlug');
set('sentry_token', '$sentryToken');

// Define staging
host('dev03.sumocoders.eu')
    ->user('{{user}}')
    ->stage('staging')
    ->set('deploy_path', '~/apps/{{client}}/{{project}}')
    ->set('branch', 'staging')
    ->set('bin/php', 'php7.4')
    ->set('bin/composer', '{{bin/php}} /home/sites/apps/{{client}}/{{project}}/shared/composer.phar')
    ->set('cachetool', '/var/run/php_74_fpm_sites.sock')
    ->set('document_root', '~/php74/{{client}}/{{project}}');

// Define production
//host('$host')
//    ->user('{{user}}')
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
add('writable_dirs', ['src/Frontend/Cache', 'src/Backend/Cache']);

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
// Upload tasks
/**
 * Install assets from public dir of bundles
 * @Override from symfony.php - don't know if we need this for Fork
 */
task(
    'deploy:assets:install',
    function () {
        // do nothing
    }
)->desc('Generate and upload bundle assets');

task(
    'composer:install',
    function () {
        cd('{{deploy_path}}/shared/');

        if (!test('[ -f composer.phar ]')) {
            run('curl -s https://getcomposer.org/installer | {{bin/php}}');
        }
    }
)->desc('Install composer.phar if it is not already installed');
after('deploy:shared', 'composer:install');

task(
    'deploy:theme:buid',
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

        if (commandExist('nvm')) {
            runLocally('nvm install');
            runLocally('nvm exec node_modules/.bin/gulp build');
        } else {
            runLocally('node_modules/.bin/gulp build');
        }
    }
)->desc('Generate bundle assets');
after('deploy:update_code', 'deploy:theme:build');

task(
    'deploy:theme:upload',
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
        $remotePath = '{{release_path}}/src/Frontend/Themes/' . $theme . '/Core';

        upload(__DIR__ . '/src/Frontend/Themes/' . $theme . '/Core/', $remotePath);
    }
)->desc('Upload bundle assets');
after('deploy:theme:build', 'deploy:theme:upload');

/**
 * Migrate database
 * @Override from symfony.php which executes doctrine:migrations
 */
task('database:migrate', function () {
    // do nothing - delete when we start using doctrine:migrations
})->desc('Migrate database');

/**
 * @Override from deployer-sumo/assets
 */
task('sumo:assets:install', function() {
    // do nothing - no public directory in fork
})->desc('Install bundle\'s web assets under a public directory');

task('database:update', function () {
    if (!test('[ -d {{release_path}}/migrations/')) {
        return;
    }

    cd('{{deploy_path}}/shared/');

    if (!test('[ -f database_migrations ]')) {
        run('touch database_migrations');
    }

    $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];
    $executedMigrations = explode("\n", run('cat database_migrations'));

    cd('{{release_path}}/migrations/');

    $dirs = explode(',', run('find {{release_path}}/migrations/* -maxdepth 0 -type d | tr "\n" ","'));
    foreach ($dirs as $dir) {
        if (empty($dir)) {
            continue;
        }

        $shortName = basename($dir);
        if (in_array($shortName, $executedMigrations)) {
            continue;
        }

        if (test('[ -f ' . $shortName . '/update.sql ]')) {
            writeln('<comment>Running update.sql for ' . $shortName . '</comment>');

            run('mysql --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' < ' . $shortName . '/update.sql');
        }

        if (test('[ -f ' . $shortName . '/update.php ]')) {
            writeln('<comment>Running update.php for ' . $shortName . '</comment>');

            run('cd {{release_path}} && {{bin/php}} ' . $dir . '/update.php');
        }

        run('echo ' . $shortName . ' | tee -a {{deploy_path}}/shared/database_migrations');
    }

    // remove DB backup
    run('rm {{deploy_path}}/mysql_backup.sql');
})->desc('Update database');

/**
 * Migrate database
 * @Override from symfony.php which executes doctrine:migrations
 */
task('locale:update', function () {
    if (!test('[ -d {{release_path}}/migrations/')) {
        return;
    }

    cd('{{deploy_path}}/shared/');

    if (!test('[ -f locale_migrations ]')) {
        run('touch locale_migrations');
    }

    $executedMigrations = explode("\n", run('cat locale_migrations'));

    cd('{{release_path}}/migrations/');

    $dirs = explode(',', run('find {{release_path}}/migrations/* -maxdepth 0 -type d | tr "\n" ","'));
    foreach ($dirs as $dir) {
        if (empty($dir)) {
            continue;
        }

        $shortName = basename($dir);
        if (in_array($shortName, $executedMigrations)) {
            continue;
        }

        if (test('[ -f ' . $shortName . '/locale.xml ]')) {
            writeln('<comment>Installing locale.xml for ' . $shortName . '</comment>');

            run('{{bin/php}} {{bin/console}} forkcms:locale:import -f ' . $dir . '/locale.xml --env={{symfony_env}}');
        }

        run('echo ' . $shortName . ' | tee -a {{deploy_path}}/shared/locale_migrations');
    }
})->desc('Update locale');

task(
    'database:backup',
    function () {
        cd('{{deploy_path}}/shared/');
        $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];

        run('mysqldump --skip-lock-tables --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' > {{deploy_path}}/mysql_backup.sql');
    }
)->desc('Create a backup of the database');
before('database:update', 'database:backup');

task(
    'sumo:db:create',
    function () {
        cd('{{deploy_path}}/shared/');
        $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];

        writeln(
            run('create_db ' . $parameters['database.name'])
        );
    }
)->desc('Create the database if it does not exist yet')
    ->onStage('staging');

task(
    'sumo:db:info',
    function () {
        cd('{{deploy_path}}/shared/');
        $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];

        writeln(
            run('info_db ' . $parameters['database.name'])
        );
    }
)->desc('Get info about the database')
    ->onStage('staging');

task(
    'sumo:db:get',
    function () {
        $localParameters = Yaml::parse(runLocally('cat app/config/parameters.yml'))['parameters'];

        cd('{{deploy_path}}/shared/');
        $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];

        run('mysqldump --skip-lock-tables --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' > {{deploy_path}}/db_download.tmp.sql');
        download(
            '{{deploy_path}}/db_download.tmp.sql',
            './db_download.tmp.sql'
        );
        run('rm {{deploy_path}}/db_download.tmp.sql');

        runLocally('mysql --default-character-set="utf8" --host=' . $localParameters['database.host'] . ' --port=' . $localParameters['database.port'] . ' --user=' . $localParameters['database.user'] . ' --password=' . $localParameters['database.password'] . ' ' . $localParameters['database.name'] . ' < ./db_download.tmp.sql');
        runLocally('rm ./db_download.tmp.sql');
    }
)->desc('Replace the local database with the remote database');

task(
    'sumo:db:put',
    function () {
        $localParameters = Yaml::parse(runLocally('cat app/config/parameters.yml'))['parameters'];

        cd('{{deploy_path}}/shared/');
        $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];

        runLocally('mysqldump --column-statistics=0 --lock-tables=false --set-charset --host=' . $localParameters['database.host'] . ' --port=' . $localParameters['database.port'] . ' --user=' . $localParameters['database.user'] . ' --password=' . $localParameters['database.password'] . ' ' . $localParameters['database.name'] . ' > ./db_upload.tmp.sql');
        upload('./db_upload.tmp.sql', '{{deploy_path}}/db_upload.tmp.sql');
        runLocally('rm ./db_upload.tmp.sql');

        cd('{{deploy_path}}/');

        run('mysql --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' < db_upload.tmp.sql');
        run('rm db_upload.tmp.sql');
    }
)->desc('Replace the remote database with the local database')
->addBefore('database:backup');

task(
    'fork:cache:clear',
    function() {
        run('{{bin/php}} {{bin/console}} fork:cache:clear --env={{symfony_env}}');
        run('if [ -f {{deploy_path}}/shared/config/parameters.yml ]; then touch {{deploy_path}}/shared/config/parameters.yml; fi');
    }
)
    ->desc('Clear Fork CMS cache');
before('deploy:cache:clear', 'fork:cache:clear');

desc('Replace the local files with the remote files');
task(
    'sumo:files:get',
    function () {
        $sharedDirectories = get('shared_dirs');
        if (!is_array($sharedDirectories) || empty($sharedDirectories)) {
            return;
        }

        // remove some system dirs
        $directoriesToIgnore = [
            'var/log',      // this directory may contain useful information
            'var/sessions', // this directory may contain active sessions
        ];
        $sharedDirectories = array_values(array_filter(
            $sharedDirectories,
            function ($element) use ($directoriesToIgnore) {
                return !in_array($element, $directoriesToIgnore);
            }
        ));

        foreach ($sharedDirectories as $directory) {
            $path = '{{deploy_path}}/shared/' . $directory;

            if ((int) run('ls {{deploy_path}}/shared/' . $directory . ' | wc -l') > 0) {
                download($path, $directory . '/../');
            }
        }
    }
);

desc('Replace the remote files with the local files');
task(
    'sumo:files:put',
    function () {
        // ask for confirmation
        if (!askConfirmation('Are you sure? This will overwrite files on production!')) {
            return;
        }

        $sharedDirectories = get('shared_dirs');
        if (!is_array($sharedDirectories) || empty($sharedDirectories)) {
            return;
        }

        // remove some system dirs
        $directoriesToIgnore = [
            'var/log',      // this directory may contain useful information
            'var/sessions', // this directory may contain active sessions
        ];
        $sharedDirectories = array_values(array_filter(
            $sharedDirectories,
            function ($element) use ($directoriesToIgnore) {
                return !in_array($element, $directoriesToIgnore);
            }
        ));

        foreach ($sharedDirectories as $directory) {
           upload('./' . $directory . '/', '{{deploy_path}}/shared/' . $directory);
        }
    }
);

/**********************
 * Flow configuration *
 **********************/
before('database:update', 'locale:update');
before('fork:cache:clear', 'database:update');
// Clear the Opcache
after('deploy:symlink', 'cachetool:clear:opcache');
// Unlock the deploy when it failed
after('deploy:failed', 'deploy:unlock');
// Migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');
