<?php

namespace Backend\Modules\Error\Tests\Action;

use Backend\Core\Tests\BackendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class IndexTest extends BackendWebTestCase
{
    public function testAuthenticationIsNotNeeded(KernelBrowser $client): void
    {
        $this->logout($client);

        self::assertHttpStatusCode($client, '/private/en/error/index', Response::HTTP_BAD_REQUEST);
        self::assertCurrentUrlEndsWith($client, '/private/en/error/index');
    }

    public function testModuleNotAllowed(KernelBrowser $client): void
    {
        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/error/index?type=module-not-allowed',
            [
                'You have insufficient rights for this module.',
            ],
            Response::HTTP_FORBIDDEN
        );
    }

    public function testActionNotAllowed(KernelBrowser $client): void
    {
        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/error/index?type=action-not-allowed',
            [
                'You have insufficient rights for this action.',
            ],
            Response::HTTP_FORBIDDEN
        );
    }

    public function testNotFound(KernelBrowser $client): void
    {
        self::assertPageLoadedCorrectly(
            $client,
            '/private/en/error/index?type=not-found',
            [
                'This page was lost at sea.',
            ],
            Response::HTTP_NOT_FOUND
        );
    }
}
