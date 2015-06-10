<?php

namespace ByExample\RecommandationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ByExample\DemoBundle\Entity\Utilisateur;

/**
 * Group
 *
 * @ORM\Table(name="group")
 * @ORM\Entity
 */
class Group
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
     * @var \Utilisateur
     *
     * 
     * @ORM\ManyToOne(targetEntity="ByExample\DemoBundle\Entity\Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="id")
     * })
     */
    private $idutilisateur;

    /**
     * @var \Algorithm
     * 
     * @ORM\ManyToOne(targetEntity="ByExample\RecommandationsBundle\Entity\Test")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTest", referencedColumnName="id")
     * })
     */
    private $idtest;


      /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="string", length=25, nullable=false)
     */
    private $numero;


     /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ByExample\RecommandationsBundle\Entity\Algorithm", mappedBy="idgroup")
     */
    private $idalgorithm;


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
     * Set idutilisateur
     *
     * @param \ByExample\DemoBundle\Entity\Utilisateur $idutilisateur
     * @return Group
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


     /**
     * Set idtest
     *
     * @param \ByExample\RecommandationsBundle\Entity\Test $idtest
     * @return Group
     */
    public function setIdtest(\ByExample\RecommandationsBundle\Entity\Test $idtest = null)
    {
        $this->idtest = $idtest;
    
        return $this;
    }

    /**
     * Get idtest
     *
     * @return \Test
     */
    public function getIdtest()
    {
        return $this->idtest;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Group
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }
    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

     /**
     * Add idalgorithm
     *
     * @param \ByExample\RecommandationsBundle\Entity\Algorithm $idalgorithm
     * @return Group
     */
    public function addIdalgorithm(\ByExample\RecommandationsBundle\Entity\Algorithm $idalgorithm)
    {
        $this->idalgorithm[] = $idalgorithm;

        return $this;
    }

    /**
     * Remove idalgorithm
     *
     * @param \ByExample\RecommandationsBundle\Entity\Algorithm $idalgorithm
     */
    public function removeIdalgorithm(\ByExample\RecommandationsBundle\Entity\Algorithm $idalgorithm)
    {
        $this->idalgorithm->removeElement($idalgorithm);
    }

    /**
     * Get idalgorithm
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdalgorithm()
    {
        return $this->idalgorithm;
    }


}