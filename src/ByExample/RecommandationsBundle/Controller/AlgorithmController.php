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
 	*@NamePrefix("byexample_algorithm_")
 **/
class AlgorithmController extends Controller{
   

    /**
    * Permet de tester un algorithme
    * @Get("algorithms/{algorithm}")
    * @ApiDoc()
    * @return FOSView
   */

  public function getRecommandationAction($algorithm){
    $view = FOSView::create();  
        //$params = array("track" => $key, "api_key" => $api_key_last, "format" => "json", "limit" => "30");
      
       $url="http://localhost:8080/Papaye/webresources/algorithms/".$algorithm;

       //$url .= '&' . http_build_query($params);
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('ByExampleDemoBundle:Item');

       $ch = curl_init();
       curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
       curl_setopt($ch, CURLOPT_URL, $url );
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $info=curl_exec($ch);
       $infodecode = json_decode($info, true);
       $results = array();
       foreach ($infodecode as $key) {
            $item = $repo->findFormatItems($key);
            array_push($results, $item);
       }

        if($results){ //s'il y a une action sur l'item
            $view->setStatusCode(200)->setData($results);
            
        }else{ 
            $view->setStatusCode(404);
        }

        return $view;

 
}
/**
    * Permet de lister les algorithmes
    * @Get("algorithms")
    * @ApiDoc()
    * @return FOSView
   */

  public function getAlgorithmsAction(){
        $view = FOSView::create();    
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Algorithm');
        $results=$repo->listAlgorithmsWithLimits();
        if($results){ //s'il y a une action sur l'item
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;

 
}

/**
    * Permet de lister les associations Algorithme pour un utilisateur
    * @Get("users/{iduser}/algorithms")
    * @ApiDoc()
    * @return FOSView
   */

  public function getAlgorithmsUserAction($iduser){
        $view = FOSView::create();    
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Algorithmuser');
        $results=$repo->findByIdutilisateur($iduser);
        if($results){ 
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;

 
}

/**
    * Créer une association entre des utilisateurs et des algorithmes
    * @Post("algorithms")
    * @ApiDoc()
    * @return FOSView
   */

  public function postAlgorithmsUserAction($iduser){
        $view = FOSView::create();    
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Algorithmuser');
        $results=$repo->findByIdutilisateur($iduser);
        if($results){ 
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;

 
}




}