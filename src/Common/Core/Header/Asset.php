<?php

namespace Common\Core\Header;

use DateTimeImmutable;

final class Asset implements \Stringable
{
    /** @var Priority */
    private $priority;

    /** @var DateTimeImmutable */
    private $createdOn;

    public function __construct(private string $file, private bool $addTimestamp = true, ?Priority $priority = null)
    {
        $this->priority = $priority ?? Priority::standard();
        $this->createdOn = new DateTimeImmutable();
    }

    public function compare(Asset $asset): int
    {
        $comparison = $this->priority->compare($asset->priority);

        if ($comparison === 0) {
            $comparison = $this->createdOn <=> $asset->createdOn;
        }

        return $comparison;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function forCacheUrl(string $cacheUrl): self
    {
        $cacheAsset = clone $this;
        $cacheAsset->file = $cacheUrl;

        return $cacheAsset;
    }

    public function __toString(): string
    {
        if (!$this->addTimestamp) {
            return $this->file;
        }

        // check if we need to use a ? or &
        $separator = mb_strpos($this->file, '?') === false ? '?' : '&';

        return $this->file . $separator . 'm=' . @filemtime(__DIR__ . '/../../../../' . $this->file);
    }
}
