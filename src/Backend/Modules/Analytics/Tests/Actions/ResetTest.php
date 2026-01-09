<?php

namespace Backend\Modules\Analytics\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ResetTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/analytics/reset');
    }

    public function testAfterResetRedirectToSettings(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertGetsRedirected(
            $client,
            '/private/en/analytics/reset',
            '/private/en/analytics/settings'
        );
    }
}
