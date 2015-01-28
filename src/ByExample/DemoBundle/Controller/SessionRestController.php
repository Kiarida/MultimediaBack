<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExample\DemoBundle\Entity\Item;
use ByExample\DemoBundle\Entity\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;
use ByExample\DemoBundle\Repository\SessionRepository;

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
 	*@NamePrefix("byexample_items_")
 **/
class SessionRestController extends Controller{
    /**
     * Retourne la liste de tous les items écouté lors d'une session
     * @Get("users/{id}/sessions/{id_session}")
     * @ApiDoc()
     * @return FOSView
   */

    public function getItemsBySessionAction($id_session){
        $view = FOSView::create();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleDemoBundle:Session');
        $items=$repo->findItemsBySessionId($id_session);
        if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }
        return $view;
    }

     /**
    * Associe un tag a une playlist ou créé un nouveau tag
    * @Post("users/{id}/sessions/{id_session}/tags/{libelle}")
    * @ApiDoc()
    * @return FOSView
   */

  public function addSessionTagAction($id, $id_session, $tag){
    $view = FOSView::create();  
    
    $em = $this->getDoctrine()->getManager();
    $repo=$em->getRepository('ByExampleDemoBundle:Playlist');
    $tags=$repo->findTagByLibelle($libelle, $id);
    if ($tags) {
        //On regarde s'il y a déjà une association
        $tagplaylist=$repo->findPlaylistByTag($tags, $id_playlist);
        if(!$tagplaylist){
            //Sinon on la créé
            $tag=$repo->insertPlaylistTag($tags,$id_playlist);
            if($tag){
                $view->setStatusCode(200)->setData("Tag associé");
            } else {
                $view->setStatusCode(402);
            } 
        }        
        else{
            $view->setStatusCode(406);
        }
    }
    else{
        //Si le tag n'existe pas, on va le créer
        $newTag=$repo->insertTag($libelle, $id_playlist);
        if($newTag){
            $view->setStatusCode(200)->setData($newTag->getId());
        } else {
            $view->setStatusCode(408);
        }
    }
    return $view;
 }
}