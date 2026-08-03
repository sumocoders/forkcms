<?php

namespace Backend\Modules\Settings\Ajax;

use Backend\Core\Engine\Base\AjaxAction;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Response;

class GenerateSitemap extends AjaxAction
{
    public function execute(): void
    {
        parent::execute();

        $kernel = $this->getKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => 'forkcms:sitemap:generate',
            ]
        );

        $exitCode = $application->run($input);

        $this->output(
            Response::HTTP_OK,
            [
                'exitCode' => $exitCode,
            ]
        );
    }
}
