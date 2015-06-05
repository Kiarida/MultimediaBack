<?php

namespace ByExample\RecommandationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ByExample\DemoBundle\Entity\Utilisateur;

/**
 * Test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity(repositoryClass="ByExample\RecommandationsBundle\Repository\TestRepository")
 */
class Test
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
     * @ORM\Column(name="dateDebut", type="datetime", nullable=false)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $datefin;

      /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=25, nullable=true)
     */
    private $label;


   /**
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="ByExample\RecommandationsBundle\Entity\TestUser", mappedBy="idtest", fetch="EXTRA_LAZY")
         *
         */
        private $idtestuser;

    /**
     * @var Algorithm
     *
     * @ORM\ManyToMany(targetEntity="Algorithm", inversedBy="idtest")
     * @ORM\JoinTable(name="testalgorithm",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idTest", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idAlgorithm", referencedColumnName="id")
     *   }
     * )
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
     * Add idtestuser
     *
     * @param \ByExample\RecommandationsBundle\Entity\TestUser $idtestuser
     * @return Test
     */
    public function addIdtestuser(\ByExample\RecommandationsBundle\Entity\TestUser $idtestuser)
    {
        $this->idtestuser[] = $idtestuser;

        return $this;
    }

    /**
     * Remove idtestuser
     *
     * @param \ByExample\RecommandationsBundle\Entity\TestUser $idtestuser
     */
    public function removeIdtestuser(\ByExample\RecommandationsBundle\Entity\TestUser $idtestuser)
    {
        $this->idtestuser->removeElement($idtestuser);
    }

    /**
     * Get idtestuser
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdtestuser(){
        return $this->idtestuser;
    }


    /**
     * Add idalgorithm
     *
     * @param \ByExample\RecommandationsBundle\Entity\Algorithm $idalgorithm
     * @return Test
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

}