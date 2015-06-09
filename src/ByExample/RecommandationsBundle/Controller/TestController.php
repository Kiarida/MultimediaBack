<?php

namespace ByExample\RecommandationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExample\DemoBundle\Entity\Item;
use ByExample\DemoBundle\Entity\Actions;
use ByExample\RecommandationsBundle\Entity\Algorithm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
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
use Doctrine\ORM\Query;


/**
  *@NamePrefix("byexample_test_")
 **/
class TestController extends Controller{
   

    
/**
    * Permet de lister les tests
    * @Get("tests")
    * @ApiDoc()
    * @return FOSView
   */

  public function getTestsAction(){
        $view = FOSView::create();    
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Test');
        $results=$repo->findAll();
        if($results){ //s'il y a une action sur l'item
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;

 
}

/**
    * Permet de lister les associations Algorithme pour un utilisateur
    * @Get("tests/count/")
    * @ApiDoc()
    * @return FOSView
   */

  public function getTestAction(){
        $view = FOSView::create();    
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Test');
        $results=$repo->createGroup(3);
        if($results){ 
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;
}

/**
    * Permet de récupérer le test courant
    * @Get("tests/current/")
    * @ApiDoc()
    * @return FOSView
   */

  public function getCurrentTestAction(){
        $view = FOSView::create();    
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Test');
        $results=$repo->currentTest();
        if($results){ 
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;
}

/**
    * Créer une association entre des utilisateurs et des algorithmes
    * @Post("tests")
    * @ApiDoc()
    * @return FOSView
   */

  public function postTestAction(){
        $view = FOSView::create();    
        $label = $this->get('request')->request->get('label');
        //$idalgo = $this->get('request')->request->get('idAlgo');
        $mode = $this->get('request')->request->get('mode');
        $groups = $this->get('request')->request->get('groups');
        $idalgo=[3, 4];
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Test');
        $arraygroup=$repo->createGroup($groups);
        $results = $repo->createTest($idalgo, $label, $mode, $groups, $arraygroup);
        if($results){ 
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;

 
}






}