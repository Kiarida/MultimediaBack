<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExample\DemoBundle\Entity\Playlist;
use ByExample\DemoBundle\Entity\Tag;
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
 	*@NamePrefix("byexample_items_")
 **/
class PlaylistRestController extends Controller{
/**
     * @Get("users/{id}/playlists/{id_playlist}")
     * @ApiDoc()
     * @return FOSView
   */

    public function getPlaylistsAction($id, $id_playlist){
        $view = FOSView::create();

    $em = $this->getDoctrine()->getManager();
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
    $rsm->addScalarResult('idItem', 'idItem');
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('nom', 'nom');
    $rsm->addScalarResult('dateCreation', 'datecreation');
    $q = $em->createNativeQuery(
        'SELECT p.*, idItem FROM playlist p, itemplaylist WHERE p.id = ? AND p.idutilisateur = ?',
        $rsm
    )->setParameter(1, $id_playlist)->setParameter(2, $id);
    $playlists = $q->getResult();
    /*$em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM ByExampleDemoBundle:Playlist p
            JOIN p.idutilisateur u
            JOIN p.iditem i
            WHERE u.id = :id
            AND p.id = :idplaylist'
           
        )->setParameter('id', $id)->setParameter('idplaylist',$id_playlist);
        
        $playlists = $query->getResult();   

        */


        if ($playlists) {
            $view->setStatusCode(200)->setData($playlists);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
    }

    /**
     * @Get("users/{id}/playlists/{id_playlist}/tags")
     * @ApiDoc()
     * @return FOSView
   */
    public function getPlaylistTagsAction($id, $id_playlist){
        $view = FOSView::create();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
        'SELECT t.id, t.libelle FROM ByExampleDemoBundle:Tag t JOIN t.idplaylist g WHERE g.id= :id AND g.idutilisateur = :idutil')
        ->setParameter('id', $id_playlist)->setParameter("idutil", $id);
        $tags=$query->getResult();
        if ($tags) {
            $view->setStatusCode(200)->setData($tags);
        } else {
            $view->setStatusCode(404);
        }
        return $view;

    }

    /**
    * @Post("users/{id}/playlists/{id_playlist}/tags/{libelle}")
    * @ApiDoc()
    * @return FOSView
   */

  public function getPlaylistTagAction($id, $id_playlist, $libelle){
  $view = FOSView::create();
  
  //$word="%".$libelle."%";
  $em = $this->getDoctrine()->getManager();
  $query = $em->createQuery(
      'SELECT t.id
      FROM ByExampleDemoBundle:Tag t
      JOIN t.idplaylist p
      WHERE t.libelle LIKE :libelle 
      AND p.idutilisateur = :idutil'
  )->setParameter('libelle', $libelle)->setParameter('idutil',$id);
  $tags = $query->getResult();
    if ($tags) {
        $query=$em->createQuery('SELECT p.id FROM ByExampleDemoBundle:Playlist p JOIN p.idtag t WHERE p.id =:playlist AND t.id = :tag')->setParameter("playlist",$id_playlist)->setParameter("tag",$tags[0]["id"]);
        $tagplaylist=$query->getResult();
        if(!$tagplaylist){
            $conn = $em->getConnection();
            $tag = $conn->insert("tagplaylist", array("idTag"=>$tags[0]["id"], "idPlaylist"=>$id_playlist));
            if($tag){
                $view->setStatusCode(200)->setData("Tag associé");
            } else {
                $view->setStatusCode(404);
            } 
        }        
        else{
            $view->setStatusCode(406);
        }
        return $view;
    }
    else{
        $newTag = new Tag();
        $newTag->setLibelle($libelle);
        $em->persist($newTag);
        $em->flush();
        $idTag = $newTag->getId();
        $conn = $em->getConnection();
        $tag = $conn->insert("tagplaylist", array("idTag"=>$idTag, "idPlaylist"=>$id_playlist));
        if($newTag && $tag){
            $view->setStatusCode(200)->setData($newTag);
        } else {
            $view->setStatusCode(404);
        }
        return $view;
    }
    return $view;
 }


    /**
    * @Delete("users/{id}/playlists/{id_playlist}/tags/{idTag}")
    * @ApiDoc()
    * @return FOSView
   */

    public function deletePlaylistTagAction($id, $id_playlist, $idTag){
        $view = FOSView::create();

        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery('SELECT t.id
      FROM ByExampleDemoBundle:Tag t
      JOIN t.idplaylist p
      WHERE t.id LIKE :idtag 
      AND p.idutilisateur = :idutil')
        ->setParameter("idtag",$idTag)->setParameter("idutil",$id);
        $tags=$query->getResult();

        if($tags){
            $conn = $em->getConnection();
            $conn->delete("tagplaylist", array("idTag"=>$idTag));
            $view->setStatusCode(200);
        }
        return $view;
    }

     /**
    * @Delete("users/{id}/playlists/{id_playlist}")
    * @ApiDoc()
    * @return FOSView
   */

    public function deletePlaylistAction($id, $id_playlist){
        $view = FOSView::create();

        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery('SELECT p.id
      FROM ByExampleDemoBundle:Playlist p
      WHERE p.id LIKE :idplaylist 
      AND p.idutilisateur = :idutil')
        ->setParameter("idplaylist",$id_playlist)->setParameter("idutil",$id);
        $playlists=$query->getResult();
        if($playlists){
            $conn = $em->getConnection();
            $conn->delete("playlist", array("id"=>$id_playlist));
            $view->setStatusCode(200);
        }
        return $view;
    }

}