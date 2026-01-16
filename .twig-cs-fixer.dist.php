<?php

$ruleset = new TwigCsFixer\Ruleset\Ruleset();

// You can start from a default standard
$ruleset->addStandard(new TwigCsFixer\Standard\TwigCsFixer());
$ruleset->addStandard(new TwigCsFixer\Standard\Symfony());
$ruleset->addStandard(new TwigCsFixer\Standard\Twig());

$config = new TwigCsFixer\Config\Config();
$config->setRuleset($ruleset);

return $config;
