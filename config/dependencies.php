<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

$aggregator = new ConfigAggregator(
    providers: [
        new PhpFileProvider(__DIR__ . '/common/*.php'),
    ],
);

return $aggregator->getMergedConfig();
