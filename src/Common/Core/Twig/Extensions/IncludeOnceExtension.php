<?php

namespace Common\Core\Twig\Extensions;

use Twig\Attribute\AsTwigFunction;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\SandboxExtension;

final class IncludeOnceExtension
{
    /** @var array */
    private $includedTemplates = [];

    #[AsTwigFunction(name: 'include_once', needsEnvironment: true, needsContext: true, isSafe: ['all'])]
    public function includeOnce(
        Environment $env,
        array $context,
        string $template,
        array $variables = [],
        bool $withContext = true,
        bool $ignoreMissing = false,
        bool $sandboxed = false
    ): string {
        if ($this->isIncluded($template)) {
            return '';
        }

        $alreadySandboxed = false;
        $sandbox = null;
        $output = '';
        $isSandboxed = $sandboxed && $env->hasExtension(SandboxExtension::class);

        if ($withContext) {
            $variables = array_merge($context, $variables);
        }

        if ($isSandboxed) {
            $sandbox = $env->getExtension(SandboxExtension::class);
            if (!$alreadySandboxed = $sandbox->isSandboxed()) {
                $sandbox->enableSandbox();
            }
        }

        try {
            try {
                $loaded = $env->resolveTemplate($template);
                $output = $loaded ? $loaded->render($variables) : '';
            } catch (LoaderError $e) {
                if (!$ignoreMissing) {
                    throw $e;
                }
            }
        } finally {
            if ($isSandboxed && !$alreadySandboxed) {
                $sandbox->disableSandbox();
            }
        }

        // this needs to happen after we capture the output so we can check if it was previously included
        $this->setIncluded($template);

        return $output;
    }

    #[AsTwigFunction(name: 'is_included')]
    public function isIncluded(string $template): bool
    {
        return $this->includedTemplates[$template] ?? false;
    }

    #[AsTwigFunction(name: 'set_included')]
    public function setIncluded(string $template, bool $isIncluded = true): void
    {
        $this->includedTemplates[$template] = $isIncluded;
    }
}
