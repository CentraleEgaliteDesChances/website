<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CordeeLyceeReference
 */
class CordeeLyceeReference
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $annee;


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
     * Set annee
     *
     * @param integer $annee
     * @return CordeeLyceeReference
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }
    /**
     * @var \CEC\TutoratBundle\Entity\Cordee
     */
    private $cordee;

    /**
     * @var \CEC\TutoratBundle\Entity\Lycee
     */
    private $lycee;


    /**
     * Set id
     *
     * @param integer $id
     * @return CordeeLyceeReference
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Set cordee
     *
     * @param \CEC\TutoratBundle\Entity\Cordee $cordee
     * @return CordeeLyceeReference
     */
    public function setCordee(\CEC\TutoratBundle\Entity\Cordee $cordee = null)
    {
        $this->cordee = $cordee;
    
        return $this;
    }

    /**
     * Get cordee
     *
     * @return \CEC\TutoratBundle\Entity\Cordee 
     */
    public function getCordee()
    {
        return $this->cordee;
    }

    /**
     * Set lycee
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycee
     * @return CordeeLyceeReference
     */
    public function setLycee(\CEC\TutoratBundle\Entity\Lycee $lycee = null)
    {
        $this->lycee = $lycee;
    
        return $this;
    }

    /**
     * Get lycee
     *
     * @return \CEC\TutoratBundle\Entity\Lycee 
     */
    public function getLycee()
    {
        return $this->lycee;
    }
    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateModification;


    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return CordeeLyceeReference
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
     * @return CordeeLyceeReference
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
}