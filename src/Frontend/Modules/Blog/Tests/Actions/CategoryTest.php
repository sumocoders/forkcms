<?php

namespace Frontend\Modules\Blog\Actions;

use Backend\Modules\Blog\DataFixtures\LoadBlogCategories;
use Backend\Modules\Blog\DataFixtures\LoadBlogPosts;
use Frontend\Core\Language\Language;
use Frontend\Core\Tests\FrontendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class CategoryTest extends FrontendWebTestCase
{
    public function testCategoryHasPage(KernelBrowser $client): void
    {
        $this->loadFixtures(
            $client,
            [
                LoadBlogCategories::class,
                LoadBlogPosts::class,
            ]
        );

        self::assertPageLoadedCorrectly($client, '/en/blog/category/' . LoadBlogCategories::BLOG_CATEGORY_SLUG, [LoadBlogCategories::BLOG_CATEGORY_TITLE]);
    }

    public function testNonExistingCategoryPostGives404(KernelBrowser $client): void
    {
        self::assertHttpStatusCode404($client, '/en/blog/category/non-existing');
    }

    public function testCategoryPageContainsBlogPost(KernelBrowser $client): void
    {
        $this->loadFixtures(
            $client,
            [
                LoadBlogCategories::class,
                LoadBlogPosts::class,
            ]
        );

        self::assertPageLoadedCorrectly($client, '/en/blog/category/' . LoadBlogCategories::BLOG_CATEGORY_SLUG, [LoadBlogCategories::BLOG_CATEGORY_TITLE]);
        self::assertClickOnLink($client, Language::lbl('ReadMore'), [LoadBlogPosts::BLOG_POST_TITLE]);
        self::assertCurrentUrlEndsWith($client, '/en/blog/detail/' . LoadBlogPosts::BLOG_POST_SLUG);
    }

    public function testNonExistingPageGives404(KernelBrowser $client): void
    {
        $this->loadFixtures(
            $client,
            [
                LoadBlogCategories::class,
                LoadBlogPosts::class,
            ]
        );

        self::assertPageLoadedCorrectly($client, '/en/blog/category/' . LoadBlogCategories::BLOG_CATEGORY_SLUG, [LoadBlogCategories::BLOG_CATEGORY_TITLE]);
        self::assertHttpStatusCode404($client, '/en/blog/category/' . LoadBlogCategories::BLOG_CATEGORY_SLUG, 'GET', ['page' => 34]);
    }
}
