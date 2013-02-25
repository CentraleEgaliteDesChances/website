<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChangementCordeeLycee
 */
class ChangementCordeeLycee
{
    const CHANGEMENT_ACTION_AJOUT       =  1;    // Ajout d'un partenariat
    const CHANGEMENT_ACTION_SUPPRESSION = -1;    // Suppression d'un partenariat

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $action;

    /**
     * @var integer
     */
    private $annee;

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateModification;

    /**
     * @var \CEC\TutoratBundle\Entity\Cordee
     */
    private $cordee;

    /**
     * @var \CEC\TutoratBundle\Entity\Lycee
     */
    private $lycee;


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
     * Set action
     *
     * @param integer $action
     * @return ChangementCordeeLycee
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return integer 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     * @return ChangementCordeeLycee
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return ChangementCordeeLycee
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
     * @return ChangementCordeeLycee
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
     * Set cordee
     *
     * @param \CEC\TutoratBundle\Entity\Cordee $cordee
     * @return ChangementCordeeLycee
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
     * @return ChangementCordeeLycee
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
}
