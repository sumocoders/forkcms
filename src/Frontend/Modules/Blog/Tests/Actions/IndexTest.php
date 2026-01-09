<?php

namespace Frontend\Modules\Blog\Tests\Actions;

use Frontend\Core\Tests\FrontendWebTestCase;
use Backend\Modules\Blog\DataFixtures\LoadBlogCategories;
use Backend\Modules\Blog\DataFixtures\LoadBlogPosts;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class IndexTest extends FrontendWebTestCase
{
    public function testIndexContainsBlogPosts(KernelBrowser $client): void
    {
        $this->loadFixtures(
            $client,
            [
                LoadBlogCategories::class,
                LoadBlogPosts::class,
            ]
        );

        self::assertPageLoadedCorrectly($client, '/en/blog', [LoadBlogPosts::BLOG_POST_TITLE]);
    }

    public function testNonExistingPageGives404(KernelBrowser $client): void
    {
        self::assertHttpStatusCode200($client, '/en/blog');
        self::assertHttpStatusCode404($client, '/en/blog', 'GET', ['page' => 34]);
    }
}
