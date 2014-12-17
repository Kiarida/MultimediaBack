<?php
namespace ByExample\DemoBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

 
/**
 * UserRecipeAssociation
 *
 * @ORM\Table( name = "notetagitem")
 * @ORM\Entity
 */
class NoteTagItem
{


    /**
     * @var string
     *
     * @ORM\Column(name="note", type="decimal")
     */
    private $note;
    /** 
    * @ORM\Column(name="idTag", type="integer", nullable=false)
    *@ORM\Id @ORM\ManyToOne(targetEntity="Tag") */
 
    private $idtag;
 /**
 * @ORM\Column(name="idItem", type="integer", nullable=false)
 * @ORM\Id @ORM\ManyToOne(targetEntity="Item") */
    private $iditem;
 
    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->id;
    }
 

    /**
     * Set note
     *
     * @param string $note
     * @return NoteTagItem
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }


    /**
     * Add idtag
     *
     * @param \ByExample\DemoBundle\Entity\Tag $idtag
     * @return NoteTagItem
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

 
    /**
     * Add iditem
     *
     * @param \ByExample\DemoBundle\Entity\Item $iditem
     * @return NoteTagItem
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
?>