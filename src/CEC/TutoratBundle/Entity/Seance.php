<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seance
 */
class Seance
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $lieu;

    /**
     * @var \DateTime
     */
    private $debut;

    /**
     * @var \DateTime
     */
    private $fin;

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
     * Set lieu
     *
     * @param string $lieu
     * @return Seance
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    
        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set debut
     *
     * @param \DateTime $debut
     * @return Seance
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;
    
        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     * @return Seance
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
    
        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime 
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Seance
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
     * @return Seance
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
     * @var \CEC\TutoratBundle\Entity\Groupe
     */
    private $groupe;


    /**
     * Set id
     *
     * @param integer $id
     * @return Seance
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Set groupe
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupe
     * @return Seance
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tuteurs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tuteurs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add tuteurs
     *
     * @param \CEC\MembreBundle\Entity\Membre $tuteurs
     * @return Seance
     */
    public function addTuteur(\CEC\MembreBundle\Entity\Membre $tuteurs)
    {
        $this->tuteurs[] = $tuteurs;
    
        return $this;
    }

    /**
     * Remove tuteurs
     *
     * @param \CEC\MembreBundle\Entity\Membre $tuteurs
     */
    public function removeTuteur(\CEC\MembreBundle\Entity\Membre $tuteurs)
    {
        $this->tuteurs->removeElement($tuteurs);
    }

    /**
     * Get tuteurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTuteurs()
    {
        return $this->tuteurs;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lyceens;


    /**
     * Add lyceens
     *
     * @param \CEC\TutoratBundle\Entity\Lyceens $lyceens
     * @return Seance
     */
    public function addLyceen(\CEC\TutoratBundle\Entity\Lyceens $lyceens)
    {
        $this->lyceens[] = $lyceens;
    
        return $this;
    }

    /**
     * Remove lyceens
     *
     * @param \CEC\TutoratBundle\Entity\Lyceens $lyceens
     */
    public function removeLyceen(\CEC\TutoratBundle\Entity\Lyceens $lyceens)
    {
        $this->lyceens->removeElement($lyceens);
    }

    /**
     * Get lyceens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLyceens()
    {
        return $this->lyceens;
    }
}