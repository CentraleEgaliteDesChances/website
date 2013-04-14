<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cordee
 */
class Cordee
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateModification;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lycees;

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
     * Add lycees
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycees
     * @return Cordee
     */
    public function addLycee(\CEC\TutoratBundle\Entity\Lycee $lycees)
    {
        $this->lycees[] = $lycees;
    
        return $this;
    }

    /**
     * Remove lycees
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycees
     */
    public function removeLycee(\CEC\TutoratBundle\Entity\Lycee $lycees)
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
     * Returns true if has at least one lycee
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->getLycees()->count() > 0;
    }
}
