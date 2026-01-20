<?php

namespace Deployer;

use Symfony\Component\Yaml\Yaml;

require 'recipe/symfony.php';
require 'contrib/cachetool.php';
require __DIR__ . '/vendor/tijsverkoyen/deployer-sumo/sumo.php';

// Define some variables
set('client', '$client');
set('project', '$project');
set('repository', '$repository');
set('production_url', '$productionUrl');
set('production_user', '$productionUser');
set('php_version', '8.5');

set('symfony_env', 'prod');
set('writable_recursive', true);

// Define staging
host('dev03.sumocoders.eu')
    ->setRemoteUser('sites')
    ->set('labels', ['stage' => 'staging'])
    ->set('deploy_path', '~/apps/{{client}}/{{project}}')
    ->set('branch', 'staging')
    ->set('bin/php', '{{php_binary}}')
    ->set('cachetool', '/var/run/php_{{php_version_numeric}}_fpm_sites.sock')
    ->set('document_root', '~/php{{php_version_numeric}}/{{client}}/{{project}}')
    ->set('keep_releases', 2);

// Define production
//host('$host')
//    ->setRemoteUser('{{user}}')
//    ->set('labels', ['stage' => 'production'])
//    ->setPort(2244)
//    ->set('deploy_path', '~/wwwroot')
//    ->set('branch', 'master')
//    ->set('bin/php', '{{php_binary}}')
//    ->set('cachetool', '/data/vhosts/{{production_user}}/.sock/{{production_user}}-{{php_binary}}.sock --tmp-dir=/data/vhosts/{{production_user}}/.temp')
//    ->set('document_root', '~/wwwroot/www')
//    ->set('http_user', '{{production_user}}')
//    ->set('writable_mode', 'chmod') // Cloudstar only
//    ->set('keep_releases', 3);

/*************************
 * No need to edit below *
 *************************/

set('php_binary', function () {
    return 'php' . get('php_version');
});

set('php_version_numeric', function () {
    return (int) filter_var(get('bin/php'), FILTER_SANITIZE_NUMBER_INT);
});

set('use_relative_symlink', false);

// Shared files/dirs between deploys
add('shared_files', ['.env.local', 'app/config/parameters.yml']);
add('shared_dirs', ['src/Frontend/Files']);

// Writable dirs by web server
add('writable_dirs', [
    'src/Frontend/Cache',
    'src/Backend/Cache',
    'src/Frontend/Files',
    'var/cache',
    'var/logs'
]);

// Disallow stats
set('allow_anonymous_stats', false);

// Shared folder
set('shared_folder', '{{deploy_path}}/shared');

// composer
set('bin/composer', function () {
    if (!test('[ -f {{shared_folder}}/composer.phar ]')) {
        run("cd {{shared_folder}} && curl -sLO https://getcomposer.org/download/latest-stable/composer.phar");
    }

    return '{{bin/php}} {{shared_folder}}/composer.phar';
});
set('composer_options', '--verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader');

// Limit the number of releases that should be kept
set('keep_releases', 3);

/*****************
 * Task sections *
 *****************/
// Overrides tasks from symfony and deployer-sumo
task('database:migrate', function () {
});
task('sumo:assets:install', function () {
});
task('sumo:assets:build', function () {
});
task('sumo:assets:upload', function () {
});

// Fork CMS specific tasks
task(
    'fork:theme:build',
    function () {
        $packageFile = file_get_contents('package.json');
        $package = json_decode($packageFile, true);

        if (!array_key_exists('theme', $package)) {
            warning('No theme found in package.json');

            return;
        }

        $nvmPath = trim(shell_exec('echo $HOME/.nvm/nvm.sh'));

        if (file_exists($nvmPath)) {
            runLocally(
                '. ' . $nvmPath . ' && nvm use && nvm exec node_modules/.bin/gulp build && nvm exec npm run build'
            );
        } else {
            runLocally('node_modules/.bin/gulp build && npm run build');
        }
    }
)->desc('Run the build script which will build our needed assets.');
task(
    'fork:theme:upload',
    function () {
        $packageFile = file_get_contents('package.json');
        $package = json_decode($packageFile, true);

        if (!array_key_exists('theme', $package)) {
            warning('No theme found in package.json');

            return;
        }

        $theme = $package['theme'];
        $remotePath = '{{release_path}}/src/Frontend/Themes/' . $theme . '/Core';

        upload(__DIR__ . '/src/Frontend/Themes/' . $theme . '/Core/', $remotePath);
    }
)->desc('Upload bundle assets');

task(
    'fork:cache:clear',
    function () {
        run('{{bin/console}} fork:cache:clear --env={{symfony_env}}');
        run(
            'if [ -f {{deploy_path}}/shared/config/parameters.yml ]; then touch {{deploy_path}}/shared/config/parameters.yml; fi'
        );
    }
)->desc('Clear Fork CMS cache');

// Migrations
task('fork:migrations:database:run', function () {
    if (!test('[ -d {{release_path}}/migrations/ ]')) {
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
            warning('Running update.sql for ' . $shortName);
            run(
                'mysql --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' < ' . $shortName . '/update.sql'
            );
        }

        if (test('[ -f ' . $shortName . '/update.php ]')) {
            warning('Running update.php for ' . $shortName);
            run('cd {{release_path}} && {{bin/php}} ' . $dir . '/update.php');
        }
        run('echo ' . $shortName . ' | tee -a {{deploy_path}}/shared/database_migrations');
    }

    // remove DB backup
    run('rm {{deploy_path}}/mysql_backup.sql');
})->desc('Run database migrations');
task('fork:migrations:locale:run', function () {
    if (!test('[ -d {{release_path}}/migrations/ ]')) {
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
            warning('Installing locale.xml for ' . $shortName);
            run('{{bin/console}} forkcms:locale:import -f ' . $dir . '/locale.xml --env={{symfony_env}}');
        }
        run('echo ' . $shortName . ' | tee -a {{deploy_path}}/shared/locale_migrations');
    }
})->desc('Run locale migrations');

task(
    'fork:database:backup',
    function () {
        cd('{{deploy_path}}/shared/');
        $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];

        run(
            'mysqldump --skip-lock-tables --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' > {{deploy_path}}/mysql_backup.sql'
        );
    }
)->desc('Create a backup of the database');

/**********************
 * Flow configuration *
 **********************/
// Clear the Opcache
after('deploy:symlink', 'cachetool:clear:opcache');
// Unlock the deploy when it failed
after('deploy:failed', 'deploy:unlock');
// Migrations
before('deploy:symlink', 'database:migrate');
before('fork:cache:clear', 'fork:migrations:database:run');
before('fork:migrations:database:run', 'fork:migrations:locale:run');
before('fork:migrations:database:run', 'fork:database:backup');

// Build and upload theme
after('deploy:update_code', 'fork:theme:build');
after('fork:theme:build', 'fork:theme:upload');
// Clear Fork CMS cache after deploy
before('deploy:cache:clear', 'fork:cache:clear');
