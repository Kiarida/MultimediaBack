<?php

namespace ByExample\DemoBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use ByExample\DemoBundle\Entity\Tag;
use Doctrine\ORM\Query;

/**
 * EcouteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EcouteRepository extends EntityRepository{

	public function findEcoutesByUser($id_user, $limit){
		$query=$this->_em->createQuery('SELECT partial e.{id,date, typeecoute}, partial i.{id,url,titre,note,duree,typeitem,nbvues,date,urlCover,urlPoster}, partial a.{id,nom}
	    FROM ByExampleDemoBundle:Ecoute e
	    LEFT  JOIN e.idsession s
	    LEFT  JOIN e.iditem i
	    LEFT  JOIN i.idartiste a
        WHERE s.idutilisateur = :user
        ORDER BY e.id DESC')
	    ->setParameter("user",$id_user)
	    ->setMaxResults($limit);
	    $items=$query->getResult(Query::HYDRATE_ARRAY);
	    return $items;
	}
}