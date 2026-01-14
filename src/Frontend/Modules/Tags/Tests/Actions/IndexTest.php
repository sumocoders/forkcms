<?php

namespace Frontend\Modules\Tags\Tests\Actions;

use Backend\Modules\Tags\DataFixtures\LoadTagsModulesTags;
use Backend\Modules\Tags\DataFixtures\LoadTagsTags;
use Frontend\Core\Tests\FrontendWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class IndexTest extends FrontendWebTestCase
{
    public function testTagsIndexShowsTags(KernelBrowser $client): void
    {
        $this->loadFixtures(
            $client,
            [
                LoadTagsTags::class,
                LoadTagsModulesTags::class,
            ]
        );

        self::assertPageLoadedCorrectly(
            $client,
            '/en/tags',
            [
                'href="/en/tags/detail/most-used" rel="tag">',
                LoadTagsTags::TAGS_TAG_2_NAME,
                '<span class="badge badge-pill badge-default">6</span>',
            ]
        );
    }
}
