<?php

namespace ByExample\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ByExample\DemoBundle\Entity\Item;
use ByExample\DemoBundle\Entity\Tag;
use ByExample\DemoBundle\Entity\gsAPI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View AS FOSView;
use ByExample\DemoBundle\Repository\ItemRepository;
use ByExample\DemoBundle\Repository\MusiqueRepository;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Put;
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
use Symfony\Component\HttpFoundation\Session\Session;
use \DateTime;

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
  * Parcourt la liste des items en BDD et récupère les infos depuis echonest
  * @Route("/items/echonest/{idTrack}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getItemEchonestAction($idTrack){
     $view = FOSView::create();
     $em =$this->getDoctrine()->getManager();
     $repo = $em->getRepository('ByExampleDemoBundle:Item');

     $items=$repo->findNewItemsAndArtists();
     $infos=[];
     foreach($items as $item){
       $titre=$item["titre"];
       $artist=$item["idartiste"][0]["nom"];

       $params = array("artist" => $artist, "title" => $titre, "bucket" => "song_hotttnesss");
       $param2=array("bucket" =>"audio_summary");
       $param3=array("bucket"=>"artist_familiarity");
       $param4=array("bucket"=>"artist_hotttnesss");
       $url="http://developer.echonest.com/api/v4/song/search?api_key=1N7LROIETL6PEVJAF&format=json&results=1";

       $url .= '&' . http_build_query($params);
       $url .= '&' . http_build_query($param2);
       $url .= '&' . http_build_query($param3);
       $url .= '&' . http_build_query($param4);

       $ch = curl_init();
       curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
       curl_setopt($ch, CURLOPT_URL, $url );
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $info=curl_exec($ch);
       $infodecode = json_decode($info, true);

       $repoMusique = $em->getRepository('ByExampleDemoBundle:Musique');
       $repoArtiste = $em->getRepository('ByExampleDemoBundle:Artiste');
       if(!empty($infodecode["response"]["songs"])){
         $repoMusique->putMusicItem($item["id"],$infodecode["response"]);
         //$insertitems = $repo->getAlbumLastFM($item["idartiste"][0]["nom"], $item["idalbum"]);
         if(!isset($item["idalbum"][0]["urlCover"])){
            $insertitems = $repo->getAlbumLastFM($item["idartiste"][0]["nom"], $item["idalbum"], $item["id"]);
         }
         if(!isset($item["idartiste"][0]["urlCover"])){
          $repoArtiste->putMusicArtist($item["idartiste"][0]["id"], $infodecode["response"]);
          $update = $repoArtiste->updateImgArtistLastFM($item["idartiste"], $infodecode["response"]["songs"][0]["artist_id"]);
          $asso = $this->getGenresItemAction($item["idartiste"][0]["id"]);
          //$similar = $repoArtiste->getSimilarArtists($item["idartiste"]);
          
        }
          
       }
       $infos[$item["id"]] = $infodecode;
       curl_close($ch);
     }
     if ($asso) {
            $view->setStatusCode(200)->setData($asso);
        } else {
            $view->setStatusCode(407);
        }

        return $view;
  }




  /**
  * Récupère les infos sur les artistes depuis echonest
  * @Route("/items/getgenres/{idArtist}")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getGenresItemAction($idArtist){
     $view = FOSView::create();
     $em =$this->getDoctrine()->getManager();
     $repoGenre = $em->getRepository('ByExampleDemoBundle:Genre');
     $repoArtists = $em->getRepository('ByExampleDemoBundle:Artiste');
     $artist=$repoArtists->find($idArtist);
     $infos=[];
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

       curl_close($ch);

      $new = $repoGenre->addGenre($artist, $infodecode["response"]);
         

     

     if ($new) {
            $view->setStatusCode(200)->setData($new);
        } else {
            $view->setStatusCode(407);
        }

        return $view;
  }






  /**
  * Récupère les infos sur les artistes depuis echonest
  * @Route("")
  * @Method({"GET"})
  * @ApiDoc()
  */
  public function getGenresAction(){
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

       curl_close($ch);
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
      $j=0;
        $biography[$i]["tracks"] = [];
        $biography[$i]["tracks"] = $repo->findItemByAlbum($album['id']);
        foreach ($biography[$i]["tracks"] as $track) {
          $biography[$i]["tracks"][$j]["idalbum"][0]=array("id"=> $album['id'], "titre"=>$album["titre"]);
          $j++;
        }
        
        
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
    * @Route("users/{iduser}/items/{idItem}/tags/{idTag}")
    * @Method({"PUT"})
    * @ApiDoc()
    */

    public function putNoteTagItemAction($iduser,$idItem, $idTag){
      $view = FOSView::create();
      if($this->get('request')->getMethod() == "PUT"){
        $note_tag= $this->container->getParameter('note_tag');
          $param = $this->get('request')->request->get('param');
          $em =$this->getDoctrine()->getManager();
          $type= $this->container->getParameter('type_note_tag');
          $repo = $em->getRepository('ByExampleDemoBundle:Note');
          $note=$repo->addNoteTagItem($idTag, $idItem, $iduser, $param, $note_tag, $type);
          if ($note) {
                  $view->setStatusCode(200)->setData($note);
              } else {
                  $view->setStatusCode(404);
              }
            }
      return $view;

    }

    /**
      * Récupère les deux notes d'un tag sur un item
      * @Route("users/{iduser}/items/{idItem}/tags/{idTag}")
      * @Method({"GET"})
      * @ApiDoc()
      */

      public function getNoteTagItemAction($iduser,$idItem, $idTag){
        $view = FOSView::create();
        $em =$this->getDoctrine()->getManager();
        $notes=array();
        $repo = $em->getRepository('ByExampleDemoBundle:Note');
        $noteUtil=$repo->findByItemTagUser($idItem,$idTag, $iduser);

        if ($noteUtil) {
                    $view->setStatusCode(200)->setData($noteUtil);
                } else {
                    $view->setStatusCode(404);

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

    /**
    * Test streaming grooveshark
    * @Route("items/grooveshark/{iditem}")
    * @Method({"GET"})
    * @ApiDoc()
    */

    public function getGroovesharkAction($iditem, Request $request){
      $view = FOSView::create();
      $this->getRequest()->getSession()->clear();
      $em =$this->getDoctrine()->getManager();
      $repo = $em->getRepository('ByExampleDemoBundle:Item');
      $item=$repo->find($iditem);
      $url=$item->getUrl();
      $url = $repo->getItemGrooveshark($url, $request);
    //$session = $request->getSession();

// définit et récupère des attributs de session
    
    if($url){
          $view->setStatusCode(200)->setData($url);
      } else {
          $view->setStatusCode(404);
      }

      return $view;
    }


    /**
    * Marks 30 seconds
    * @Route("items/grooveshark/mark30secondes")
    * @Method({"GET"})
    * @ApiDoc()
    */
    public function mark30secondeAction(){
      $view = FOSView::create();
      $gs = new gsAPI();

      $session = $this->getRequest()->getSession();
      $gs->sessionID=$session->get("gsSessionID");
      $streamKey = $session->get('gsStreamKey');
      $streamServer = $session->get('gsStreamServer');
      $success = $gs->markStreamKeyOver30Secs($streamKey, $streamServer);
        
      if($success){
        $view->setStatusCode(200)->setData($success);
      }
      else{
        $view->setStatusCode(404);
      }

      return $view;
    }

    /**
  * Recherche des items dans la base en fonction du mot clé donné en paramètre
  * @Route("/items/grooveshark/search/{key}")
  * @Method({"GET"})
  * @ApiDoc()
  */
    public function searchItemGroovesharkAction($key){
      $view = FOSView::create();
      $api_key=$this->container->getParameter('api_key');
      $array=[];
      $params = array("combined" => $key, "bucket" => "id:rhapsody-FR");
       $param2=array("bucket" =>"tracks");
    
       $url="http://developer.echonest.com/api/v4/song/search?api_key=NNLVK0KSQ8PVIAOTU&format=json";

       $url .= '&' . http_build_query($params);
       $url .= '&' . http_build_query($param2);
       $url .= '&' . http_build_query($param3);
       $url .= '&' . http_build_query($param4);

       $ch = curl_init();
       curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
       curl_setopt($ch, CURLOPT_URL, $url );
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $info=curl_exec($ch);
       $infodecode = json_decode($info, true);

       if($infodecode){
        foreach($infodecode["response"]["songs"] as $song){
          if($song["tracks"][0]["foreign_id"]){
            $result=explode(":", $song["tracks"][0]["foreign_id"]);
            $id_rhapso = $result[2];
            //$params = array("" => $key, "type" => "track");

              $url="http://api.rhapsody.com/v1/tracks/".$id_rhapso;

              //$url .= "?". http_build_query($params);


              $ch = curl_init();
              curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'apikey:'.$api_key));
              curl_setopt($ch, CURLOPT_URL, $url );
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $info=curl_exec($ch);
              $songs = json_decode($info, true);
              if($songs["code"]){
                $url="http://api.rhapsody.com/v1/search/typeahead";
                $params = array("q" => $song["title"], "type" => "track");
                $url .= "?". http_build_query($params);

                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'apikey:'.$api_key));
                curl_setopt($ch, CURLOPT_URL, $url );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $info=curl_exec($ch);
                $infodecode2 = json_decode($info, true);
                foreach($infodecode2 as $songRhapsody){
                  if($songRhapsody["artist"]["name"]==$song["artist_name"]){
                    array_push($array,$songRhapsody);
                  }
                
                
                }
              }
              else{
                  //$songs=$infodecode;
                  array_push($array, $songs);
                }

              
            }
        }
        //$result=explode(":",$infodecode["response"]["songs"][0]["tracks"][0]["foreign_id"]);
        //$id_rhapso=
       }

      /*$params = array("q" => $key, "type" => "track");

            $url="http://api.rhapsody.com/v1/search/typeahead";

            $url .= "?". http_build_query($params);


            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'apikey:'.$api_key));
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $info=curl_exec($ch);
            $infodecode = json_decode($info, true);*/

       if($array){
        $view->setStatusCode(200)->setData($array);
      }
      else{
        $view->setStatusCode(404);
      }

      return $view;
    }
    /**
    * Mark complete video
    * @Route("items/grooveshark/markComplete")
    * @Method({"GET"})
    * @ApiDoc()
    */
    public function markCompleteAction(){
      $view = FOSView::create();
      $gs = new gsAPI();

      $session = $this->getRequest()->getSession();
      $gs->sessionID=$session->get("gsSessionID");
      $streamKey = $session->get('gsStreamKey');
      $songID = $session->get('gsSongID');
      $streamServer = $session->get('gsStreamServer');
      $success = $gs->markSongComplete($songID, $streamKey, $streamServer);
        
      if($success){
        $view->setStatusCode(200)->setData($success);
      }
      else{
        $view->setStatusCode(404);
      }

      return $view;
    }


    /**
    * Get info from grooveshark artist
    * @Route("items/grooveshark/artistInfo")
    * @Method({"GET"})
    * @ApiDoc()
    */
    public function artistInfoAction(){
      $view = FOSView::create();
      $gs = new gsAPI();

      $session = $this->getRequest()->getSession();
      $gs->sessionID=$session->get("gsSessionID");
    
      $success = $gs->getArtistAlbums(401561);
      foreach($success as $succ){

      }
      if($success){
        $view->setStatusCode(200)->setData($success);
      }
      else{
        $view->setStatusCode(404);
      }

      return $view;
    }

    /**
    * Insère titre / album et artiste sommairement
    * @Post("items")
    * @ApiDoc()
    */
    public function addItemArtisteAction(){
      $view = FOSView::create();
      if($this->get('request')->getMethod() == "POST"){
        $em =$this->getDoctrine()->getManager();
        $url = $this->getRequest()->request->get('url');
        $titre = $this->getRequest()->request->get('titre');
        $nomAlbum=$this->getRequest()->request->get('nomAlbum');
        $duration=$this->getRequest()->request->get('duration');
        //$typeitem = $this->getRequest()->query->get('typeitem');
        $nom = $this->getRequest()->request->get('nom');
        $repo = $em->getRepository('ByExampleDemoBundle:Item');
        $success = $repo->addItemArtiste($url, $titre, $nomAlbum, $nom, $duration);
        

        //
      }
      if($success){
       //$link = $repo->getItemGrooveshark($url, $this->getRequest());
        $view->setStatusCode(200)->setData($success);
      }
      else{
        $view->setStatusCode(404);
      }

      return $view; 
    }


 }
