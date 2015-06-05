<?php

namespace ByExample\RecommandationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ByExample\DemoBundle\Entity\Utilisateur;

/**
 * TestUser
 *
 * @ORM\Table(name="testutilisateur")
 * @ORM\Entity
 */
class TestUser
{


     /**
     * @var \Utilisateur
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ByExample\DemoBundle\Entity\Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="id")
     * })
     */
    private $idutilisateur;

    /**
     * @var \Algorithm
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ByExample\RecommandationsBundle\Entity\Test")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTest", referencedColumnName="id")
     * })
     */
    private $idtest;


      /**
     * @var integer
     *
     * @ORM\Column(name="groupe", type="string", length=25, nullable=false)
     */
    private $groupe;


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
     * @return TestUser
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
     * @return TestUser
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
     * Set groupe
     *
     * @param string $groupe
     * @return TestUser
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }
    /**
     * Get groupe
     *
     * @return string
     */
    public function getGroupe()
    {
        return $this->groupe;
    }


}