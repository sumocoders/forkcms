<?php

namespace Frontend\Modules\FormBuilder;

use Frontend\Core\Engine\Base\Config as FrontendBaseConfig;

/**
 * This is the configuration-object
 */
class Config extends FrontendBaseConfig
{
    /**
     * The default action
     *
     * @var string
     */
    #[\Override]
    protected $defaultAction = 'Form';
}
