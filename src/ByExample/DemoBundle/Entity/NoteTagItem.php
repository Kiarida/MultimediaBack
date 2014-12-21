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



}