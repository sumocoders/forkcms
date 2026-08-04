<?php

namespace Backend\Modules\Settings\Ajax;

use Backend\Core\Engine\Base\AjaxAction;
use Common\Sitemap\SitemapGenerator;
use Symfony\Component\HttpFoundation\Response;

class GenerateSitemap extends AjaxAction
{
    public function execute(): void
    {
        parent::execute();

        try {
            $count = $this->get(SitemapGenerator::class)->generate();
        } catch (\Throwable $e) {
            $this->output(Response::HTTP_INTERNAL_SERVER_ERROR, null, $e->getMessage());

            return;
        }

        $this->output(Response::HTTP_OK, ['count' => $count]);
    }
}
