<?php

namespace Backend\Modules\ContentBlocks\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class AddTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/content_blocks/index');
    }

    public function testFormIsDisplayed(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/content_blocks/add',
            [
                'Title<abbr data-toggle="tooltip" aria-label="Required field" title="Required field">*</abbr>',
                'Visible on site',
                'Add content block',
            ]
        );
    }
}
