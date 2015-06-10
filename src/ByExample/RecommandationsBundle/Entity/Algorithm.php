<?php

namespace ByExample\RecommandationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ByExample\DemoBundle\Entity\Utilisateur;

/**
 * Algorithm
 *
 * @ORM\Table(name="algorithm")
 * @ORM\Entity(repositoryClass="ByExample\RecommandationsBundle\Repository\AlgorithmRepository")
 */
class Algorithm
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=25, nullable=false)
     */
    private $nom;

     /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=25, nullable=true)
     */
    private $label;

     /**
     * @var boolean
     *
     * @ORM\Column(name="precalculated", type="boolean", nullable=false)
     */
    private $precalculated;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ByExample\RecommandationsBundle\Entity\Test", mappedBy="idalgorithm")
     */
    private $idtest;


     /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ByExample\RecommandationsBundle\Entity\Group", mappedBy="idalgorithm")
     */
    private $idgroup;

  


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
     * Get nom
     *
     * @return string 
     */
    public function getNom(){
        return $this->nom;
    }


    /**
     * Set nom
     *
     * @param string $nom
     * @return Algorithm
     */
    public function setNom($nom){
        $this->nom = $nom;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel(){
        return $this->label;
    }


    /**
     * Set label
     *
     * @param string $label
     * @return Algorithm
     */
    public function setLabel($label){
        $this->label = $label;
    }

    /**
     * Get precalculated
     *
     * @return boolean 
     */
    public function getPrecalculated(){
        return $this->precalculated;
    }


      /**
     * Set precalculated
     *
     * @param boolean $precalculated
     * @return Algorithm
     */
    public function setPrecalculated($precalculated){
        $this->precalculated = $precalculated;
    }

}