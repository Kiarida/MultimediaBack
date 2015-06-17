<?php

namespace ByExample\RecommandationsBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use ByExample\RecommandationsBundle\Entity\Algorithm;
use ByExample\RecommandationsBundle\Entity\Test;
use ByExample\RecommandationsBundle\Entity\Group;
use Doctrine\ORM\Query;
use \DateTime;

/**
 * Group
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends EntityRepository{

	

	public function createGroup($groups){
		$query = $this->_em->createQuery(
                'SELECT u.id
                FROM ByExampleDemoBundle:Utilisateur u 
                ');
                $count = $query->getResult(Query::HYDRATE_ARRAY);
                $nbUtil = count($count);
                $arrayGroup = [];
                for ($i =1; $i <= $groups; $i++) {
                	$arrayGroup[$i]=[];

                }
                $complete = false;
                $limite=$nbUtil/$groups;

                foreach ($count as $util) {
                	if($complete){
                		$limite = ($nbUtil/$groups)+1;
                	}
                	$result= rand(1, $groups);
                	while(count($arrayGroup[$result]) >= floor($limite)){
                		$result= rand(1, $groups);
                	}
                	array_push($arrayGroup[$result], $util);
                	$complete=true;
                	foreach($arrayGroup as $tableau){
                		if(count($tableau) != floor($limite)){
                			$complete=false;
                		}
                		
                	}
                }



                return $arrayGroup;
	}

        public function attributionGroup($test, $groupe, $users, $algos){
                $repositoryAlgo = $this->_em->getRepository('ByExampleRecommandationsBundle:Algorithm');
                $repositoryUtil = $this->_em->getRepository('ByExampleDemoBundle:Utilisateur');
                //$repositoryGroup = $this->_em->getRepository('ByExampleRecommandationsBundle:Group');
                //$arraygroups = $repositoryGroup->createGroup($groups);

                $newgroup = new Group();
                $newgroup->setNumero($groupe);
                foreach($users as $user){
                        //return $repositoryUtil->findOneById($user["id"]);
                        $newgroup->addIdutilisateur($repositoryUtil->findOneById($user["id"])); 
                }
                    
               for($i = 0; $i < count($algos); $i++)  {  
                //foreach ($algos as $idalgo) {
                        $newgroup->addIdAlgorithm($repositoryAlgo->findOneById($algos[$i]));
                }
                        $newgroup->setIdtest($test);
                        $this->_em->persist($newgroup);
                        $this->_em->flush();
                
                return $newgroup;
        }

        public function getGroup($idtest){
                $query = $this->_em->createQuery('SELECT g
                     FROM ByExampleRecommandationsBundle:Group g
                     WHERE g.idtest = :key')
                ->setParameter('key', $idtest);
                $groupe = $query->getResult(Query::HYDRATE_ARRAY);
                return $groupe;
        }

        public function verifyGroups($nbgroups, $allowedGroups){

         $query = $this->_em->createQuery('SELECT COUNT(u.id)
                     FROM ByExampleDemoBundle:Utilisateur u');
                $users = $query->getResult(Query::HYDRATE_ARRAY);
                $count = $users[0][1]/$nbgroups;
                if($count >= $allowedGroups){
                  return true;
                }
                else{
                  return false;
                }
                return false;
        }

	

	



}
