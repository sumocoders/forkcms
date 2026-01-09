<?php

namespace Backend\Modules\ContentBlocks\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ThemesTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/extensions/themes');
    }

    public function testIndexHasModules(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/extensions/themes',
            [
                'Installed themes',
                'Upload theme',
                'Find themes',
            ]
        );
    }
}
