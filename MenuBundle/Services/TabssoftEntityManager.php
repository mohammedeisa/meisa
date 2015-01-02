<?php
namespace Meisa\MenuBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

/**
 * Created by PhpStorm.
 * User: mohammed
 * Date: 05/12/14
 * Time: 07:55 ص
 */
class MeisaEntityManager
{
    private $em;

    public function __construct(Doctrine $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    public function getEntityManager()
    {
        return $this->em;
    }
} 