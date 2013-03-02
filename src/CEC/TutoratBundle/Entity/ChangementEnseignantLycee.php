<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChangementEnseignantLycee
 */
class ChangementEnseignantLycee
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
     * @var string
     */
    private $role;

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
     * @var \CEC\TutoratBundle\Entity\Enseignant
     */
    private $enseignant;


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
     * @return ChangementEnseignantLycee
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
     * @return ChangementEnseignantLycee
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
     * Set role
     *
     * @param string $role
     * @return ChangementEnseignantLycee
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return ChangementEnseignantLycee
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
     * @return ChangementEnseignantLycee
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
     * @return ChangementEnseignantLycee
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
     * Set enseignant
     *
     * @param \CEC\TutoratBundle\Entity\Enseignant $enseignant
     * @return ChangementEnseignantLycee
     */
    public function setEnseignant(\CEC\TutoratBundle\Entity\Enseignant $enseignant = null)
    {
        $this->enseignant = $enseignant;
    
        return $this;
    }

    /**
     * Get enseignant
     *
     * @return \CEC\TutoratBundle\Entity\Enseignant 
     */
    public function getEnseignant()
    {
        return $this->enseignant;
    }
}