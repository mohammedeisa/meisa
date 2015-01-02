<?php

namespace Meisa\MenuBundle\Controller;

use Buzz\Message\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{

    /**
     * @Route("/menu" , name ="menu")
     * @Template("MeisaMenuBundle:Admin:menu_links.html.twig")
     */
    public function menuLinksAction()
    {
        $objectId = $this->container->get('request')->get('object_id');

        $links = $this->getWebsiteLinks();
        return array('links' => $links, 'object_id' => $objectId);
    }

    /**
     * @Route("/setCurrentMenu" , name ="setCurrentMenu", options={"expose"=true})
     */
    public function setCurrentMenuAction()
    {
        $id = $this->container->get('request')->get('id');
        $currentUrl = $this->container->get('request')->get('currentUrl');
        if ($id)
            $this->container->get('request')->getSession()->set('menu_id', $id);
        if ($currentUrl)
            $this->container->get('request')->getSession()->set('current_url', $currentUrl);
        $response = new Response();
        $response->setContent(json_encode(array(
            'ok' => true,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/breadcrumbs" , name ="breadcrumbs" )
     * @Template("MeisaMenuBundle:Front:breadcrumbs.html.twig")
     */
    public function breadcrumbsAction()
    {
        $container = $this->container;
        $request = $container->get('request');
        $current_link = $request->get('current_link');

        $id = $request->getSession()->get('menu_id');

        $currentMenu = null;
        $em = $this->getDoctrine()->getManager();
        $query = null;

        if ($id) {
            $query = $em->createQuery('SELECT a FROM MeisaMenuBundle:Menu a where a.id=:id order by a.updatedAt DESC')
                ->setParameter('id', $id);
        } elseif ($current_link) {
            $query = $em->createQuery('SELECT a FROM MeisaMenuBundle:Menu a where a.link=:link order by a.updatedAt DESC')
                ->setParameter('link', $current_link);
        }
        $query = $query->setMaxResults(1)
            ->getResult();
        if (count($query) > 0)
            $currentMenu = $query[0];

        $menuItems = array();

        if ($currentMenu) {
            while (true) {
                $menuItems[] = $currentMenu;
                if ($currentMenu->getParent()) {
                    $currentMenu = $currentMenu->getParent();
                } else {
                    break;
                }
            }
        } else {

            $PreRoute = $request->getSchemeAndHttpHost() . $request->getBaseUrl();
            $route = $this->get('router')->match(str_replace($PreRoute, '', $current_link));

            if ($route['_route'] == 'product_category') {
                $slug = $route['slug'];
                $route = $this->get('router')->generate($route['_route'], array('slug' => $slug));
                $url = $request->getSchemeAndHttpHost() . $request->getBaseUrl() . $route;
            } elseif ('careers') {

            }
        }

        return array('breadcrumb_items' => array_reverse($menuItems));
    }

    public function getCategoryParentsUrls($item_slug, $prefix_url)
    {
        $urls = array();
        $urls[] = array('url' => $prefix_url . '/products', 'title' => 'Products');
    }

    /**
     * @Route("/available_website_links" , name ="available_website_links")
     * @Template("MeisaMenuBundle:Front:available_menu_links.html.twig")
     */
    public function availableWebsiteLinksAction()
    {
        $links = $this->getWebsiteLinks();
        return array('links' => $links);
    }

    public function getWebsiteLinks()
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT mc FROM MeisaMenuBundle:MenuConfig mc');
        $menuConfigs = $query->getResult();
        $routes = $menuConfigs;
        $links = array('list_routes' => array(), 'show_routes' => array(), 'custom_routes' => array());
        foreach ($routes as $routeCategory => $routeValue) {
            $tempRoute = array();
            if ($routeValue->getType() == 'list') {
                $links['list_routes'][] = array('display_name' => $routeValue->getDisplayName(), 'url' => $this->generateUrl($routeValue->getRouteName(), array(), true));
            }
            $tempRoute = array();
            if ($routeValue->getType() == 'show') {
//                foreach ($routeValue as $routeKey => $showRoute) {
                $tempRoute['display_name'] = $routeValue->getDisplayName();
                $tempRoute['routes'] = array();
                $params = array();
                foreach ($routeValue->getParameters() as $param) {
                    $params[$param->getParameterKey()] = $em->createQuery('SELECT a.' . $param->getLinkDisplayName() . ',a.' . $param->getParameterKey() . ' FROM ' . $param->getEntityClassName() . ' a')->getResult();
                    $params[$param->getParameterKey()]['link_display_name'] = $param->getLinkDisplayName();
                }

                foreach ($params as $paramsKey => $paramValues) {
                    foreach ($paramValues as $paramKey => $paramValue) {
                        if (is_array($paramValue)) {

                            $url = $this->generateUrl($routeValue->getRouteName(), array($paramsKey => $paramValue[$paramsKey]), true);
                            $tempRoute['routes'][] = array('link_display' => $paramValue[$params[$paramsKey]['link_display_name']], 'url' => $url);
                        }
                    }
                }
//                }
                $links['show_routes'][] = $tempRoute;
            }
        }
        return $links;
    }

    /**
     * @Route("/test_menu" , name ="test_menu")
     * @Template("MeisaMenuBundle:Front:test_header_menu.html.twig")
     */
    public function testMenuAction()
    {
        $objectId = $this->container->get('request')->get('object_id');

        $links = $this->getWebsiteLinks();
        return array('links' => $links, 'object_id' => $objectId);
    }
}
