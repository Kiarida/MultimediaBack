<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExample\DemoBundle\Entity\Item;
use ByExample\DemoBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;
use ByExample\DemoBundle\Repository\ItemRepository;
use ByExample\DemoBundle\Repository\MusiqueRepository;
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
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ODM\PHPCR\Query\QueryException;
use Doctrine\ORM\Query;
use Doctrine\Common\Util\Debug;
/**
 *
 * @Route("/api/items")
 *@NamePrefix("byexample_items_")
 */

class ItemRestController extends Controller
{
	/**
  * Renvoie le détail d'un item
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemAction($id){
  $view = FOSView::create();

    $item = $this->getDoctrine()->getRepository('ByExampleDemoBundle:Item')->find($id);
/*  $itemT = new Item();
  $itemT->setId($item->getId());*/

    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

  /**
  * Recherche des items dans la base en fonction du mot clé donné en paramètre
  * @Route("/items/search/{key}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemsSearchAction($key){
      $view = FOSView::create();
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('ByExampleDemoBundle:Item');
      $items = $repo->findItemsBySearchKey($key);

        if ($items) {
                $view->setStatusCode(200)->setData($items);
            } else {
                $view->setStatusCode(404);
            }

      return $view;
  }

/**
  * Renvoie une liste des x musiques les plus écoutées depuis y jours
  * @Route("/items/get/popular")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemsPopularAction(){

    $view = FOSView::create();
    $days = $this->container->getParameter('popular_parameter_days');
    $limit = $this->container->getParameter('popular_limit');


    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $items = $repo->findItemsByPopularity($days, $limit);


    if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }

    return $view;
}

/**
  * Retourne tous les tags liés a l'item en paramètre
  * @Route("/items/{id}/tags")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemTagsAction($id){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $items = $repo-> findTagsByItem($id);

    if ($items) {
            $view->setStatusCode(200)->setData($items);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
}

/**
  * Retourne un item aléatoire du genre en paramètre
  * @Route("/items/genre/{idGenre}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemGenreAction($idGenre){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $item = $repo-> findRandomItemByGenre($idGenre);

    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

    return $view;
}

/**
  * Retourne un item aléatoire de l'artiste en paramètre
  * @Route("/items/artiste/{idArtiste}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemArtisteAction($idArtiste){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $item = $repo-> findRandomItemByArtiste($idArtiste);

    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

  /**
  * Retourne un item depuis Spotify
  * @Route("/items/spotify/{idTrack}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemSpotifyAction($idTrack){
     $view = FOSView::create();
     $em =$this->getDoctrine()->getManager();
     $repo = $em->getRepository('ByExampleDemoBundle:Item');

     $items=$repo->findAllGenre();
     $infos=[];
     foreach($items as $item){
       $titre=$item[0]["titre"];
       $artist=$item[0]["idartiste"][0]["nom"];

       $params = array("artist" => $artist, "title" => $titre, "bucket" => "song_hotttnesss");
       $param2=array("bucket" =>"audio_summary");
       $url="http://developer.echonest.com/api/v4/song/search?api_key=1N7LROIETL6PEVJAF&format=json&results=1";

       $url .= '&' . http_build_query($params);
       $url .= '&' . http_build_query($param2);

       $ch = curl_init();
       curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
       curl_setopt($ch, CURLOPT_URL, $url );
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $info=curl_exec($ch);
       $infodecode = json_decode($info, true);
       $repoMusique = $em->getRepository('ByExampleDemoBundle:Musique');
       if(!empty($infodecode["response"]["songs"])){
         $repoMusique->putMusicItem($item[0]["id"],$infodecode["response"]);
       }
       $infos[$item[0]["id"]] = $infodecode;
     }
     if ($infos) {
            $view->setStatusCode(200)->setData($infos);
        } else {
            $view->setStatusCode(407);
        }

        return $view;
  }

  /**
  * Retourne un item depuis Spotify
  * @Route("/items/getgenres/")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getGenresItemAction(){
     $view = FOSView::create();
     $em =$this->getDoctrine()->getManager();
     $repo = $em->getRepository('ByExampleDemoBundle:Item');
     $repoGenre = $em->getRepository('ByExampleDemoBundle:Genre');
     $repoArtists = $em->getRepository('ByExampleDemoBundle:Artiste');
     $artists=$repoArtists->findAll();
     $infos=[];
     foreach($artists as $artist){
       $artistName=$artist->getNom();
       $params = array("name" => $artistName);
       $url="http://developer.echonest.com/api/v4/artist/terms?api_key=1N7LROIETL6PEVJAF&format=json";

       $url .= '&' . http_build_query($params);

       $ch = curl_init();
       curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
       curl_setopt($ch, CURLOPT_URL, $url );
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $info=curl_exec($ch);
       $infodecode = json_decode($info, true);


       $items=$repo->findItemByArtist($artist->getId());
       foreach($items as $item){
         $new = $repoGenre->addGenre($item, $infodecode["response"]);
         array_push($infos, $new);
       }

     }

     if ($artists) {
            $view->setStatusCode(200)->setData($infos);
        } else {
            $view->setStatusCode(407);
        }

        return $view;
  }

  /**
  * Recherche un item sur Soundcloud
  * @Route("/items/souncloud/")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function searchItemSoundcloudAction(){
    if($this->get('request')->getMethod() == "GET"){

        $artist = $this->getRequest()->query->get('artist');
        $title = $this->getRequest()->query->get('title');
        //$duration = $this->get('request')->request->get('duration');
        $em =$this->getDoctrine()->getManager();
    $view = FOSView::create();
    $bad=["Remix","Cover", "mix", "Rework", "Edit", "Mashup", "COVER", "Bootleg", "RMX", "Parody"];
    //$bad=["COVER"];
    $good=[];
    $params = array("q" => $artist." - ".$title, "limit" => 50);
    $url="https://api.soundcloud.com/tracks.json?consumer_key=d00a97f4dd31e93d67b63f4034aedc44";

    $url .= '&' . http_build_query($params);
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
    curl_setopt($ch, CURLOPT_URL, $url );
    //curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $item=curl_exec($ch);
    $items=json_decode($item, true);

    foreach($items as $itm){
      $cpt=0;
      foreach($bad as $word){
        if(strpos($itm["title"], $word) ==true){
          //$good="SALYT";
          break;
        }
        else{
          $cpt++;
        }
        if($cpt == 10){
          array_push($good, $itm);
        }
      }
    }

    if ($item) {
           $view->setStatusCode(200)->setData($good);
       } else {
           $view->setStatusCode(404);
       }

       return $view;

  }
}


  /**
  * Retourne les pistes d'un artiste donné
  * @Route("/items/albums/{idArtiste}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getAlbumByArtisteAction($idArtiste){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $albums = $repo->findAlbumByArtist($idArtiste);
    $biography = $albums;
    $i = 0;
    foreach ($albums as  $album) {
        $biography[$i]["tracks"] = [];
        $biography[$i]["tracks"] = $repo->findItemByAlbum($album['id']);
        $i++;
    }
    if ($biography) {
            $view->setStatusCode(200)->setData($biography);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

/**
  * Retourne les pistes d'un album donné
  * @Route("/items/album/{idAlbum}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemByAlbumAction($idAlbum){

    $view = FOSView::create();

    $em =$this->getDoctrine()->getManager();
    $repo = $em->getRepository('ByExampleDemoBundle:Item');
    $item = $repo->findItemByAlbum($idAlbum);
    //$item = $repo->findItemByAlbum($idAlbum);

    if ($item) {
            $view->setStatusCode(200)->setData($item);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
  }

  /**
    * Met à jour la note d'un tag sur un item
    * @Route("/items/{idItem}/tags/{idTag}")
    * @Method({"PUT"})
    * @ApiDoc()
    */

    public function putNoteTagItemAction($idItem, $idTag){
      $view = FOSView::create();
      if($this->get('request')->getMethod() == "PUT"){
        $note_tag= $this->container->getParameter('note_tag');
          $param = $this->get('request')->request->get('param');
          $em =$this->getDoctrine()->getManager();
          $repo = $em->getRepository('ByExampleDemoBundle:Tag');
          $note=$repo->addNoteTagItem($idTag, $idItem, $param, $note_tag);
          if ($note) {
                  $view->setStatusCode(200)->setData($note);
              } else {
                  $view->setStatusCode(404);
              }
            }
      return $view;

    }


    /**
      * Met à jour le nombre de vues d'un item
      * @Route("/items/{idItem}/vues/")
      * @Method({"PUT"})
      * @ApiDoc()
      */
      public function updateViewItemAction($idItem){
        $view = FOSView::create();
        $em =$this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleDemoBundle:Item');
        $notes = $repo->updateView($idItem);
        if ($notes) {
                $view->setStatusCode(200)->setData($notes);
            } else {
                $view->setStatusCode(404);
            }

            return $view;
          }
 }
