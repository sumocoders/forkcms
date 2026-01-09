<?php

namespace Backend\Modules\Pages\Tests\Actions;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class IndexTest extends BackendWebTestCase
{
    public function testAuthenticationIsNeeded(KernelBrowser $client): void
    {
        self::assertAuthenticationIsNeeded($client, '/private/en/pages/index');
    }

    public function testIndexContainsPages(KernelBrowser $client): void
    {
        $this->login($client);

        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/pages/index',
            [
                'Home',
                'Add page',
                'Recently edited',
            ]
        );
    }
}
