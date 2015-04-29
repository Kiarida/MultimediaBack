<?php

namespace ByExample\DemoBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetMetadataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('getMetadata')
            ->setDescription('Get metadata from echonest for new items')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$em = $this->getContainer()->get('doctrine')->getManager();
        


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
          $repoArtiste->updateImgArtistLastFM($item["idartiste"]);
          $asso = $this->getGenresItemAction($item["idartiste"][0]["id"]);
          
        }
          
       }
       $infos[$item["id"]] = $infodecode;
       curl_close($ch);
     }


        $em->flush();
        $output->writeln("finish");

    }




    public function getGenresItemAction($idArtist){
     $em = $this->getContainer()->get('doctrine')->getManager();
     $repo = $em->getRepository('ByExampleDemoBundle:Item');
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
       $items=$repo->findItemByArtist($artist->getId());
       foreach($items as $item){
           $new = $repoGenre->addGenre($item, $infodecode["response"]);
           array_push($infos, $new);
         

         }
      }



}




?>