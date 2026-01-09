<?php

namespace Backend\Modules\Authentication\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class LogoutTest extends BackendWebTestCase
{
    public function testLogoutActionRedirectsYouToLoginAfterLoggingOut(KernelBrowser $client): void
    {
        $this->login($client);

        $client->request('GET', '/private/en/authentication/logout');
        $client->followRedirect();

        self::assertStringContainsString(
            '/private/en/authentication/index',
            $client->getHistory()->current()->getUri()
        );
    }
}
