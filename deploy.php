<?php

namespace Deployer;

use Symfony\Component\Yaml\Yaml;

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
    // ->set('branch', 'staging')
    ->set('branch', '430-replace-capistrano-with-deployer')
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
// Upload tasks
/**
 * Install assets from public dir of bundles
 * @Override from symfony.php - don't know if we need this for Fork
 */
task(
    'deploy:assets:install',
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

        $theme = $package['theme'];
        $remotePath = '{{release_path}}/src/Frontend/Themes/' . $theme . '/Core';

        upload(__DIR__ . '/src/Frontend/Themes/' . $theme . '/Core/', $remotePath);
    }
)->desc('Generate and upload bundle assets');
after('deploy:update_code', 'deploy:assets:install');

/**
 * Migrate database
 * @Override from symfony.php which executes doctrine:migrations
 */
task('database:migrate', function () {
    cd('{{deploy_path}}/shared/');

    if (!test('[ -f executed_migrations ]')) {
        run('touch executed_migrations');
    }

    $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];
    $executedMigrations = explode("\n", run('cat executed_migrations'));

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
            writeln('Running update.sql for ' . $shortName);

            run('mysql --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' < ' . $shortName . '/update.sql');
        }

        if (test('[ -f ' . $shortName . '/locale.xml ]')) {
            writeln('Installing locale.xml for ' . $shortName);

            run('{{bin/php}} {{bin/console}} forkcms:locale:import -f ' . $dir . '/locale.xml --env={{symfony_env}}');
        }

        run('echo ' . $shortName . ' | tee -a {{deploy_path}}/shared/executed_migrations');
    }

    // remove DB backup
    run('rm {{deploy_path}}/mysql_backup.sql');
})->desc('Migrate database and locale');

task(
    'database:backup',
    function () {
        cd('{{deploy_path}}/shared/');
        $parameters = Yaml::parse(run('cat app/config/parameters.yml'))['parameters'];

        run('mysqldump --skip-lock-tables --default-character-set="utf8" --host=' . $parameters['database.host'] . ' --port=' . $parameters['database.port'] . ' --user=' . $parameters['database.user'] . ' --password=' . $parameters['database.password'] . ' ' . $parameters['database.name'] . ' > {{deploy_path}}/mysql_backup.sql');
    }
)->desc('Create a backup of the database');
before('database:migrate', 'database:backup');

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

desc('Enable a redirect page, all traffic will be redirected to this page.');
task(
    'sumo:redirect:enable',
    function () {
        if (!get('production_url', false)) {
            throw new \RuntimeException("Set a production url");
        }

        set('redirect_path', get('deploy_path') . '/redirect');

        run('mkdir -p {{redirect_path}}');
        run(
            'wget -qO {{redirect_path}}/index.php http://static.sumocoders.be/redirect2/index.phps'
        );
        run(
            'wget -qO {{redirect_path}}/.htaccess http://static.sumocoders.be/redirect2/htaccess'
        );
        run(
            'sed -i "s|<real-url>|{{production_url}}|" {{redirect_path}}/index.php'
        );

        run('rm {{document_root}}');
        run('{{bin/symlink}} {{redirect_path}} {{document_root}}');
    }
)->addAfter('cachetool:clear:opcache');

desc('Replace the local files with the remote files');
task(
    'sumo:files:get',
    function () {
        $sharedDirectories = get('shared_dirs');
        if (!is_array($sharedDirectories) || empty($sharedDirectories)) {
            return;
        }

        foreach ($sharedDirectories as $directory) {
            $path = '{{deploy_path}}/shared/' . $directory;

            if (test(sprintf('[ -d %1$s ]', $path))) {
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
            upload('./' . $directory, '{{deploy_path}}/shared/');
        }
    }
);

/**********************
 * Flow configuration *
 **********************/
before('fork:cache:clear', 'database:migrate');
// Clear the Opcache
after('deploy:symlink', 'cachetool:clear:opcache');
// Unlock the deploy when it failed
after('deploy:failed', 'deploy:unlock');
// Migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');
