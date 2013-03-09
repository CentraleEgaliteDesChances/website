<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChangementGroupeLyceen
 */
class ChangementGroupeLyceen
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
     * @return ChangementGroupeLyceen
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
     * @return ChangementGroupeLyceen
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
     * @return ChangementGroupeLyceen
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
     * @return ChangementGroupeLyceen
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
     * @var string
     */
    private $manyToOne;

    /**
     * @var string
     */
    private $lifecycleCallbacks;


    /**
     * Set manyToOne
     *
     * @param string $manyToOne
     * @return ChangementGroupeLyceen
     */
    public function setManyToOne($manyToOne)
    {
        $this->manyToOne = $manyToOne;
    
        return $this;
    }

    /**
     * Get manyToOne
     *
     * @return string 
     */
    public function getManyToOne()
    {
        return $this->manyToOne;
    }

    /**
     * Set lifecycleCallbacks
     *
     * @param string $lifecycleCallbacks
     * @return ChangementGroupeLyceen
     */
    public function setLifecycleCallbacks($lifecycleCallbacks)
    {
        $this->lifecycleCallbacks = $lifecycleCallbacks;
    
        return $this;
    }

    /**
     * Get lifecycleCallbacks
     *
     * @return string 
     */
    public function getLifecycleCallbacks()
    {
        return $this->lifecycleCallbacks;
    }
    /**
     * @var \CEC\TutoratBundle\Entity\Groupe
     */
    private $groupe;

    /**
     * @var \CEC\TutoratBundle\Entity\Lyceen
     */
    private $lyceen;


    /**
     * Set groupe
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupe
     * @return ChangementGroupeLyceen
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
     * Set lyceen
     *
     * @param \CEC\TutoratBundle\Entity\Lyceen $lyceen
     * @return ChangementGroupeLyceen
     */
    public function setLyceen(\CEC\TutoratBundle\Entity\Lyceen $lyceen = null)
    {
        $this->lyceen = $lyceen;
    
        return $this;
    }

    /**
     * Get lyceen
     *
     * @return \CEC\TutoratBundle\Entity\Lyceen 
     */
    public function getLyceen()
    {
        return $this->lyceen;
    }
}