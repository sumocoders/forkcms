<?php

namespace Backend\Modules\Dashboard\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class IndexTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/dashboard/index');
    }

    public function testIndexHasWidgets(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/dashboard/index',
            [
                'Blog: Latest comments',
                'FAQ: Feedback',
                'Analysis',
                'Users: Statistics',
            ]
        );
    }
}
