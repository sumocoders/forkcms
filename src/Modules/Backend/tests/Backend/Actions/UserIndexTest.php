<?php

use ForkCMS\Modules\Backend\DataFixtures\UserFixture;
use ForkCMS\Modules\Backend\DataFixtures\UserGroupFixture;
use ForkCMS\Modules\Backend\tests\BackendWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserIndexTest extends BackendWebTestCase
{
    protected const TEST_URL = '/private/en/backend/user-index';

    protected function setUp(): void
    {
        parent::setUp();
        self::request(Request::METHOD_GET, self::TEST_URL);
    }

    public function testAuthenticationIsNeeded(): void
    {
        self::assertAuthenticationIsNeeded(self::TEST_URL);
    }

    public function testPageLoads(): void
    {
        self::loginBackendUser();
        self::assertPageLoadedCorrectly(self::TEST_URL, ['Display name', 'E-mail', 'Super admin', 'Normal user']);
        self::assertPageTitleSame('Users | settings | Fork CMS | Fork CMS');
        self::assertHasLink('Add', '/private/en/backend/user-add');
    }

    public function testDataGrid(): void
    {
        $user = self::loginBackendUser();

        self::assertDataGridHasLink($user->getEmail(), '/private/en/backend/user-edit/' . $user->getId());
        self::assertDataGridHasLink('user@fork-cms.com');
        self::assertDataGridNotHasLink('demo@fork-cms.com');
        self::filterDataGrid('User.email', 'user@fork-cms.com');
        self::assertDataGridHasLink('user@fork-cms.com');
        self::assertDataGridNotHasLink('demo@fork-cms.com');
        self::assertDataGridNotHasLink($user->getEmail());
        self::filterDataGrid('User.email', $user->getEmail());
        self::filterDataGrid('User.displayName', 'demo');
        self::assertDataGridIsEmpty();
        self::filterDataGrid('User.displayName', $user->getDisplayName());
        self::assertDataGridHasLink($user->getEmail(), '/private/en/backend/user-edit/' . $user->getId());

        self::assertHttpStatusCode404('test', 'bob', []);
    }

    protected static function getClassFixtures(): array
    {
        return [
            new UserGroupFixture(),
            new UserFixture(self::getContainer()->get(UserPasswordHasherInterface::class)),
        ];
    }
}
