<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/lib',
        __DIR__.'/test',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@autoPHPUnitMigration:risky' => true,
        'php_unit_attributes' => true,
        'php_unit_data_provider_return_type' => true,
        'void_return' => true,
        'declare_strict_types' => true,
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->setFinder($finder);
