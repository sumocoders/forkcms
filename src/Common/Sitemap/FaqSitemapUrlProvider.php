<?php

namespace Common\Sitemap;

use Frontend\Core\Engine\Navigation as FrontendNavigation;
use SpoonDatabase;

final class FaqSitemapUrlProvider implements SitemapUrlProviderInterface
{
    public function __construct(private SpoonDatabase $database)
    {
    }

    public function getUrls(string $locale): array
    {
        $records = (array) $this->database->getRecords(
            'SELECT i.id, m.url, m.seo_index
             FROM faq_questions AS i
             INNER JOIN meta AS m ON i.meta_id = m.id
             WHERE i.hidden = ? AND i.language = ?',
            [false, $locale]
        );

        if (empty($records)) {
            return [];
        }

        $basePath = FrontendNavigation::getUrlForBlock('Faq', 'Detail', $locale);
        $siteUrl = rtrim(SITE_URL, '/');

        $urls = [];
        foreach ($records as $record) {
            if ($record['seo_index'] === 'noindex') {
                continue;
            }

            $urls[] = new SitemapUrl(
                loc: $siteUrl . '/' . ltrim($basePath, '/') . '/' . $record['url'],
                changefreq: 'monthly',
                priority: 0.5,
            );
        }

        return $urls;
    }
}
