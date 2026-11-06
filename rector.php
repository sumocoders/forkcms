<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSymfonyContainerPhp(__DIR__ . '/var/cache/dev/ForkCMS_App_AppKernelDevDebugContainer.php')
    ->withAttributesSets()
    ->withComposerBased(doctrine: true)
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
