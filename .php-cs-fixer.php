<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'strict_param' => true,
    'no_unused_imports' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'static_lambda' => true,
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => true,
        'import_functions' => true,
    ],
])
    ->setFinder($finder);