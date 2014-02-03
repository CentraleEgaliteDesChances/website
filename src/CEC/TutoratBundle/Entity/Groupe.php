<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

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
     * @var CEC\MainBundle\Utility\AnneScolaire
     */
    private $anneeScolaire;

    /**
     * @var string
     */
    private $rendezVous;

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
    private $lyceens;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tuteurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $seances;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lycees;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lyceens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tuteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lycees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->anneeScolaire = AnneeScolaire::withDate();
    }
    
    /**
     * Retourne la description des lycées composant le groupe de tutorat.
     *
     * @return string
     */
    public function getLyceesDescription()
    {
        $description = '';
        foreach ($this->getLycees() as $lycee)
        {
            if ($description != '') $description .= ' & ';
            $description .= $lycee->getNom();
            $description .= ' '.$lycee->getVille();
        }
        return $description;
    }
    
    /**
     * Retourne la description du groupe, composé de la description des lycées
     * et du niveau entre parenthèses.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->__toString();
    }
    
    /**
     * Retourne la description d'un groupe de tutorat
     */
    public function __toString()
    {
        $description = $this->getLyceesDescription();
        $description .= ' (' . $this->getNiveau() . ')';
        return $description;
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
     * Set anneeScolaire
     *
     * @param \CEC\MainBundle\AnneeScolaire\AnneeScolaire $anneeScolaire
     * @return Groupe
     */
    public function setAnneeScolaire(\CEC\MainBundle\AnneeScolaire\AnneeScolaire $anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;
    
        return $this;
    }

    /**
     * Get anneeScolaire
     *
     * @return \CEC\MainBundle\AnneeScolaire\AnneeScolaire
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

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
     * Add tuteurs
     *
     * @param \CEC\MembreBundle\Entity\Membre $tuteurs
     * @return Groupe
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
     * Add lycees
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycees
     * @return Groupe
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
}