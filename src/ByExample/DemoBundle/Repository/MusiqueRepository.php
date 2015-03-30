<?php

namespace ByExample\DemoBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use ByExample\DemoBundle\Entity\Musique;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * MusiqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MusiqueRepository extends EntityRepository
{

public function putMusicItem($idItem, $infos){
    $repository = $this->_em->getRepository('ByExampleDemoBundle:Item');
    $item = $repository->find($idItem);
    $newMusique=new Musique();
    $newMusique->setTempo($infos["songs"][0]["audio_summary"]["tempo"]);
    $newMusique->setMode($infos["songs"][0]["audio_summary"]["mode"]);
    $newMusique->setLoudness($infos["songs"][0]["audio_summary"]["loudness"]);
    $newMusique->setEnergy($infos["songs"][0]["audio_summary"]["energy"]);
    $newMusique->setDanceability($infos["songs"][0]["audio_summary"]["danceability"]);
    $newMusique->setHotttness($infos["songs"][0]["song_hotttnesss"]);
    $newMusique->setIditem($item);
    $this->_em->persist($newMusique);
    $this->_em->flush();

  }

}