<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExemple\DemoBundle\Entity\Item;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;

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


/**
 * 
 * @Route("/api/items")
 *@NamePrefix("byexample_items_")
 */

class ItemRestController extends Controller
{
	/**
     * @Method({"GET"})
     * @ApiDoc()
   */
  public function getItemAction($id){
  $view = FOSView::create();
	
    $item = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Item')->find($id);
	
    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

  /**
  * @Route("/items/search/{key}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemsSearchAction($key){
  $view = FOSView::create();
    $key = "%".$key."%";
  $em =$this->getDoctrine()->getManager();
  $query = $em->createQuery('SELECT i FROM ByExampleDemoBundle:Item i WHERE i.titre LIKE :key')->setParameter('key', $key);
$items = $query->getResult();

  /* $item = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Item')->findByTitre($key);
  */ 
    if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

/**
  * @Route("/items/popular/")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemsPopularAction(){

    $view = FOSView::create();
    $days = $this->container->getParameter('popular_parameter_days');
    $limit = $this->container->getParameter('popular_limit');

 $em =$this->getDoctrine()->getManager();
  $query = $em->createQuery(
    'SELECT COUNT(i.id) as views, i.titre 
    FROM ByExampleDemoBundle:Item i, ByExampleDemoBundle:Ecoute e
    WHERE e.iditem = i.id
    AND (e.date > :before)
    GROUP BY i.id
    ORDER BY views DESC'
    )->setParameter('before', new \DateTime('-'.$days.' days'))->setMaxResults($limit);
$items = $query->getResult();

  
    if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
}

/**
  * @Route("/items/{id}/tags/")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemTagsAction($id){

    $view = FOSView::create();

 $em =$this->getDoctrine()->getManager();
  $query = $em->createQuery(
    'SELECT t.libelle, nt.note
    FROM ByExampleDemoBundle:Tag t, ByExampleDemoBundle:NoteTagItem nt
    WHERE t.id = nt.idtag
    AND nt.iditem= :id'
    )->setParameter('id', $id);
$items = $query->getResult();

  
    if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
}

/**
  * @Route("/items/genre/{id}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemGenreAction($id){

    $view = FOSView::create();

 $em =$this->getDoctrine()->getManager();

 $max = $em->createQuery(
    'SELECT MAX(i.id) FROM ByExampleDemoBundle:Item i
            JOIN i.idgenre g
            WHERE g.id= :id
            ')
 ->setParameter('id', $id)
 ->getSingleScalarResult();


  $query = $em->createQuery(
    'SELECT i
    FROM ByExampleDemoBundle:Item i
    JOIN i.idgenre g
            WHERE g.id= :id
    AND i.id >= :rand
    ORDER BY i.id ASC'
    )
  ->setParameter('id', $id)
  ->setParameter('rand',rand(0,$max))
  ->setMaxResults(1) ;

  $item = $query->getSingleResult();

  
    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
}

/**
  * @Route("/items/artiste/{id}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemArtisteAction($id){

    $view = FOSView::create();

 $em =$this->getDoctrine()->getManager();

 $max = $em->createQuery(
    'SELECT MAX(i.id) FROM ByExampleDemoBundle:Item i
            JOIN i.idartiste a
            WHERE a.id= :id
            ')
 ->setParameter('id', $id)
 ->getSingleScalarResult();


  $query = $em->createQuery(
    'SELECT i
    FROM ByExampleDemoBundle:Item i
    JOIN i.idartiste a
            WHERE a.id= :id
    AND i.id >= :rand
    ORDER BY i.id ASC'
    )
  ->setParameter('id', $id)
  ->setParameter('rand',rand(0,$max))
  ->setMaxResults(1) ;

  $item = $query->getResult();

  
    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
}
 }