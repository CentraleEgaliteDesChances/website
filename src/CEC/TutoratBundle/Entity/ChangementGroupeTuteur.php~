<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChangementGroupeTuteur
 */
class ChangementGroupeTuteur
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
     * @return ChangementGroupeTuteur
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
     * @return ChangementGroupeTuteur
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
     * @var \CEC\TutoratBundle\Entity\Groupe
     */
    private $groupe;

    /**
     * @var \CEC\MembreBundle\Entity\Membre
     */
    private $tuteur;


    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return ChangementGroupeTuteur
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
     * @return ChangementGroupeTuteur
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
     * Set groupe
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupe
     * @return ChangementGroupeTuteur
     */
    public function setGroupe(\CEC\TutoratBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;
    
        return $this;
    }

    /**
     * Get groupe
     *
     * @return \CEC\TutoratBundle\Entity\Groupe 
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set tuteur
     *
     * @param \CEC\MembreBundle\Entity\Membre $tuteur
     * @return ChangementGroupeTuteur
     */
    public function setTuteur(\CEC\MembreBundle\Entity\Membre $tuteur = null)
    {
        $this->tuteur = $tuteur;
    
        return $this;
    }

    /**
     * Get tuteur
     *
     * @return \CEC\MembreBundle\Entity\Membre 
     */
    public function getTuteur()
    {
        return $this->tuteur;
    }
}