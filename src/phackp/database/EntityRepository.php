<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 16/09/2017
 * Time: 16:41
 */

namespace yuxblank\phackp\database;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class EntityRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * EntityRepository constructor.
     * @param EntityManager $entityManager
     * @param string $entityName
     */
    public function __construct(EntityManager $entityManager, string $entityName)
    {
        parent::__construct($entityManager, new ClassMetadata($entityName));
    }


}