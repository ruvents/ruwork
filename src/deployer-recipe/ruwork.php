<?php

declare(strict_types=1);

namespace Deployer;

require_once 'recipe/symfony4.php';
require_once __DIR__.'/host.php';
require_once __DIR__.'/symfony.php';

/*
 * Configuration
 */

set('composer_options', 'install --no-dev --no-suggest --no-scripts --verbose --prefer-dist --no-progress --no-interaction --classmap-authoritative');

set('shared_dirs', array_merge(get('shared_dirs'), [
    'public/media',
    'public/uploads',
]));

set('shared_files', array_merge(get('shared_files'), [
    'config/parameters.yaml',
]));

/*
 * Tasks
 */

task('assets:install', static function (): void {
    symfony('assets:install {{release_path}}/public');
});

task('cache:clear', static function (): void {
    symfony('cache:clear --no-warmup');
});

task('cache:warmup', static function (): void {
    symfony('cache:warmup');
});

task('ckeditor:install', static function (): void {
    symfony('ckeditor:install --release=full --clear=drop');
});

task('doctrine:migrations:migrate', static function (): void {
    symfony('doctrine:migrations:migrate --allow-no-migration');
});

task('fos:js-routing:dump', static function (): void {
    symfony('fos:js-routing:dump --format=json --target={{release_path}}/assets/js/_routes.json');
});

task('yarn:build', static function (): void {
    run('yarn build');
});

task('yarn:install', static function (): void {
    run('yarn install');
});
