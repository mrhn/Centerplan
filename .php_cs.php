<?php

$excludeDirs = [
    'bootstrap/',
    'config/',
    'public/',
    'resources/',
    'storage/',
    'vendor/',
];

$excludeFiles = [
    '_ide_helper.php',
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude($excludeDirs)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->notName($excludeFiles);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,
            '@PSR1' => true,
            '@PSR2' => true,
            'align_multiline_comment' => false,
            'blank_line_before_return' => true,
            'php_unit_test_annotation' => false,
            'php_unit_method_casing' => ['case' => 'snake_case'],
            // for larastan
            'return_assignment' => false,
            // i do not have the time to write custom assertions
            'php_unit_strict' => false,
        ]
    )
    ->setFinder($finder)
    ->setUsingCache(false);
