<?php

declare(strict_types=1);

$config = new \PhpCsFixer\Config();

$config
    ->setRules([
        // Rule sets
        '@PHP74Migration:risky' => true,
        '@PHP80Migration' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        // Individual rules
        'array_syntax' => ['syntax' => 'short'],
        'protected_to_private' => false,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_separation' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        \PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/config',
            ])
            ->notPath('#c3.php#')
            ->append([__FILE__])
    );

return $config;
