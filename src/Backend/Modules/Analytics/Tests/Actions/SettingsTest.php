<?php

namespace Backend\Modules\Analytics\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class SettingsTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/analytics/settings');
    }

    public function testAnalyticsSettingsWorks(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly($client, '/private/en/analytics/settings', ['How to get your secret file?']);
    }
}
