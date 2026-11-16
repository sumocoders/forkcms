<?php

namespace Frontend\Modules\ContentBlocks;

use Frontend\Core\Engine\Base\Config as BaseConfig;

/**
 * This is the configuration-object
 */
class Config extends BaseConfig
{
    /**
     * The default action
     *
     * @var string
     */
    #[\Override]
    protected $defaultAction = 'Detail';
}
