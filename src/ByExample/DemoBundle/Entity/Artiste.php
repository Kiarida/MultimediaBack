<?php

namespace ByExample\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Artiste
 *
 * @ORM\Table(name="artiste")
 * @ORM\Entity(repositoryClass="ByExample\DemoBundle\Repository\ArtisteRepository")
 */
class Artiste
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
     * @var decimal
     *
     * @ORM\Column(name="note", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $note;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="idartiste", fetch="LAZY")
     */
    private $iditem;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="idartiste")
     */
    private $idtag;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->iditem = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idtag = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Artiste
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set note
     *
     * @param integer $note
     * @return Artiste
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return integer 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Add iditem
     *
     * @param \ByExample\DemoBundle\Entity\Item $iditem
     * @return Artiste
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

    /**
     * Add idtag
     *
     * @param \ByExample\DemoBundle\Entity\Tag $idtag
     * @return Artiste
     */
    public function addIdtag(\ByExample\DemoBundle\Entity\Tag $idtag)
    {
        $this->idtag[] = $idtag;
    
        return $this;
    }

    /**
     * Remove idtag
     *
     * @param \ByExample\DemoBundle\Entity\Tag $idtag
     */
    public function removeIdtag(\ByExample\DemoBundle\Entity\Tag $idtag)
    {
        $this->idtag->removeElement($idtag);
    }

    /**
     * Get idtag
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdtag()
    {
        return $this->idtag;
    }
}