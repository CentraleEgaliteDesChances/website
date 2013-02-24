<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cordee: Represents a cordée de la Réussite which is leading or has led
 * a partnership with CEC.
 */
class Cordee
{
    /**
     * @var integer
     */
    private $id;

    /**
     * Cordee's name
     * @var string
     */
    private $nom;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lycees;

    /**
     * @var \DateTime
     */
    private $dateCreation;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lycees = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Cordee
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Cordee
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
    
    /**
     * Set id
     *
     * @param integer $id
     * @return Cordee
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Add a lycee
     *
     * @param \CEC\TutoratBundle\Entity\CordeeLyceeReference $lycees
     * @return Cordee
     */
    public function addLycee(\CEC\TutoratBundle\Entity\CordeeLyceeReference $lycees)
    {
        $this->lycees[] = $lycees;
    
        return $this;
    }

    /**
     * Remove a lycee
     *
     * @param \CEC\TutoratBundle\Entity\CordeeLyceeReference $lycees
     */
    public function removeLycee(\CEC\TutoratBundle\Entity\CordeeLyceeReference $lycees)
    {
        $this->lycees->removeElement($lycees);
    }

    /**
     * Get lycees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLycees()
    {
        return $this->lycees;
    }
    /**
     * @var \DateTime
     */
    private $dateModification;


    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Cordee
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }
    
    /**
     * Returns a string description
     *
     * @return string
     */
    public function __toString() {
        return $this->getId() . '-' . $this->getNom();
    }
}