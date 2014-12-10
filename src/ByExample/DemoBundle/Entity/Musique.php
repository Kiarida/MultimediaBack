<?php

namespace ByExample\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Musique
 *
 * @ORM\Table(name="musique")
 * @ORM\Entity
 */
class Musique
{
    /**
     * @var integer
     *
     * @ORM\Column(name="tempo", type="integer", nullable=false)
     */
    private $tempo;

    /**
     * @var integer
     *
     * @ORM\Column(name="mode", type="integer", nullable=false)
     */
    private $mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="loudness", type="integer", nullable=false)
     */
    private $loudness;

    /**
     * @var integer
     *
     * @ORM\Column(name="energy", type="integer", nullable=false)
     */
    private $energy;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotttness", type="integer", nullable=false)
     */
    private $hotttness;

    /**
     * @var integer
     *
     * @ORM\Column(name="danceability", type="integer", nullable=false)
     */
    private $danceability;

    /**
     * @var \Item
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Item")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idItem", referencedColumnName="id")
     * })
     */
    private $iditem;



    /**
     * Set tempo
     *
     * @param integer $tempo
     * @return Musique
     */
    public function setTempo($tempo)
    {
        $this->tempo = $tempo;
    
        return $this;
    }

    /**
     * Get tempo
     *
     * @return integer 
     */
    public function getTempo()
    {
        return $this->tempo;
    }

    /**
     * Set mode
     *
     * @param integer $mode
     * @return Musique
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    
        return $this;
    }

    /**
     * Get mode
     *
     * @return integer 
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set loudness
     *
     * @param integer $loudness
     * @return Musique
     */
    public function setLoudness($loudness)
    {
        $this->loudness = $loudness;
    
        return $this;
    }

    /**
     * Get loudness
     *
     * @return integer 
     */
    public function getLoudness()
    {
        return $this->loudness;
    }

    /**
     * Set energy
     *
     * @param integer $energy
     * @return Musique
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;
    
        return $this;
    }

    /**
     * Get energy
     *
     * @return integer 
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * Set hotttness
     *
     * @param integer $hotttness
     * @return Musique
     */
    public function setHotttness($hotttness)
    {
        $this->hotttness = $hotttness;
    
        return $this;
    }

    /**
     * Get hotttness
     *
     * @return integer 
     */
    public function getHotttness()
    {
        return $this->hotttness;
    }

    /**
     * Set danceability
     *
     * @param integer $danceability
     * @return Musique
     */
    public function setDanceability($danceability)
    {
        $this->danceability = $danceability;
    
        return $this;
    }

    /**
     * Get danceability
     *
     * @return integer 
     */
    public function getDanceability()
    {
        return $this->danceability;
    }

    /**
     * Set iditem
     *
     * @param \ByExample\DemoBundle\Entity\Item $iditem
     * @return Musique
     */
    public function setIditem(\ByExample\DemoBundle\Entity\Item $iditem)
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