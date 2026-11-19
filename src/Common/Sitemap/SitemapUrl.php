<?php

namespace Common\Sitemap;

final class SitemapUrl
{
    public function __construct(public string $loc, public ?\DateTimeInterface $lastmod = null, public ?string $changefreq = null, public ?float $priority = null)
    {
    }
}
