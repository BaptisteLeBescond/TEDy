<?php

namespace SequenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sequence
 *
 * @ORM\Table(name="sequence")
 * @ORM\Entity(repositoryClass="SequenceBundle\Repository\SequenceRepository")
 */
class Sequence
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
     * @var string
     *
     * @ORM\Column(name="musique", type="string", length=255, nullable=true)
     */
    private $musique;

    /**
      * @ORM\ManyToMany(targetEntity="SequenceBundle\Entity\Etape")
      * @ORM\OrderBy({"position" = "asc"})
      */
    private $etapes;

    /**
      * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
      */
    private $createur;

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
     * @return Sequence
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
     * @return Sequence
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
     * Set musique
     *
     * @param string $musique
     * @return Sequence
     */
    public function setMusique($musique)
    {
        $this->musique = $musique;
    
        return $this;
    }

    /**
     * Get musique
     *
     * @return string 
     */
    public function getMusique()
    {
        return $this->musique;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etapes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add etapes
     *
     * @param \SequenceBundle\Entity\Etape $etapes
     * @return Sequence
     */
    public function addEtape(\SequenceBundle\Entity\Etape $etapes)
    {
        $this->etapes[] = $etapes;
    
        return $this;
    }

    /**
     * Remove etapes
     *
     * @param \SequenceBundle\Entity\Etape $etapes
     */
    public function removeEtape(\SequenceBundle\Entity\Etape $etapes)
    {
        $this->etapes->removeElement($etapes);
    }

    /**
     * Remove all etapes
     *
     */
    public function removeAllEtape()
    {
        $this->etapes->clear();
    }

    /**
     * Get etapes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEtapes()
    {
        return $this->etapes;
    }

    /**
     * Set createur
     *
     * @param \UserBundle\Entity\User $createur
     * @return Sequence
     */
    public function setCreateur(\UserBundle\Entity\User $createur = null)
    {
        $this->createur = $createur;
    
        return $this;
    }

    /**
     * Get createur
     *
     * @return \UserBundle\Entity\User 
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    public function __toString(){
        return $this->libelle;
    }
}
