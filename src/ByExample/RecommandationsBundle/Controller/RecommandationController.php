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
  *@NamePrefix("byexample_recommandation_")
 **/
class RecommandationController extends Controller{
   

    /**
    * Récupère une recommandation
    * @Get("users/{iduser}/recommandations")
    * @ApiDoc()
    * @return FOSView
   */

  public function getRecommandationAction($iduser){
    $view = FOSView::create();  
        //$params = array("track" => $key, "api_key" => $api_key_last, "format" => "json", "limit" => "30");
      
       $results = array();
       //$url .= '&' . http_build_query($params);
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('ByExampleDemoBundle:Item');
      $repoAlgo = $em->getRepository('ByExampleRecommandationsBundle:Algorithm');
      $repoReco = $em->getRepository('ByExampleRecommandationsBundle:Recommandation');
      $algorithm=$this->get('request')->query->get('algorithm');
      $idAlgo=$repoAlgo->findOneByNom($algorithm);
      //Si l'algorithme a l'attribut precalculated, on va chercher les recommandations des utilisateurs dans la BDD
      if($idAlgo->getPrecalculated() == true){
        $results=$repoRec->findByIdalgorithm($idAlgo->getId());
      }
      //Sinon, on le fait en online : on récupère directement la recommandation et on l'insère pour la stocker
      else{
        $url="http://localhost:8080/Papaye/webresources/algorithms/".$algorithm."/".$iduser;

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $info=curl_exec($ch);
        $infodecode = json_decode($info, true);
        
        foreach ($infodecode as $key) {
          $searchReco = $repoReco->searchRecommandation($idAlgo, $iduser, $key);
          if(!$searchReco){
             $newReco = $repoReco->addRecommandation($idAlgo, $iduser, $key);
          }
          $item = $repo->findFormatItems($key);
          array_push($results, $item);
        }
        
      }
       

        if($results){ //s'il y a une action sur l'item
            $view->setStatusCode(200)->setData($results);
            
        }else{ 
            $view->setStatusCode(404);
        }

        return $view;

 
}
/**
    * Permet de lister les recommandations
    * @Get("recommandations")
    * @ApiDoc()
    * @return FOSView
   */

  public function getRecommandationsAction(){
        $view = FOSView::create();    
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleRecommandationsBundle:Recommandation');
        $results=$repo->findAll();
        if($results){
          $view->setStatusCode(200)->setData($results);      
        }else{ 
          $view->setStatusCode(404);
        }
        return $view;

 
}






}