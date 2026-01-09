<?php

namespace Backend\Modules\ContentBlocks\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class IndexTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/content_blocks/index');
    }

    public function testIndexHasNoItems(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/content_blocks/index',
            [
                'There are no items yet.',
                'Add content block',
            ]
        );
    }
}
