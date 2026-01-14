<?php

namespace Backend\Modules\ContentBlocks\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class EditThemeTemplateTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded(
            $client,
            '/private/en/extensions/edit_theme_template?token=68ozixmy4j&id=3'
        );
    }

    public function testFormIsDisplayed(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/extensions/edit_theme_template?token=68ozixmy4j&id=3',
            [
                'Allow the user to upload an image.',
                'Positions',
                'If you want a position to display wider or higher in it\'s graphical representation',
            ]
        );
    }
}
