<?php

namespace Common\Sitemap;

final class SitemapUrl
{
    public string $loc;
    public ?\DateTimeInterface $lastmod;
    public ?string $changefreq;
    public ?float $priority;

    public function __construct(
        string $loc,
        ?\DateTimeInterface $lastmod = null,
        ?string $changefreq = null,
        ?float $priority = null
    ) {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }
}
