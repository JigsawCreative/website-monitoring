<?php

declare(strict_types=1);

use Fsylum\RectorWordPress\Set\WordPressLevelSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])

    ->withSets([
        WordPressLevelSetList::UP_TO_WP_6_8,
    ])

    ->withPhpSets(php81: true)
    ->withTypeCoverageLevel(5)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);