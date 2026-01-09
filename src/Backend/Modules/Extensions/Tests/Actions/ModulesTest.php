<?php

namespace Backend\Modules\ContentBlocks\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ModulesTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/extensions/modules');
    }

    public function testIndexHasModules(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/extensions/modules',
            [
                'Installed modules',
                'Upload module',
                'Find modules',
            ]
        );
        self::assertResponseDoesNotHaveContent($client->getResponse(), 'Not installed modules');
    }
}
