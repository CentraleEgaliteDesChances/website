<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;
use CEC\TutoratBundle\Entity\Seance;

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
    private $lyceensParAnnee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tuteursParAnnee;

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
        $this->lyceensParAnnee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tuteursParAnnee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lycees = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add seances
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seances
     * @return Groupe
     */
    public function addSeance(\CEC\TutoratBundle\Entity\Seance $seances)
    {
        $this->seances->add($seances);
    
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
     * @return array
     */
    public function getSeances()
    {
        return $this->seances->toArray();
    }

    /**
     * Get seances par année
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeancesAnnee(\CEC\MainBundle\AnneeScolaire\AnneeScolaire $annee)
    {
        $seances = $this->getSeances();

        $seances = array_filter($seances, function(Seance $s) use($annee) { return $annee->contientDate($s->getDate());});

        return $seances;
    }

    /**
     * Add tuteursParAnnee
     *
     * @param \CEC\TutoratBundle\Entity\GroupeTuteurs $tuteursParAnnee
     * @return Groupe
     */
    public function addTuteursParAnnee(\CEC\TutoratBundle\Entity\GroupeTuteurs $tuteursParAnnee)
    {
        $this->tuteursParAnnee->add($tuteursParAnnee);
    
        return $this;
    }

    /**
     * Remove tuteursParAnnee
     *
     * @param \CEC\TutoratBundle\Entity\GroupeTuteurs $tuteursParAnnee
     */
    public function removeTuteursParAnnee(\CEC\TutoratBundle\Entity\GroupeTuteurs $tuteursParAnnee)
    {
        $this->tuteursParAnnee->removeElement($tuteursParAnnee);
    }

    /**
     * Get tuteursParAnnee
     *
     * @return array
     */
    public function getTuteursParAnnee()
    {
        return $this->tuteursParAnnee->toArray();
    }

    /**
    * Retourne les tuteurs de l'année scolaire donnée en argument
    */
    public function getTuteursAnnee(\CEC\MainBundle\AnneeScolaire\AnneeScolaire $annee)
    {
        $tuteurs = $this->getTuteursParAnnee();

        $tuteurs = array_filter($tuteurs, function(GroupeTuteurs $g) use($annee) { return ($g->getAnneeScolaire()==$annee);});

        $tuteurs = array_map(function(GroupeTuteurs $g){ return $g->getTuteur();}, $tuteurs);

        return $tuteurs;
    }

    /**
     * Add lyceensParAnnee
     *
     * @param \CEC\TutoratBundle\Entity\GroupeEleves $lyceensParAnnee
     * @return Groupe
     */
    public function addLyceensParAnnee(\CEC\TutoratBundle\Entity\GroupeEleves $lyceensParAnnee)
    {
        $this->lyceensParAnnee->add($lyceensParAnnee);
    
        return $this;
    }

    /**
     * Remove lyceensParAnnee
     *
     * @param \CEC\TutoratBundle\Entity\GroupeEleves $lyceensParAnnee
     */
    public function removeLyceensParAnnee(\CEC\TutoratBundle\Entity\GroupeEleves $lyceensParAnnee)
    {
        $this->lyceensParAnnee->removeElement($lyceensParAnnee);
    }

    /**
     * Get lyceensParAnnee
     *
     * @return array 
     */
    public function getLyceensParAnnee()
    {
        return $this->lyceensParAnnee->toArray();
    }

    /**
    * Retourne les lyceens de l'année scolaire donnée en argument
    */
    public function getLyceensAnnee(\CEC\MainBundle\AnneeScolaire\AnneeScolaire $annee)
    {
        $lyceens = $this->getLyceensParAnnee();

        $lyceens = array_filter($lyceens, function(GroupeEleves $g) use($annee) { return ($g->getAnneeScolaire()==$annee);});

        $lyceens = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceens);

        return $lyceens;
    }

    /**
     * Add lycees
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycees
     * @return Groupe
     */
    public function addLycee(\CEC\TutoratBundle\Entity\Lycee $lycees)
    {
        $this->lycees->add($lycees);
    
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
     * @return array
     */
    public function getLycees()
    {
        return $this->lycees->toArray();
    }
}
