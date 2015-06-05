<?php

namespace ByExample\RecommandationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ByExample\DemoBundle\Entity\Utilisateur;

/**
 * Recommandation
 * @ORM\Entity
 * @ORM\Table(name="recommandation")
 */
class Recommandation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;


   /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Algorithm", mappedBy="idrecommandation")
     */
    private $idalgorithm;


     /**
     * @var \ByExample\DemoBundle\Entity\Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="ByExample\DemoBundle\Entity\Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="id")
     * })
     */
    private $idutilisateur;


      /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ByExample\DemoBundle\Entity\Item", inversedBy="idrecommandation")
     * @ORM\JoinTable(name="itemrecommandation",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idRecommandation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idItem", referencedColumnName="id")
     *   }
     * )
     */
    private $iditem;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

     /**
     * Get date
     *
     * @return string 
     */
    public function getDate(){
        return $this->date;
    }


    /**
     * Set date
     *
     * @param string $date
     * @return Algorithm
     */
    public function setDate($date){
        $this->date = $date;
    }

     /**
     * Set idalgorithm
     *
     * @param \ByExample\RecommandationsBundle\Entity\Algorithm $idalgorithm
     * @return Actions
     */
    public function setIdalgorithm(\ByExample\RecommandationsBundle\Entity\Algorithm $idalgorithm = null)
    {
        $this->idalgorithm = $idalgorithm;
    
        return $this;
    }

    /**
     * Get idalgorithm
     *
     * @return \ByExample\RecommandationsBundle\Entity\Algorithm
     */
    public function getIdalgorithme()
    {
        return $this->idalgorithm;
    }

     /**
     * Set idutilisateur
     *
     * @param \ByExample\DemoBundle\Entity\Utilisateur $idutilisateur
     * @return Recommandation
     */
    public function setIdutilisateur(\ByExample\DemoBundle\Entity\Utilisateur $idutilisateur = null)
    {
        $this->idutilisateur = $idutilisateur;
    
        return $this;
    }

    /**
     * Get idutilisateur
     *
     * @return \ByExample\DemoBundle\Entity\Utilisateur 
     */
    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    
}