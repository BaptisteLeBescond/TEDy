<?php

namespace SequenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planning
 *
 * @ORM\Table(name="planning")
 * @ORM\Entity(repositoryClass="SequenceBundle\Repository\PlanningRepository")
 */
class Planning
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var bool
     *
     * @ORM\Column(name="enCours", type="boolean")
     */
    private $enCours;

    /**
      * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
      */
    private $enfant;

    /**
      * @ORM\ManyToOne(targetEntity="SequenceBundle\Entity\Sequence")
      */
    private $sequence;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     */
    private $duree;

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
     * @return Planning
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
     * Set enCours
     *
     * @param boolean $enCours
     * @return Planning
     */
    public function setEnCours($enCours)
    {
        $this->enCours = $enCours;
    
        return $this;
    }

    /**
     * Get enCours
     *
     * @return boolean 
     */
    public function getEnCours()
    {
        return $this->enCours;
    }

    /**
     * Set enfant
     *
     * @param \UserBundle\Entity\User $enfant
     * @return Planning
     */
    public function setEnfant(\UserBundle\Entity\User $enfant = null)
    {
        $this->enfant = $enfant;
    
        return $this;
    }

    /**
     * Get enfant
     *
     * @return \UserBundle\Entity\User 
     */
    public function getEnfant()
    {
        return $this->enfant;
    }

    /**
     * Set sequence
     *
     * @param \SequenceBundle\Entity\Sequence $sequence
     * @return Planning
     */
    public function setSequence(\SequenceBundle\Entity\Sequence $sequence = null)
    {
        $this->sequence = $sequence;
    
        return $this;
    }

    /**
     * Get sequence
     *
     * @return \SequenceBundle\Entity\Sequence 
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set duree
     *
     * @param string $duree
     * @return Planning
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
    
        return $this;
    }

    /**
     * Get duree
     *
     * @return string 
     */
    public function getDuree()
    {
        return $this->duree;
    }
}
