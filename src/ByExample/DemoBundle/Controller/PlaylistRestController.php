<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExemple\DemoBundle\Entity\Playlist;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;

use FOS\RestBundle\Controller\Annotations\Get;
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

        //$playlists=$query->getResult();
        //$playlists=array();
        //$truc = array();
        /*foreach ($results as $key => $value) {  

            $playlists[]= $value->getIditem()->toArray();

        }
               

        */


        if ($playlists) {
            $view->setStatusCode(200)->setData($playlists);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
    }



}