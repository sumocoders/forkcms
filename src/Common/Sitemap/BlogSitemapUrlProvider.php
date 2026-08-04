<?php

namespace Common\Sitemap;

use Frontend\Core\Engine\Navigation as FrontendNavigation;
use SpoonDatabase;

final class BlogSitemapUrlProvider implements SitemapUrlProviderInterface
{
    public function __construct(private SpoonDatabase $database)
    {
    }

    public function getUrls(string $locale): array
    {
        $records = (array) $this->database->getRecords(
            'SELECT i.id, i.publish_on, m.url, m.seo_index
             FROM blog_posts AS i
             INNER JOIN meta AS m ON i.meta_id = m.id
             WHERE i.status = ? AND i.hidden = ? AND i.language = ? AND i.publish_on <= ?',
            ['active', false, $locale, gmdate('Y-m-d H:i:s')]
        );

        if (empty($records)) {
            return [];
        }

        $basePath = FrontendNavigation::getUrlForBlock('Blog', 'Detail', $locale);
        $siteUrl = rtrim(SITE_URL, '/');

        $urls = [];
        foreach ($records as $record) {
            if ($record['seo_index'] === 'noindex') {
                continue;
            }

            $urls[] = new SitemapUrl(
                loc: $siteUrl . '/' . ltrim($basePath, '/') . '/' . $record['url'],
                lastmod: new \DateTimeImmutable($record['publish_on']),
                changefreq: 'weekly',
                priority: 0.6,
            );
        }

        return $urls;
    }
}
