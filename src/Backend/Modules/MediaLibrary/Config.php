<?php

namespace Backend\Modules\MediaLibrary;

use Backend\Core\Engine\Base\Config as BackendBaseConfig;

/**
 * This is the configuration-object for the media library module
 */
class Config extends BackendBaseConfig
{
    /**
     * The default action
     *
     * @var string
     */
    #[\Override]
    protected $defaultAction = 'MediaItemIndex';
}
