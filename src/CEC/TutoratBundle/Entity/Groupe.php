<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 */
class Groupe
{
     /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $niveau;

    /**
     * @var string
     */
    private $typeDeTutorat;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $seances;

    /**
     * @var \CEC\TutoratBundle\Entity\Lycee
     */
    private $lycee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lyceens;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $membres;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lyceens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set niveau
     *
     * @param string $niveau
     * @return Groupe
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    
        return $this;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set typeDeTutorat
     *
     * @param string $typeDeTutorat
     * @return Groupe
     */
    public function setTypeDeTutorat($typeDeTutorat)
    {
        $this->typeDeTutorat = $typeDeTutorat;
    
        return $this;
    }

    /**
     * Get typeDeTutorat
     *
     * @return string 
     */
    public function getTypeDeTutorat()
    {
        return $this->typeDeTutorat;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Groupe
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
     * @return Groupe
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
     * @return Groupe
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
     * Set annee
     *
     * @param integer $annee
     * @return Groupe
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
     * @return Groupe
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
     * @return Groupe
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
     * Add seances
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seances
     * @return Groupe
     */
    public function addSeance(\CEC\TutoratBundle\Entity\Seance $seances)
    {
        $this->seances[] = $seances;
    
        return $this;
    }

    /**
     * Remove seances
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seances
     */
    public function removeSeance(\CEC\TutoratBundle\Entity\Seance $seances)
    {
        $this->seances->removeElement($seances);
    }

    /**
     * Get seances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeances()
    {
        return $this->seances;
    }

    /**
     * Set lycee
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycee
     * @return Groupe
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
     * Add lyceens
     *
     * @param \CEC\TutoratBundle\Entity\Lyceen $lyceens
     * @return Groupe
     */
    public function addLyceen(\CEC\TutoratBundle\Entity\Lyceen $lyceens)
    {
        $this->lyceens[] = $lyceens;
    
        return $this;
    }

    /**
     * Remove lyceens
     *
     * @param \CEC\TutoratBundle\Entity\Lyceen $lyceens
     */
    public function removeLyceen(\CEC\TutoratBundle\Entity\Lyceen $lyceens)
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

    /**
     * Add membres
     *
     * @param \CEC\MembreBundle\Entity\Membre $membres
     * @return Groupe
     */
    public function addMembre(\CEC\MembreBundle\Entity\Membre $membres)
    {
        $this->membres[] = $membres;
    
        return $this;
    }

    /**
     * Remove membres
     *
     * @param \CEC\MembreBundle\Entity\Membre $membres
     */
    public function removeMembre(\CEC\MembreBundle\Entity\Membre $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembres()
    {
        return $this->membres;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tuteurs;


    /**
     * Add tuteurs
     *
     * @param \CEC\TutoratBundle\Entity\ChangementGroupeTuteur $tuteurs
     * @return Groupe
     */
    public function addTuteur(\CEC\TutoratBundle\Entity\ChangementGroupeTuteur $tuteurs)
    {
        $this->tuteurs[] = $tuteurs;
    
        return $this;
    }

    /**
     * Remove tuteurs
     *
     * @param \CEC\TutoratBundle\Entity\ChangementGroupeTuteur $tuteurs
     */
    public function removeTuteur(\CEC\TutoratBundle\Entity\ChangementGroupeTuteur $tuteurs)
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
     * Add lycee
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycee
     * @return Groupe
     */
    public function addLycee(\CEC\TutoratBundle\Entity\Lycee $lycee)
    {
        $this->lycee[] = $lycee;
    
        return $this;
    }

    /**
     * Remove lycee
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycee
     */
    public function removeLycee(\CEC\TutoratBundle\Entity\Lycee $lycee)
    {
        $this->lycee->removeElement($lycee);
    }
    /**
     * @var string
     */
    private $rendezVous;


    /**
     * Set rendezVous
     *
     * @param string $rendezVous
     * @return Groupe
     */
    public function setRendezVous($rendezVous)
    {
        $this->rendezVous = $rendezVous;
    
        return $this;
    }

    /**
     * Get rendezVous
     *
     * @return string 
     */
    public function getRendezVous()
    {
        return $this->rendezVous;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lycees;


    /**
     * Get lycees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLycees()
    {
        return $this->lycees;
    }
}