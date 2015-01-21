<?php

namespace ByExample\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity
 */
class Note
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
     * @var decimal
     *
     * @ORM\Column(name="note", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="id")
     * })
     */
    private $idutilisateur;

    /**
     * @var \Artiste
     *
     * @ORM\ManyToOne(targetEntity="Artiste")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idArtiste", referencedColumnName="id")
     * })
     */
    private $idartiste;




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
     * Set note
     *
     * @param integer $note
     * @return Note
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
     * Set date
     *
     * @param \DateTime $date
     * @return Note
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set idutilisateur
     *
     * @param \ByExample\DemoBundle\Entity\Utilisateur $idutilisateur
     * @return Note
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
     * Set idartiste
     *
     * @param \ByExample\DemoBundle\Entity\Artiste $idartiste
     * @return Note
     */
    public function setIdartiste(\ByExample\DemoBundle\Entity\Artiste $idartiste = null)
    {
        $this->idartiste = $idartiste;
    
        return $this;
    }

    /**
     * Get idartiste
     *
     * @return \ByExample\DemoBundle\Entity\Artiste 
     */
    public function getIdartiste()
    {
        return $this->idartiste;
    }

    /**
     * Set iditem
     *
     * @param \ByExample\DemoBundle\Entity\Item $iditem
     * @return Note
     */
    public function setIditem(\ByExample\DemoBundle\Entity\Item $iditem = null)
    {
        $this->iditem = $iditem;
    
        return $this;
    }

    /**
     * Get iditem
     *
     * @return \ByExample\DemoBundle\Entity\Item 
     */
    public function getIditem()
    {
        return $this->iditem;
    }
}