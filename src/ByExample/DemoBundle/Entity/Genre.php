<?php

namespace ByExample\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity
 */
class Genre
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
     * @ORM\Column(name="libelle", type="string", length=25, nullable=false)
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="idgenre")
     */
    private $iditem;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->iditem = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

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
     * Set libelle
     *
     * @param string $libelle
     * @return Genre
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    
        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Add iditem
     *
     * @param \ByExample\DemoBundle\Entity\Item $iditem
     * @return Genre
     */
    public function addIditem(\ByExample\DemoBundle\Entity\Item $iditem)
    {
        $this->iditem[] = $iditem;
    
        return $this;
    }

    /**
     * Remove iditem
     *
     * @param \ByExample\DemoBundle\Entity\Item $iditem
     */
    public function removeIditem(\ByExample\DemoBundle\Entity\Item $iditem)
    {
        $this->iditem->removeElement($iditem);
    }

    /**
     * Get iditem
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIditem()
    {
        return $this->iditem;
    }
}