<?php

namespace ByExample\DemoBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use ByExample\DemoBundle\Entity\Tag;
use Doctrine\ORM\Query;
/**
 * NoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends EntityRepository{


	public function findTagByIdPlay($id_playlist){
		$query = $this->_em->createQuery(
        'SELECT partial t.{id,libelle} FROM ByExampleDemoBundle:Tag t JOIN t.idplaylist g WHERE g.id= :id')
        ->setParameter('id', intval($id_playlist));
        $tags=$query->getResult(Query::HYDRATE_ARRAY);
        return $tags;
	}

	public function findTagById($idTag){
		$query=$this->_em->createQuery('SELECT partial t.{id,libelle}
      	FROM ByExampleDemoBundle:Tag t
      	WHERE t.id = :idtag')
        ->setParameter("idtag",$idTag);
        $tags=$query->getResult(Query::HYDRATE_ARRAY);
        return $tags;
	}

	public function findTagByLibelle($libelle){
		$query = $this->_em->createQuery(
	    "SELECT partial t.{id,libelle}
	    FROM ByExampleDemoBundle:Tag t
	    WHERE t.libelle LIKE '%".strtolower($libelle)."%'");
	  	$tags = $query->getResult(Query::HYDRATE_ARRAY);
	  	return $tags;
	}


	
}
