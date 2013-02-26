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
    private $changementsLycee;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->changementsLycee = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add changementsLycee
     *
     * @param \CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsLycee
     * @return Cordee
     */
    public function addChangementsLycee(\CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsLycee)
    {
        $this->changementsLycee[] = $changementsLycee;
    
        return $this;
    }

    /**
     * Remove changementsLycee
     *
     * @param \CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsLycee
     */
    public function removeChangementsLycee(\CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsLycee)
    {
        $this->changementsLycee->removeElement($changementsLycee);
    }

    /**
     * Get changementsLycee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChangementsLycee()
    {
        return $this->changementsLycee;
    }
    
    /**
     * Get description
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . ' - ' . $this->getNom();
    }
}