<?php

$config = require 'vendor/broadway/coding-standard/.php_cs.dist';

$config->setFinder(
    \PhpCsFixer\Finder::create()
        ->in([
            __DIR__ . '/lib',
            __DIR__ . '/test',
            __DIR__ . '/examples',
        ])
);

return $config;
