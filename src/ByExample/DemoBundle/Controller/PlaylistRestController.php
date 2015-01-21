<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExample\DemoBundle\Entity\Playlist;
use ByExample\DemoBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;
use ByExample\DemoBundle\Repository\PlaylistRepository;

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
class PlaylistRestController extends Controller{
    /**
     * Retourne tous les détails d'une playlist
     * @Get("users/{id}/playlists/{id_playlist}")
     * @ApiDoc()
     * @return FOSView
   */

    public function getPlaylistsAction($id, $id_playlist){
        $view = FOSView::create();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleDemoBundle:Playlist');
        $playlists=$repo->findPlaylistById($id, $id_playlist);
        if ($playlists) {
            $view->setStatusCode(200)->setData($playlists);
        } else {
            $view->setStatusCode(404);
        }
        return $view;
    }

    /**
    * Retourne la liste des tags liés à une playlist
     * @Get("users/{id}/playlists/{id_playlist}/tags")
     * @ApiDoc()
     * @return FOSView
   */
    public function getPlaylistTagsAction($id, $id_playlist){
        $view = FOSView::create();
        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('ByExampleDemoBundle:Playlist');
        $tags=$repo->findTagByIdPlay($id, $id_playlist);
        if ($tags) {
            $view->setStatusCode(200)->setData($tags);
        } else {
            $view->setStatusCode(404);
        }
        return $view;

    }

    /**
    * Associe un tag a une playlist ou créé un nouveau tag
    * @Post("users/{id}/playlists/{id_playlist}/tags/{libelle}")
    * @ApiDoc()
    * @return FOSView
   */

  public function getPlaylistTagAction($id, $id_playlist, $libelle){
    $view = FOSView::create();  
      //$word="%".$libelle."%";
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


    /**
    * Supprime une association entre un tag et une playlist
    * @Delete("users/{id}/playlists/{id_playlist}/tags/{idTag}")
    * @ApiDoc()
    * @return FOSView
   */

    public function deletePlaylistTagAction($id, $id_playlist, $idTag){
        $view = FOSView::create();

        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('ByExampleDemoBundle:Playlist');
        $tags=$repo->findTagById($idTag, $id);
        if($tags){
            $conn = $em->getConnection();
            $conn->delete("tagplaylist", array("idTag"=>$idTag));
            $view->setStatusCode(200);
        }
        return $view;
    }

     /**
     * Supprime une playlist
    * @Delete("users/{id}/playlists/{id_playlist}")
    * @ApiDoc()
    * @return FOSView
   */

    public function deletePlaylistAction($id, $id_playlist){
        $view = FOSView::create();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleDemoBundle:Playlist');
        $playlists=$repo->findPlaylistById($id, $id_playlist);
        if($playlists){
            $conn = $em->getConnection();
            $conn->delete("playlist", array("id"=>$id_playlist));
            $view->setStatusCode(200);
        }
        return $view;
    }

}