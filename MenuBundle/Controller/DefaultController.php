<?php

namespace Meisa\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
    /**
     * @Route("admin/page_tree_up/{id}",name = "page_tree_up")
     * @Template()
     */
    public function moveupAction($id)
    {
        {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('MeisaMenuBundle:Menu');
            $page = $repo->find($id);
            if ($page->getParent()){
                $repo->moveUp($page);
            }else{
                // get Max position
                $qb = $repo->createQueryBuilder('p');
                $qb->addSelect('MIN(p.position)as position')->orderBy('p.position', 'DESC');
                $result = $qb->getQuery()->getResult();
                $result = $result[0];
                $min_position = $result['position'];
               if($page->getPosition()!=$min_position){

                   $root_position = $page->getPosition();
                   $oldposition = $repo->findByPosition($root_position);

                   $up_position = $root_position-1;
                   $next_tree =  $repo->findByPosition($up_position);
                   foreach($oldposition as $node){
                       $node->setPosition($up_position);
                       $em->persist($node);

                   }

                   foreach($next_tree as $node){
                       $node->setPosition($root_position);
                       $em->persist($node);

                   }
                   $em->flush();

               }

            }

            return $this->redirect($this->getRequest()->headers->get('referer'));
        } new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
    /**
     * @Route("admin/page_tree_down/{id}",name = "page_tree_down")
     * @Template()
     */

    public function movedownAction($id)
    {
        {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('MeisaMenuBundle:Menu');
            $page = $repo->find($id);
            if ($page->getParent()){
                $repo->moveDown($page);
            }else{
                // get Max position
                $qb = $repo->createQueryBuilder('p');
                $qb->addSelect('MAX(p.position)as position')->orderBy('p.position', 'DESC');
                $result = $qb->getQuery()->getResult();
                $result = $result[0];
                $max_position = $result['position'];
                if($page->getPosition()!=$max_position){

                    $root_position = $page->getPosition();
                    $oldposition = $repo->findByPosition($root_position);

                    $down_position = $root_position+1;
                    $next_tree =  $repo->findByPosition($down_position);
                    foreach($oldposition as $node){
                        $node->setPosition($down_position);
                        $em->persist($node);

                    }

                    foreach($next_tree as $node){
                        $node->setPosition($root_position);
                        $em->persist($node);

                    }
                    $em->flush();

                }

            }
            return $this->redirect($this->getRequest()->headers->get('referer'));
        } new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }


    /**
     * @Route("admin/menu",name = "menu")
     * @Template()
     */
    public function menuAction(){


        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('MeisaMenuBundle:Menu');
        $query =  $repo
            ->createQueryBuilder('node')
            ->select('node')
            ->from('MeisaMenuBundle:Menu', 'p')
            ->orderBy('node.position , node.root, node.lft', 'ASC')
            ->where('node.id = p.id')
            ->getQuery()
        ;
        $options = array('decorate' => true,'rootOpen' => '<ul>',
    'rootClose' => '</ul>',
    'childOpen' => '<li>',
    'childClose' => '</li>');
        $tree = $repo->buildTree($query->getArrayResult(), $options);


        return $this->render(
            'MeisaMenuBundle:Default:menu.html.twig',
            array('trees' => $tree)
        );



    }

}
