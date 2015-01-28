<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExample\DemoBundle\Entity\Item;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;
use ByExample\DemoBundle\Repository\ItemRepository;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\Rest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;                  // @ApiDoc(resource=true, description="Filter",filters={{"name"="a-filter", "dataType"="string", "pattern"="(foo|bar) ASC|DESC"}})
use FOS\RestBundle\Controller\Annotations\NamePrefix;       // NamePrefix Route annotation class @NamePrefix("bdk_core_user_userrest_")
use FOS\RestBundle\View\RouteRedirectView;                  // Route based redirect implementation
use Symfony\Component\Validator\ConstraintViolation;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ODM\PHPCR\Query\QueryException;
use Doctrine\ORM\Query;
use Doctrine\Common\Util\Debug;
/**
 * 
 * @Route("/api/items")
 *@NamePrefix("byexample_items_")
 */

class ItemRestController extends Controller
{
	/**
  * Renvoie le détail d'un item
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemAction($id){
  $view = FOSView::create();

    $item = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Item')->find($id);
/*  $itemT = new Item();
  $itemT->setId($item->getId());*/

    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

  /**
  * Recherche des items dans la base en fonction du mot clé donné en paramètre
  * @Route("/items/search/{key}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemsSearchAction($key){
      $view = FOSView::create();
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('ByExampleDemoBundle:Item');
      $items = $repo->findItemsBySearchKey($key);

        if ($items) {
                $view->setStatusCode(200)->setData($items);
            } else {
                $view->setStatusCode(404);
            }

      return $view;
  }

/**
  * Renvoie une liste des x musiques les plus écoutées depuis y jours 
  * @Route("/items/popular/")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemsPopularAction(){

    $view = FOSView::create();
    $days = $this->container->getParameter('popular_parameter_days');
    $limit = $this->container->getParameter('popular_limit');

    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $items = $repo->findItemsByPopularity($days, $limit);
  
    if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }

    return $view;
}

/**
  * Retourne tous les tags liés a l'item en paramètre
  * @Route("/items/{id}/tags/")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemTagsAction($id){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $items = $repo-> findTagsByItem($id);

    if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
}

/**
  * Retourne un item aléatoire du genre en paramètre
  * @Route("/items/genre/{idGenre}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemGenreAction($idGenre){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $item = $repo-> findRandomItemByGenre($idGenre);

    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

    return $view;
}

/**
  * Retourne un item aléatoire de l'artiste en paramètre
  * @Route("/items/artiste/{idArtiste}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemArtisteAction($idArtiste){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $item = $repo-> findRandomItemByArtiste($idArtiste);

    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }
 }