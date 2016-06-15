<?php

namespace SequenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table(name="contrat")
 * @ORM\Entity(repositoryClass="SequenceBundle\Repository\ContratRepository")
 */
class Contrat
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
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetimetz")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="fini", type="boolean")
     */
    private $fini;

    /**
     * @var bool
     *
     * @ORM\Column(name="enCours", type="boolean")
     */
    private $enCours;

    /**
      * @ORM\ManyToOne(targetEntity="SequenceBundle\Entity\Recompense")
      */
    private $recompense;

    /**
      * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
      */
    private $educateur;

    /**
      * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
      */
    private $enfant;

    /**
      * @ORM\ManyToOne(targetEntity="SequenceBundle\Entity\Sequence")
      */
    private $sequence;

    public function __construct()
    {
        $this->date = new \DateTime('now');
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
     * @return Contrat
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
     * Set description
     *
     * @param string $description
     * @return Contrat
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Contrat
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
     * Set enCours
     *
     * @param boolean $enCours
     * @return Contrat
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
     * Set recompense
     *
     * @param \SequenceBundle\Entity\Recompense $recompense
     * @return Contrat
     */
    public function setRecompense(\SequenceBundle\Entity\Recompense $recompense = null)
    {
        $this->recompense = $recompense;
    
        return $this;
    }

    /**
     * Get recompense
     *
     * @return \SequenceBundle\Entity\Recompense 
     */
    public function getRecompense()
    {
        return $this->recompense;
    }

    /**
     * Set educateur
     *
     * @param \UserBundle\Entity\User $educateur
     * @return Contrat
     */
    public function setEducateur(\UserBundle\Entity\User $educateur = null)
    {
        $this->educateur = $educateur;
    
        return $this;
    }

    /**
     * Get educateur
     *
     * @return \UserBundle\Entity\User 
     */
    public function getEducateur()
    {
        return $this->educateur;
    }

    /**
     * Set enfant
     *
     * @param \UserBundle\Entity\User $enfant
     * @return Contrat
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
     * @return Contrat
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
     * Set fini
     *
     * @param boolean $fini
     * @return Contrat
     */
    public function setFini($fini)
    {
        $this->fini = $fini;
    
        return $this;
    }

    /**
     * Get fini
     *
     * @return boolean 
     */
    public function getFini()
    {
        return $this->fini;
    }
}
