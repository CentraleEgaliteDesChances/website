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
    private $date;

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
     * @var \CEC\TutoratBundle\Entity\Groupe
     */
    private $groupe;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tuteurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lyceens;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $compteRendus;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tuteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lyceens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activites = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Retourne les lycées du groupe de tutorat associé à la séance.
     * Si la séance n'est associé à aucun groupe, on renvoit un ArrayCollection vide.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection()
     */
    public function retreiveLycees()
    {
        if ($this->getGroupe())
        {
            $lycees = $this->getGroupe()->getLycees();
        } else {
            $lycees = new \Doctrine\Common\Collections\ArrayCollection();
        }
        return $lycees;
    }
    
    /**
     * Retourne le lieu de la séance de tutorat, qu'il soit spécifique ou commun au groupe.
     * Si la séance n'est associé à aucun groupe et qu'aucun lieu n'est spécifié, 
     * on renvoit null.
     *
     * @return string
     */
    public function retreiveLieu()
    {
        if (!$lieu = $this->getLieu())
        {
            if ($this->getGroupe())
            {
                $lieu = $this->getGroupe()->getLieu();
            } else {
                $lieu = null;
            }
        }
        return $lieu;
    }
    
    /**
     * Retourne le début de la séance de tutorat, qu'il soit spécifique ou commun au groupe.
     * Si la séance n'est associé à aucun groupe et qu'aucun début n'est spécifié, 
     * on renvoit null.
     *
     * @return \DateTime
     */
    public function retreiveDebut()
    {
        if (!$debut = $this->getDebut())
        {
            if ($this->getGroupe())
            {
                $debut = $this->getGroupe()->getDebut();
            } else {
                $debut = null;
            }
        }
        return $debut;
    }
    
    /**
     * Retourne la fin de la séance de tutorat, qu'elle soit spécifique ou commun au groupe.
     * Si la séance n'est associé à aucun groupe et qu'aucune fin n'est spécifiée, 
     * on renvoit null.
     *
     * @return \DateTime
     */
    public function retreiveFin()
    {
        if (!$fin = $this->getFin())
        {
            if ($this->getGroupe())
            {
                $fin = $this->getGroupe()->getFin();
            } else {
                $fin = null;
            }
        }
        return $fin;
    }
    
    /**
     * Retourne le lieu de rendez-vous de la séance, qu'il soit spécifique ou commun au groupe.
     * Si la séance n'est associé à aucun groupe et qu'aucun lieu n'est spécifié, 
     * on renvoit null.
     *
     * @return string
     */
    public function retreiveRendezVous()
    {
        if (!$rendezVous = $this->getRendezVous())
        {
            if ($this->getGroupe())
            {
                $rendezVous = $this->getGroupe()->getRendezVous();
            } else {
                $rendezVous = null;
            }
        }
        return $rendezVous;
    }
    
    /**
     * Retourne la date de début de la séance.
     *
     * @return \DateTime
     */
    public function retreiveDateDebut()
    {
        $time = $this->retreiveDebut()->format('H:i:s');
        $date = $this->getDate()->format('Y-m-d');
        $dateDebut = new \DateTime($date . ' ' . $time);
        return $dateDebut;
    }
    
    /**
     * Retourne la date de fin de la séance.
     *
     * @return \DateTime
     */
    public function retreiveDateFin()
    {
        $time = $this->retreiveFin()->format('H:i:s');
        $date = $this->getDate()->format('Y-m-d');
        $dateFin = new \DateTime($date . ' ' . $time);
        return $dateFin;
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
     * Set date
     *
     * @param \DateTime $date
     * @return Seance
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
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
     * Set rendezVous
     *
     * @param string $rendezVous
     * @return Seance
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
     * Add lyceens
     *
     * @param \CEC\MembreBundle\Entity\Eleve $lyceens
     * @return Seance
     */
    public function addLyceen(\CEC\MembreBundle\Entity\Eleve $lyceens)
    {
        $this->lyceens[] = $lyceens;
    
        return $this;
    }

    /**
     * Remove lyceens
     *
     * @param \CEC\MembreBundle\Entity\Eleve $lyceens
     */
    public function removeLyceen(\CEC\MembreBundle\Entity\Eleve $lyceens)
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
     * Add compteRendus
     *
     * @param \CEC\ActiviteBundle\Entity\CompteRendu $compteRendus
     * @return Seance
     */
    public function addCompteRendu(\CEC\ActiviteBundle\Entity\CompteRendu $compteRendus)
    {
        $this->compteRendus[] = $compteRendus;
    
        return $this;
    }

    /**
     * Remove compteRendus
     *
     * @param \CEC\ActiviteBundle\Entity\CompteRendu $compteRendus
     */
    public function removeCompteRendu(\CEC\ActiviteBundle\Entity\CompteRendu $compteRendus)
    {
        $this->compteRendus->removeElement($compteRendus);
    }

    /**
     * Get compteRendus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompteRendus()
    {
        return $this->compteRendus;
    }
}