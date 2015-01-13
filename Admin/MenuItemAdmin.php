<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meisa\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class MenuItemAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */

    protected function configureFormFields(FormMapper $formMapper)
    {

        $repository_ = $this->getModelManager()->getEntityManager($this->getClass())->getRepository($this->getClass());
        $repository = $repository_->createQueryBuilder('p')
            ->addOrderBy('p.position', 'ASC')
            ->addOrderBy('p.root', 'ASC')
            ->addOrderBy('p.lft', 'ASC');
        $formMapper
            ->add('title')
            ->add('itemClass')
            ->add('itemId')
            ->add('link', 'meisa_link', array())
            ->add('parent')
            ->add('position');
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $repository = $this->getModelManager()->getEntityManager($this->getClass())->getRepository($this->getClass());
        $repository = $repository->createQueryBuilder('p')
            ->addOrderBy('p.lft', 'ASC');

        $datagridMapper
            ->add('parent', 'doctrine_orm_callback', array(
                    'callback' => array($this, 'getAllChildCategories'),
                    'field_type' => 'checkbox'
                )
                ,
                'choice',
                array('choices' => $this->getAllCategories())
            )
            ->add('title');
    }

    public function getAllCategories()
    {

        $repository = $this->getModelManager()->getEntityManager("Meisa\MenuBundle\Entity\Menu")
            ->getRepository("Meisa\MenuBundle\Entity\Menu");
        $qb = $repository->createQueryBuilder('c');

        $results = $qb->getQuery()->getResult();
        $choices = array();
        foreach ($results as $result) {
            $choices[$result->getId()] = $result->__toString();
        }
        return $choices;

    }

    public function getAllChildCategories($queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            $queryBuilder->addOrderBy($alias . '.position', 'ASC');
            $queryBuilder->addOrderBy($alias . '.root', 'ASC');
            $queryBuilder->addOrderBy($alias . '.lft', 'ASC');
            return true;
        }

        $selectedCat = $value['value'];
        $repository = $this->getModelManager()->getEntityManager("Meisa\MenuBundle\Entity\Menu")
            ->getRepository("Meisa\MenuBundle\Entity\Menu");
        $qb = $repository->createQueryBuilder('c');
        $qb->where('c.parent=:parent');
        $qb->setParameter('parent', $selectedCat);
        $categories = $qb->getQuery()->getResult();
        $childIds = array();

        foreach ($categories as $cat) {
            array_push($childIds, $cat->getId());
        }

        $parentAndChildIds = $childIds;
        array_push($parentAndChildIds, $selectedCat);

        $queryBuilder->andWhere($alias . '.id IN(:cat)');
        $queryBuilder->setParameter('cat', $parentAndChildIds);
        $queryBuilder->addOrderBy($alias . '.id', 'ASC');

        return true;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('link')
            ->add('enabled')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('titles_hierarchy', '', array('template' => 'MeisaMenuBundle:Admin:titles_hierarchy_field.html.twig'))
            ->add('enabled')
//            ->add('menu')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                    'Sort' => array('template' => 'MeisaMenuBundle:Admin:sort.html.twig'),
                )
            ));
    }


    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'MeisaMenuBundle:Admin:menu_item/base_edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        // this is the queryproxy, you can call anything you could call on the doctrine orm QueryBuilder
        $query
            ->addOrderBy($query->getRootAlias() . '.lft', 'ASC');
        return $query;
    }

    public function getFormTheme()
    {
        return array('MeisaMenuBundle:Form:meisa_link_field.html.twig');
    }
}