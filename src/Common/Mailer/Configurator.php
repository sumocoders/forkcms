<?php

namespace Common\Mailer;

use PDOException;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Common\ModulesSettings;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class Configurator
{
    public function __construct(private ModulesSettings $modulesSettings, private ContainerInterface $container)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $this->configureMail();
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->configureMail();
    }

    private function configureMail(): void
    {
        try {
            $transport = TransportFactory::create(
                (string) $this->modulesSettings->get('Core', 'mailer_type', 'sendmail'),
                $this->modulesSettings->get('Core', 'smtp_server'),
                (int) $this->modulesSettings->get('Core', 'smtp_port', 25),
                $this->modulesSettings->get('Core', 'smtp_username'),
                $this->modulesSettings->get('Core', 'smtp_password'),
                $this->modulesSettings->get('Core', 'smtp_secure_layer')
            );
            $mailer = $this->container->get('mailer');
            if ($mailer !== null) {
                $this->container->get('mailer')->__construct($transport);
            }
            $this->container->set(
                'swiftmailer.transport',
                $transport
            );
        } catch (PDOException) {
            // we'll just use the mail transport thats pre-configured
        }
    }
}
