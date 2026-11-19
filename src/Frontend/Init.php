<?php

namespace Frontend;

/**
 * This class will initiate the frontend-application
 */
class Init extends \Common\Core\Init
{
    #[\Override]
    protected $allowedTypes = ['Frontend', 'FrontendAjax'];
}
