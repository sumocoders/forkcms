<?php

namespace Frontend\Modules\Faq\Tests\Actions;

use Frontend\Core\Tests\FrontendWebTestCase;
use Backend\Modules\Faq\DataFixtures\LoadFaqCategories;
use Backend\Modules\Faq\DataFixtures\LoadFaqQuestions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class IndexTest extends FrontendWebTestCase
{
    public function testFaqIndexContainsCategories(KernelBrowser $client): void
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
            [LoadFaqCategories::FAQ_CATEGORY_TITLE]
        );
    }

    public function testFaqIndexContainsQuestions(KernelBrowser $client): void
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
            [LoadFaqQuestions::FAQ_QUESTION_TITLE]
        );
    }
}
