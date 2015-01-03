<?php

namespace Meisa\MenuBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class MenuRepository extends NestedTreeRepository{
}
