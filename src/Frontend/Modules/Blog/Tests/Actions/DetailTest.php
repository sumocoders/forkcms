<?php

namespace Frontend\Modules\Blog\Actions;

use Backend\Modules\Blog\DataFixtures\LoadBlogCategories;
use Backend\Modules\Blog\DataFixtures\LoadBlogPosts;
use Frontend\Core\Language\Language;
use Frontend\Core\Tests\FrontendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class DetailTest extends FrontendWebTestCase
{
    public function testBlogPostHasDetailPage(KernelBrowser $client): void
    {
        $this->loadFixtures(
            $client,
            [
                LoadBlogCategories::class,
                LoadBlogPosts::class,
            ]
        );

        self::assertPageLoadedCorrectly($client, '/en/blog', [LoadBlogPosts::BLOG_POST_TITLE]);
        self::assertClickOnLink($client, Language::lbl('ReadMore'), [LoadBlogPosts::BLOG_POST_TITLE]);
        self::assertCurrentUrlEndsWith($client, '/en/blog/detail/' . LoadBlogPosts::BLOG_POST_SLUG);
    }

    public function testNonExistingBlogPostGives404(KernelBrowser $client): void
    {
        self::assertHttpStatusCode404($client, '/en/blog/detail/non-existing');
    }
}
