<?php

namespace yuxblank\phackp\database\driver;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use yuxblank\phackp\database\api\EntitiyManagerDriver;

class DoctrineDriver implements EntitiyManagerDriver
{
    const CONTAINER_MANAGED = 'container';
    const BEAN              = 'bean';

    /** @var \Doctrine\ORM\Configuration */
    private $doctrineConfiguration;
    /** @var EntityManager */
    private $entityManager;

    /**
     * DoctrineDriver constructor.
     * Inject database configuration with the Container
     * @param array $databaseConfig
     * @throws \Doctrine\ORM\ORMException
     * @throws \InvalidArgumentException
     */
    public function __construct(array $databaseConfig)
    {
        $this->doctrineConfiguration = Setup::createAnnotationMetadataConfiguration(
            $databaseConfig['entities_paths'],
            $databaseConfig['is_dev'],
            $databaseConfig['proxy_dir'],
            $databaseConfig['cache'],
            $databaseConfig['simple_annotations']
        );

        $this->entityManager = EntityManager::create($databaseConfig['connection'], $this->doctrineConfiguration);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getDriver(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}