<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExemple\DemoBundle\Entity\Artiste;
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
 * @Route("/artistes")
 *@NamePrefix("byexample_artistes_")
 */

class ArtisteRestController extends Controller
{
	/**
     * @Method({"GET"})
     * @ApiDoc()
   */
  public function getArtisteAction($id){
  $view = FOSView::create();
		
    //$artiste = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Artiste')->find($id);
  $artiste = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Artiste')->find($id);
    if ($artiste) {
            $view->setStatusCode(200)->setData($artiste);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

/**
   * @return FOSView
     * @Method({"GET"})
     * @ApiDoc()
   */


  public function getArtistesAction(){
  $view = FOSView::create();
	$artistes = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Artiste')->findAll();
	if ($artistes) {
            $view->setStatusCode(200)->setData($artistes);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  
 }

/**
* @Route("/artistes/search/{keyword}")
* @return FOSView
     * @Method({"GET"})
     * @ApiDoc()
   */

  public function getArtistesSearchAction($keyword){
  $view = FOSView::create();
  
  $word="%".$keyword."%";
  $em = $this->getDoctrine()->getManager();
  $query = $em->createQuery(
      'SELECT p
      FROM ByExampleDemoBundle:Artiste p
      WHERE p.nom LIKE :nom'
  )->setParameter('nom', $word);
  $artistes = $query->getResult();
  if ($artistes) {
            $view->setStatusCode(200)->setData($artistes);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  
 }

}