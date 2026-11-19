<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkipPath(__DIR__ . '/app/AppKernel.php')
    ->withSymfonyContainerPhp(__DIR__ . '/var/cache/dev/ForkCMS_App_AppKernelDevDebugContainer.php')
    ->withPhpSets()
    ->withAttributesSets()
    ->withComposerBased()
    ->withImportNames(importShortClasses: false)
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
