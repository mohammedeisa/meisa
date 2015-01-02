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
class MenuConfigParameterAdmin extends Admin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('parameterKey')
            ->add('keyGetterFunction')
            ->add('linkDisplayName')
            ->add('entityClassName');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('parameterKey')
            ->add('keyGetterFunction')
            ->add('linkDisplayName')
            ->add('entityClassName');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('parameterKey')
            ->add('keyGetterFunction')
            ->add('linkDisplayName')
            ->add('entityClassName');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $em =  $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $tables = $em->getConnection()->getSchemaManager()->listTables();

        $meta = $em->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            $entities[$m->getName()] = $m->getName();
        }
        $formMapper
            ->add('parameterKey')
            ->add('keyGetterFunction')
            ->add('linkDisplayName')
            ->add('entityClassName','choice',array('choices'=>$entities));
    }

}