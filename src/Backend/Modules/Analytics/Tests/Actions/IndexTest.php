<?php

namespace Backend\Modules\Analytics\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class IndexTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/analytics/index');
    }

    public function testRedirectToSettingsActionWhenTheAnalyticsModuleIsNotConfigured(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertGetsRedirected(
            $client,
            '/private/en/analytics/index',
            '/private/en/analytics/settings'
        );
    }
}
