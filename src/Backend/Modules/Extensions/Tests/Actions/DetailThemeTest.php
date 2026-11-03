<?php

namespace Backend\Modules\ContentBlocks\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class DetailThemeTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/extensions/detail_theme?theme=Bootstrap');
    }

    public function testIndexHasModules(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/extensions/detail_theme?theme=Bootstrap',
            [
                'Core/Layout/Templates/Home.html.twig',
                'class="positions">main, top',
                'Version',
                'Description',
            ]
        );
    }
}
