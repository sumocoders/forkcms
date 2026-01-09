<?php

namespace Backend\Modules\Location\Command;

use Backend\Core\Language\Locale;

final class CopyLocationWidgetsToOtherLocale
{
    /** @var array this is used to be able to convert the old ids to the new ones if used in other places */
    public array $extraIdMap = [];

    public function __construct(public Locale $toLocale, public ?Locale $fromLocale = null)
    {
        if ($fromLocale === null) {
            $fromLocale = Locale::workingLocale();
        }
        $this->fromLocale = $fromLocale;
    }
}
