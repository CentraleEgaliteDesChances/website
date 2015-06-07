<?php

namespace CEC\SecteurProjetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Projet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\SecteurProjetsBundle\Entity\ProjetRepository")
 * @UniqueEntity(fields="slug", message="Deux projets ne peuvent pas avoir le même slug. Changez le nom du projet.")
 */
class Projet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
	
	/**
	* @var string
	*
	* @ORM\Column(name="slug", type="string", length=100)
	*/
	private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="description_courte", type="string", length=255)
     */
    private $description_courte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetime")
     */
    private $dateModification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     */
    private $dateFin;
	
	/**
	*@var string
	*
	* @ORM\Column(name="lieu", type="string", length=255)
	*/
	private $lieu;
	
	/**
	* @var boolean
	*
	* @ORM\Column(name="inscriptions_ouvertes", type="boolean")
	*/
	private $inscriptionsOuvertes = false;
	
	/**
	* Liste des réunions liées au projet
	* 
	* @var \CEC\SecteurProjetsBundle\Entity\Reunion
	*
	* @ORM\OneToMany(targetEntity="\CEC\SecteurProjetsBundle\Entity\Reunion", mappedBy="projet")
	*/
	private $reunions;
	
	/**
	* @var \CEC\SecteurProjetsBundle\Entity\Dossier
	*
	* @ORM\OneToOne(targetEntity="\CEC\SecteurProjetsBundle\Entity\Dossier", inversedBy="projet")
	*/
	private $dossier;
	
	/**
	* @var \Doctrine\Common\Collections\Collection
	*
	* @ORM\ManyToMany(targetEntity="\CEC\MembreBundle\Entity\Membre", inversedBy="contactProjets")
	*/
	private $contacts;
	
	/**
	* @var \Doctrine\Common\Collections\Collection
	*
	*@ORM\OneToMany(targetEntity="\CEC\SecteurProjetsBundle\Entity\Album", mappedBy="projet")
	*/
	private $albums;

    /**
    * @var \Doctrine\Common\Collections\Collection
    *
    *@ORM\OneToMany(targetEntity="\CEC\SecteurProjetsBundle\Entity\ProjetEleve", mappedBy="projet")
    */
    private $inscritsParAnnee;


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
     * Set nom
     *
     * @param string $nom
     * @return Projet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
	
	/**
     * Set slug
     *
     * @param string $nom
     * @return Projet
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Projet
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description_courte
     *
     * @param string $descriptionCourte
     * @return Projet
     */
    public function setDescriptionCourte($descriptionCourte)
    {
        $this->description_courte = $descriptionCourte;
    
        return $this;
    }

    /**
     * Get description_courte
     *
     * @return string 
     */
    public function getDescriptionCourte()
    {
        return $this->description_courte;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Projet
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
     * @return Projet
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Projet
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Projet
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    
        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
	
	/**
     * Set inscriptionsOuvertes
     *
     * @param boolean $etat
     * @return Projet
     */
    public function setInscriptionsOuvertes($etat)
    {
        $this->inscriptionsOuvertes = $etat;
    
        return $this;
    }
	
	/**
     * Set inscriptionsOuvertes
     *
     * @return Projet
     */
    public function switchInscriptionsOuvertes()
    {
        $this->inscriptionsOuvertes = !$this->inscriptionsOuvertes;
    
        return $this;
    }

    /**
     * Get inscriptionsOuvertes
     *
     * @return boolean
     */
    public function getInscriptionsOuvertes()
    {
        return $this->inscriptionsOuvertes;
    }
	
	/**
     * Get reunions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReunions()
    {
        return $this->reunions;
    }
	
	/**
     * Retourne la date de début du projet.
     *
     * @return \DateTime
     */
    public function retreiveDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Retourne la date de fin du projet.
     *
     * @return \DateTime
     */
    public function retreiveDateFin()
    {
        return $this->dateFin;
    }
	
	/**
	* Get dossier
	*
	* @return \CEC\SecteurProjetsBundle\Entity\Dossier
	*/
	public function getDossier()
	{
		return $this->dossier;
	}
	
	/**
	* Set dossier
	*
	* @var \CEC\SecteurProjetsBundle\Entity\Dossier
	* @return \CEC\SecteurProjetsBundle\Entity\Projet
	*/
	public function setDossier()
	{
		$this->dossier = $dossier;
		return $this;
	}
	
	/**
     * Add contact
     *
     * @param \CEC\MembreBundle\Entity\Membre $membre
     * @return Membre
     */
    public function addContact(\CEC\MembreBundle\Entity\Membre $membre)
    {
        $this->contacts[] = $membre;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \CEC\MembreBundle\Entity\Membre $membre
     */
    public function removeContact(\CEC\MembreBundle\Entity\Membre $membre)
    {
        $this->contacts->removeElement($membre);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }
	
	/**
     * Set lieu
	 * @var string $lieu
     *
     * @return \CEC\SecteurProjetsBundle\Entity\Projet
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
     * Constructor
     */
    public function __construct()
    {
        $this->reunions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inscritsParAnnee = new \Doctrine\Common\Collections\ArrayCollection();

    }
    
    /**
     * Add reunions
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Reunion $reunions
     * @return Projet
     */
    public function addReunion(\CEC\SecteurProjetsBundle\Entity\Reunion $reunions)
    {
        $this->reunions[] = $reunions;
    
        return $this;
    }

    /**
     * Remove reunions
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Reunion $reunions
     */
    public function removeReunion(\CEC\SecteurProjetsBundle\Entity\Reunion $reunions)
    {
        $this->reunions->removeElement($reunions);
    }
	
	/**
     * Add album
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Album $album
     * @return Membre
     */
    public function addAlbum(\CEC\SecteurProjetsBundle\Entity\Album $album)
    {
        $this->albums[] = $album;

        return $this;
    }

    /**
     * Remove album
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Album $album
     */
    public function removeAlbum(\CEC\SecteurProjetsBundle\Entity\Album $album)
    {
        $this->albums->removeElement($album);
    }

    /**
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlbums()
    {
        return $this->albums;
    }

   

    /**
     * Add inscritsParAnnee
     *
     * @param \CEC\SecteurProjetsBundle\Entity\ProjetEleve $inscritsParAnnee
     * @return Projet
     */
    public function addInscritsParAnnee(\CEC\SecteurProjetsBundle\Entity\ProjetEleve $inscritsParAnnee)
    {
        $this->inscritsParAnnee[] = $inscritsParAnnee;
    
        return $this;
    }

    /**
     * Remove inscritsParAnnee
     *
     * @param \CEC\SecteurProjetsBundle\Entity\ProjetEleve $inscritsParAnnee
     */
    public function removeInscritsParAnnee(\CEC\SecteurProjetsBundle\Entity\ProjetEleve $inscritsParAnnee)
    {
        $this->inscritsParAnnee->removeElement($inscritsParAnnee);
    }

    /**
     * Get inscritsParAnnee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInscritsParAnnee()
    {
        return $this->inscritsParAnnee;
    }
}