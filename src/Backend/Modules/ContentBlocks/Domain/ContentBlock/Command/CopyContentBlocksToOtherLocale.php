<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Command;

use Backend\Core\Language\Locale;

final class CopyContentBlocksToOtherLocale
{
    /** This is used to be able to convert the old ids to the new ones if used in other places */
    public array $extraIdMap = [];

    public function __construct(public Locale $toLocale, public ?Locale $fromLocale = null)
    {
        if ($this->fromLocale === null) {
            $this->fromLocale = Locale::workingLocale();
        }
    }
}
