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
        $playlist = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Playlist')->find($id_playlist);
        if ($playlist) {
            $view->setStatusCode(200)->setData($playlist);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
    }
}