<?php

namespace ByExample\DemoBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use ByExample\DemoBundle\Entity\Utilisateur;
use Doctrine\ORM\Query;
/**
 * ActionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UtilisateurRepository extends EntityRepository{

	public function addFriend($id, $id_ami){
                //$newUser = new Utilisateur();
                //$newUser->setLibelle(strtolower($libelle));
                //$this->_em->persist($newTag);
                //$this->_em->flush();
                //$idTag = $newUser->getId();
		$conn = $this->_em->getConnection();
                $conn->insert("utilisateuramis", array("idUtilisateur"=>$id, "idUtilisateurAmi"=>$id_ami));
	}

        public function searchFriend($id, $id_ami){
                $query=$this->_em->
                createQuery('SELECT u.id FROM ByExampleDemoBundle:Utilisateur u JOIN u.idutilisateurami t WHERE u.id =:utilisateur AND t.id = :utilisateurAmi')
                ->setParameter("utilisateur",$id)->setParameter("utilisateurAmi",$id_ami);
                $ami=$query->getResult();
                return $ami;
        
        }

        public function findFriends($id){
                $query=$this->_em->createQuery('SELECT u.username, u.id FROM ByExampleDemoBundle:User u, ByExampleDemoBundle:Utilisateur p JOIN p.idutilisateurami t WHERE p.id =:utilisateur AND u.id=t.id')->setParameter("utilisateur",$id);
                $friends=$query->getResult();
                return $friends;
        }

         


}
