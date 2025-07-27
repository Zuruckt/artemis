<?php
declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return new Config()
    ->setRules([
        '@PhpCsFixer:risky' => true,
        'declare_strict_types' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
