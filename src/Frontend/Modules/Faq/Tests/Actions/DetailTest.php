<?php

namespace Frontend\Modules\Faq\Actions;

use Backend\Modules\Faq\DataFixtures\LoadFaqCategories;
use Backend\Modules\Faq\DataFixtures\LoadFaqQuestions;
use Frontend\Core\Tests\FrontendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class DetailTest extends FrontendWebTestCase
{
    public function testFaqHasDetailPage(KernelBrowser $client): void
    {
        $this->loadFixtures(
            $client,
            [
                LoadFaqCategories::class,
                LoadFaqQuestions::class,
            ]
        );

        self::assertPageLoadedCorrectly(
            $client,
            '/en/faq',
            [
                LoadFaqCategories::FAQ_CATEGORY_TITLE,
                LoadFaqQuestions::FAQ_QUESTION_TITLE,
            ]
        );

        self::assertClickOnLink(
            $client,
            LoadFaqQuestions::FAQ_QUESTION_TITLE,
            [
                '<title>' . LoadFaqQuestions::FAQ_QUESTION_TITLE,
            ]
        );
        self::assertCurrentUrlEndsWith($client, '/en/faq/detail/' . LoadFaqQuestions::FAQ_QUESTION_SLUG);
    }

    public function testNonExistingFaqGives404(KernelBrowser $client): void
    {
        self::assertHttpStatusCode404($client, '/en/faq/detail/non-existing');
    }
}
