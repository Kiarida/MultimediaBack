<?php

namespace ByExample\DemoBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use ByExample\DemoBundle\Entity\Tag;
use ByExample\DemoBundle\Entity\Playlist;
use Doctrine\ORM\Query;
use \DateTime;
/**
 * NoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlaylistRepository extends EntityRepository{

	public function findPlaylistById($id, $id_playlist){
		$query=$this->_em->createQuery('SELECT partial p.{id,nom,datecreation}, partial i.{id,url,titre,note,duree,typeitem,nbvues,date,urlCover,urlPoster}, partial a.{id,nom} 
	    FROM ByExampleDemoBundle:Playlist p left join p.iditem i LEFT JOIN i.idartiste a
	    WHERE p.id LIKE :idplaylist 
	    AND p.idutilisateur = :idutil')
	    ->setParameter("idplaylist",$id_playlist)->setParameter("idutil",$id);
	    $playlists=$query->getResult(Query::HYDRATE_ARRAY);
	    return $playlists;
	}

	/*public function findPlaylistById($id, $id_playlist){
		$rsm = new ResultSetMapping($em);
	    $rsm->addScalarResult('idItem', 'idItem');
	    $rsm->addScalarResult('id', 'id');
	    $rsm->addScalarResult('nom', 'nom');
	    $rsm->addScalarResult('dateCreation', 'datecreation');
	    $q = $this->_em->createNativeQuery(
	        'SELECT p.*, idItem FROM playlist p, itemplaylist WHERE p.id = ? AND p.idutilisateur = ?',
	        $rsm
	    )->setParameter(1, $id_playlist)->setParameter(2, $id);
	    $playlists = $q->getResult();
	    return $playlists;
	}*/

	public function insertPlaylist($name, $id){
		$newPlaylist = new Playlist();
        $newPlaylist->setNom($name);
        $newPlaylist->setDatecreation(new DateTime());
        $repository = $this->_em->getRepository('ByExampleDemoBundle:Utilisateur');
        $utilisateur = $repository->findOneById($id);
        $newPlaylist->setIdutilisateur($utilisateur);
        $this->_em->persist($newPlaylist);
        $this->_em->flush();
        $idPlaylist = $newPlaylist->getId();
        return $idPlaylist;
       
	}

	public function findPlaylistByUser($id_utilisateur){
		$query=$this->_em->createQuery('SELECT p.id FROM ByExampleDemoBundle:Playlist p WHERE p.idutilisateur =:id_utilisateur')->setParameter("id_utilisateur",$id_utilisateur);
        $tagplaylist=$query->getResult();
        return $tagplaylist;
	}

	public function updatePlaylist($id_playlist, $name, $id){
		$query = $this->_em->createQuery('UPDATE ByExampleDemoBundle:Playlist p SET p.nom = :name WHERE p.id = :idp AND p.idutilisateur = :idutilisateur')
        ->setParameter('name', $name)->setParameter('idutilisateur', $id)->setParameter('idp', $id_playlist);
        $playlists = $query->getResult();
        return $playlists;
	}

	public function findPlaylistByTag($tags, $id_playlist){
		$query=$this->_em->createQuery('SELECT p.id FROM ByExampleDemoBundle:Playlist p JOIN p.idtag t WHERE p.id =:playlist AND t.id = :tag')->setParameter("playlist",$id_playlist)->setParameter("tag",$tags[0]["id"]);
        $tagplaylist=$query->getResult();
        return $tagplaylist;
	}

	public function insertPlaylistTag($tags, $id_playlist){
		$conn = $this->_em->getConnection();
        $tag = $conn->insert("tagplaylist", array("idTag"=>$tags[0]["id"], "idPlaylist"=>$id_playlist));
        return $tag;
	}

	public function insertTag($libelle, $id_playlist){
		$newTag = new Tag();
        $newTag->setLibelle(strtolower($libelle));
        $this->_em->persist($newTag);
        $this->_em->flush();
        $idTag = $newTag->getId();
        $conn = $this->_em->getConnection();
        $conn->insert("tagplaylist", array("idTag"=>$idTag, "idPlaylist"=>$id_playlist));
        return $newTag;
	}
}