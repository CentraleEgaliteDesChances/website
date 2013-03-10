<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChangementLyceeVP
 */
class ChangementLyceeVP
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
     * @return ChangementLyceeVP
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
     * @return ChangementLyceeVP
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
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateModification;

    /**
     * @var \CEC\TutoratBundle\Entity\Lycee
     */
    private $lycee;

    /**
     * @var \CEC\MembreBundle\Entity\Membre
     */
    private $VP;


    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return ChangementLyceeVP
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
     * @return ChangementLyceeVP
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
     * Set lycee
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycee
     * @return ChangementLyceeVP
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
     * Set VP
     *
     * @param \CEC\MembreBundle\Entity\Membre $vP
     * @return ChangementLyceeVP
     */
    public function setVP(\CEC\MembreBundle\Entity\Membre $vP = null)
    {
        $this->VP = $vP;
    
        return $this;
    }

    /**
     * Get VP
     *
     * @return \CEC\MembreBundle\Entity\Membre 
     */
    public function getVP()
    {
        return $this->VP;
    }
}