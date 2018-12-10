<?php

declare(strict_types=1);

namespace Deployer;

use Deployer\Task\Context;

require 'recipe/common.php';

function symfonyCommand(string $command): string
{
    return \sprintf('{{bin/php}} {{release_path}}/bin/console %s --no-interaction', $command);
}

function symfony(string $command): void
{
    run(symfonyCommand($command));
}

function symfonyLocally(string $command): void
{
    runLocally(symfonyCommand($command));
}

// Facts

set('host', static function (): string {
    return (string) Context::get()->getHost();
});

// Configuration

set('composer_options', 'install --no-dev --no-suggest --no-scripts --verbose --prefer-dist --no-progress --no-interaction --classmap-authoritative');

set('shared_dirs', [
    'public/media',
    'public/uploads',
    'var/log',
    'var/sessions',
]);

set('shared_files', [
    '.env',
    'config/parameters.yaml',
]);

set('writable_dirs', [
    'var',
]);

// Tasks

task('assets:install', static function (): void {
    symfony('assets:install {{release_path}}/public');
});

task('cache:warmup', static function (): void {
    symfony('cache:warmup');
});

task('ckeditor:install', static function (): void {
    symfony('ckeditor:install --release=full --clear=drop');
});

task('doctrine:database:migrate', static function (): void {
    symfony('doctrine:migrations:migrate --allow-no-migration');
});

task('gulp:build', static function (): void {
    runLocally('node node_modules/gulp/bin/gulp.js build');
    runLocally('scp -r public/build/ {{host}}:{{release_path}}/public');
});

task('yarn:install', static function (): void {
    runLocally('yarn install');
});

// Deploy

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:shared',
    'deploy:vendors',
    'cache:warmup',
    'assets:install',
    'yarn:install',
    'gulp:build',
    'doctrine:database:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);
