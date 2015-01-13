<?php
/**
 * Created by PhpStorm.
 * User: mohammed
 * Date: 05/12/14
 * Time: 01:21 ص
 */
namespace Meisa\MenuBundle\Extension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;



class MenuTwigExtension extends \Twig_Extension
{
    protected $container;
    protected $em;

    public function __construct($em,$container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            'show_menu' => new \Twig_Filter_Method($this, 'show_menu'),
        );
    }

    public function show_menu($menu_slug)
    {
        $repo = $this->em->getRepository('MeisaMenuBundle:MenuItem');
        $menu = $this->em ->getRepository('MeisaMenuBundle:Menu')->findOneBy(array('slug' => $menu_slug));
        $query = $this->em
            ->createQueryBuilder()
            ->select('node')
            ->from('MeisaMenuBundle:MenuItem', 'node')
            ->where('node.menu =:menu')
            ->orderBy('node.position', 'ASC')
            ->setParameters(array('menu'=>$menu->getId()))->getQuery();

        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul class="'.$menu->getMenuClass().'">',
            'rootClose' => '</ul>',
            'childOpen' => function($node){return '<li class="'.$node['itemClass'].'" id="'.$node['itemId'].'">';},
            'childClose' => '</li>',
            'nodeDecorator' => function ($node) {
                return '<a href="' . $node['link'] . '">' . $node['title'] . '</a>';
            }
        );
        $menu = $repo->buildTree($query->getArrayResult(), $options);


        return $this->container->get('templating')
            ->render("MeisaMenuBundle:Front:menu_links.html.twig",array('menu'=>$menu));
    }

    public function getName()
    {
        return 'tabssoft_menu';
    }
} 