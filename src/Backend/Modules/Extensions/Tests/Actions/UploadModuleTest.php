<?php

namespace Backend\Modules\ContentBlocks\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class UploadModuleTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/extensions/upload_module');
    }

    public function testUploadPage(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/extensions/upload_module',
            [
                'Install',
                '<label for="file" class="control-label">',
            ]
        );
    }
}
