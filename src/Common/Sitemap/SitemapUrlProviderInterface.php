<?php

namespace Common\Sitemap;

interface SitemapUrlProviderInterface
{
    /** @return SitemapUrl[] */
    public function getUrls(string $locale): array;
}
