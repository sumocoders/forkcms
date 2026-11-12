<?php

namespace ForkCMS\Bundle\InstallerBundle\Service;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\DriverManager;
use ForkCMS\Bundle\InstallerBundle\Controller\InstallerController;
use ForkCMS\Bundle\InstallerBundle\Entity\InstallationData;
use Doctrine\DBAL\Exception\ConnectionException;

class InstallerConnectionFactory
{
    public function __construct(private readonly ConnectionFactory $connectionFactory)
    {
    }

    public function createConnection(
        array $params,
        Configuration|null $config = null,
        array $mappingTypes = [],
    ): Connection {
        try {
            $installationData = $this->getInstallationData();
            if ($installationData->getDatabaseHostname() === null) {
                return $this->getInstallerConnection($params, $config);
            }

            $params['host'] = $installationData->getDatabaseHostname();
            $params['port'] = $installationData->getDatabasePort();
            $params['dbname'] = $installationData->getDatabaseName();
            $params['user'] = $installationData->getDatabaseUsername();
            $params['password'] = $installationData->getDatabasePassword();

            //continue with regular connection creation using new params
            return $this->connectionFactory->createConnection($params, $config, $mappingTypes);
        } catch (ConnectionException | Exception) {
            return $this->getInstallerConnection($params, $config);
        }
    }

    private function getInstallationData(): InstallationData
    {
        if (InstallerController::$installationData instanceof InstallationData) {
            return InstallerController::$installationData;
        }

        return new InstallationData();
    }

    private function getInstallerConnection(
        #[\SensitiveParameter]
        array $params,
        ?Configuration $config = null,
    ): Connection {
        $normalConnection = DriverManager::getConnection($params, $config);

        return new Connection($params, $normalConnection->getDriver(), $config);
    }
}
