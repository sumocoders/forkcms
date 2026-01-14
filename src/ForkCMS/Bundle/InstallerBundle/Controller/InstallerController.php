<?php

namespace ForkCMS\Bundle\InstallerBundle\Controller;

use Common\Exception\ExitException;
use ForkCMS\Bundle\InstallerBundle\Entity\InstallationData;
use ForkCMS\Bundle\InstallerBundle\Form\Handler\DatabaseHandler;
use ForkCMS\Bundle\InstallerBundle\Form\Handler\InstallerHandler;
use ForkCMS\Bundle\InstallerBundle\Form\Handler\LanguagesHandler;
use ForkCMS\Bundle\InstallerBundle\Form\Handler\LoginHandler;
use ForkCMS\Bundle\InstallerBundle\Form\Handler\ModulesHandler;
use ForkCMS\Bundle\InstallerBundle\Form\Type\DatabaseType;
use ForkCMS\Bundle\InstallerBundle\Form\Type\LanguagesType;
use ForkCMS\Bundle\InstallerBundle\Form\Type\LoginType;
use ForkCMS\Bundle\InstallerBundle\Form\Type\ModulesType;
use ForkCMS\Bundle\InstallerBundle\Service\ForkInstaller;
use ForkCMS\Bundle\InstallerBundle\Service\RequirementsChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class InstallerController extends AbstractController
{
    /** @var InstallationData|null */
    public static $installationData;

    public function __construct(
        private readonly ForkInstaller $forkInstaller,
        private readonly RequirementsChecker $requirementsChecker,
    ) {
    }

    public function step1Action(): Response
    {
        $this->checkInstall();

        // if all our requirements are met, go to the next step
        if ($this->requirementsChecker->passes()) {
            return $this->redirect($this->generateUrl('install_step2'));
        }

        return $this->render(
            'Installer/step1.html.twig',
            [
                'checker' => $this->requirementsChecker,
                'rootDir' => realpath($this->getParameter('site.path_www')),
            ]
        );
    }

    public function step2Action(Request $request): Response
    {
        return $this->handleInstallationStep(2, LanguagesType::class, new LanguagesHandler(), $request);
    }

    public function step3Action(Request $request): Response
    {
        return $this->handleInstallationStep(3, ModulesType::class, new ModulesHandler(), $request);
    }

    public function step4Action(Request $request): Response
    {
        return $this->handleInstallationStep(4, DatabaseType::class, new DatabaseHandler(), $request);
    }

    public function step5Action(Request $request): Response
    {
        return $this->handleInstallationStep(5, LoginType::class, new LoginHandler(), $request);
    }

    public function step6Action(Request $request): Response
    {
        $this->checkInstall();

        $status = $this->forkInstaller->install($this->getInstallationData($request));

        return $this->render(
            'Installer/step6.html.twig',
            [
                'installStatus' => $status,
                'installer' => $this->forkInstaller,
                'data' => $this->getInstallationData($request),
            ]
        );
    }

    public function noStepAction(): RedirectResponse
    {
        $this->checkInstall();

        return $this->redirect($this->generateUrl('install_step1'));
    }

    protected function getInstallationData(Request $request): InstallationData
    {
        if (!$request->getSession()->has('installation_data')) {
            $request->getSession()->set('installation_data', new InstallationData());
        }
        // static cache
        self::$installationData = $request->getSession()->get('installation_data');

        return $request->getSession()->get('installation_data');
    }

    /**
     * @throws ExitException if fork is already installed
     */
    protected function checkInstall()
    {
        $filesystem = new Filesystem();
        $kernelDir = $this->getParameter('kernel.project_dir') . '/app';
        $parameterFile = $kernelDir . 'config/parameters.yml';
        if ($filesystem->exists($parameterFile)) {
            throw new ExitException(
                'This Fork has already been installed. To reinstall, delete
                 parameters.yml from the ' . $kernelDir . 'config/ directory.',
                'This Fork has already been installed. To reinstall, delete
                 parameters.yml from the ' . $kernelDir . 'config/ directory. To log in,
                 <a href="/private">click here</a>.',
                Response::HTTP_FORBIDDEN
            );
        }
    }

    private function handleInstallationStep(
        int $step,
        string $formTypeClass,
        InstallerHandler $handler,
        Request $request
    ): Response {
        $this->checkInstall();

        // check if can start the next step
        if ($this->requirementsChecker->hasErrors()) {
            return $this->redirect($this->generateUrl('install_step1'));
        }

        $form = $this->createForm($formTypeClass, $this->getInstallationData($request));
        if ($handler->process($form, $request)) {
            return $this->redirect($this->generateUrl('install_step' . ($step + 1)));
        }

        return $this->render(
            'Installer/step' . $step . '.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
