<?php

namespace Common\Core\Twig\Extensions;

use Twig\Attribute\AsTwigFilter;

class UtmExtension
{
    #[AsTwigFilter(name: 'utm', isSafe: ['html'])]
    public function injectUtmTags(
        string $html,
        string $source,
        string $medium,
        string $campaign,
    ): string {
        if ($html === '') {
            return $html;
        }

        return preg_replace_callback(
            '/href="(https?:[^"]+)"/i',
            function (array $matches) use ($campaign, $source, $medium): string {
                $href = $matches[1];

                $utmParams = http_build_query([
                    'utm_source' => $source,
                    'utm_medium' => $medium,
                    'utm_campaign' => $campaign,
                ]);

                $separator = str_contains($href, '?') ? '&' : '?';

                return 'href="' . $href . $separator . $utmParams . '"';
            },
            $html
        );
    }
}
