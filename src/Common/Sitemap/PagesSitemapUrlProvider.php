<?php

namespace Common\Sitemap;

use Frontend\Core\Engine\Navigation as FrontendNavigation;
use SpoonDatabase;

final class PagesSitemapUrlProvider implements SitemapUrlProviderInterface
{
    public function __construct(private SpoonDatabase $database)
    {
    }

    public function getUrls(string $locale): array
    {
        $records = (array) $this->database->getRecords(
            'SELECT i.id, m.seo_index
             FROM pages AS i
             INNER JOIN meta AS m ON i.meta_id = m.id
             WHERE i.status = ? AND i.hidden = ? AND i.language = ? AND i.id != ?',
            ['active', 'N', $locale, 404]
        );

        if (empty($records)) {
            return [];
        }

        $siteUrl = rtrim(SITE_URL, '/');

        $urls = [];
        foreach ($records as $record) {
            if ($record['seo_index'] === 'noindex') {
                continue;
            }

            $path = FrontendNavigation::getUrl((int) $record['id'], $locale);

            $urls[] = new SitemapUrl(
                loc: $siteUrl . '/' . ltrim($path, '/'),
                changefreq: 'weekly',
                priority: 0.8,
            );
        }

        return $urls;
    }
}
