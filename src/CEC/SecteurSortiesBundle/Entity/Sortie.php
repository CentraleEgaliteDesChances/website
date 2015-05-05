<?php

namespace CEC\SecteurSortiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * Représente une sortie prévue et organisée par le secteur sortie.
 *
 * Cette entité permet de lister toutes les sorties qui ont étés faites et qui sont à venir.
 *
 * Une sortie est identifié par son nom qui doit être unique.
 *
 * @author Corentin Bertrand
 * @version 1.0
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CEC\SecteurSortiesBundle\Entity\SortieRepository")
 * @UniqueEntity(
 *     fields = "nom",
 *     message = "Une sortie possédant ce nom existe déjà."
 * )
 */
class Sortie
{
    /**
     * @var integer
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * Nom de la sortie.
     * Le nom est requis, unique, et ne peut excéder 100 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "nom", type = "string", length = 100)
     * @Assert\NotBlank(message = "Le nom de la sortie ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le nom de la sortie ne peut excéder 100 caractères."
     * )
     */
    private $nom;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateCreation", type = "datetime")
     * @Gedmo\Timestampable(on = "create")
     * @Assert\DateTime()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateModification", type = "datetime")
     * @Gedmo\Timestampable(on = "update")
     * @Assert\DateTime()
     */
    private $dateModification;

    /**
     * Adresse de rendez-vous de la sortie
     *
     * @var string
     *
     * @ORM\Column(name = "adresse", type = "string", length = 100)
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "L'adresse ne peut excéder 100 caractères."
     * )
     */
    private $adresse;

    /**
     * Année scolaire de la sortie
     *
     * @var CEC\MainBundle\Utility\AnneScolaire
     *
     * @ORM\Column(name = "anneeScolaire", type = "anneescolaire")
     */
    private $anneeScolaire;

    /**
     * Date prévue de la sortie
     *
     * @var \Date
     *
     * @ORM\Column(name = "dateSortie", type = "date")
     * @Assert\Date()
     */
    private $dateSortie;

    /**
     * Heure de rendez-vous
     *
     * @var \Time
     *
     * @ORM\Column(name = "heureDebut", type = "time")
     * @Assert\Time()
     */
    private $heureDebut;

    /**
     * Heure approximative de fin
     *
     * @var \Time
     *
     * @ORM\Column(name = "heureFin", type = "time")
     * @Assert\Time()
     */
    private $heureFin;

    /**
     * Description de la sortie
     *
     * @var string
     *
     * @ORM\Column(name = "description", type = "string", length = 800)
     * @Assert\MaxLength(
     *     limit = 800,
     *     message = "La description ne peut excéder 800 caractères."
     * )
     */
    private $description;
	
	/**
	* Nombre de places disponibles
	*
	* @var integer
	* 
	* @ORM\Column(name="placeslimitees", type = "integer")
	*/
	private $places = 0;
	
	/**
	* Lycéens ayant participé à la sortie
	*
	* * @ORM\ManyToMany(targetEntity = "CEC\MembreBundle\Entity\Eleve" )
	* @ORM\JoinTable(name="eleves_sorties",
    *      joinColumns={@ORM\JoinColumn(name="sortie_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="eleve_id", referencedColumnName="id")}
    *      )
    */
	private $lyceens = array();

    /**
     * Nombre de lycéens ayant finalement participas à la sortie
     *
     * @var integer
     *
     * @ORM\Column(name = "nbLyceens", type = "integer", nullable=true)
     */
    private $nbLyceens;

    /**
     * Nombre de tuteurs ayant finalement accompagné la sortie
     *
     * @var integer
     *
     * @ORM\Column(name = "nbTuteurs", type = "integer", nullable=true)
     */
    private $nbTuteurs;

    /**
     * Commentaire sur le déroulement de la sortie
     *
     * @var string
     *
     * @ORM\Column(name = "commentaire", type = "string", length = 800, nullable=true)
     * @Assert\MaxLength(
     *     limit = 800,
     *     message = "Le commentaire ne peut excéder 800 caractères."
     * )
     */
    private $commentaire;

    /**
     * Prix total et final de la sortie pour CEC
     *
     * @var integer
     *
     * @ORM\Column(name = "prix", type = "integer", nullable=true)
     */
    private $prix;

    /**
     * True si le compte rendu a été fait, même partiellement.
     * Le compte rendu concerne pour l'instant nbLyceens, nbTuteurs, commentaire et prix.
     *
     * @var boolean
     *
     * @ORM\Column(name = "okCr", type = "integer")
     */
    private $okCR;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->okCR = 0;
        $this->anneeScolaire = AnneeScolaire::withDate();
    }


    /**
     * Retourne la date de début de la sortie.
     *
     * @return \DateTime
     */
    public function retreiveDateDebut()
    {
        $time = $this->getHeureDebut()->format('H:i:s');
        $date = $this->getDateSortie()->format('Y-m-d');
        $dateDebut = new \DateTime($date . ' ' . $time);
        return $dateDebut;
    }

    /**
     * Retourne la date de fin de la sortie.
     *
     * @return \DateTime
     */
    public function retreiveDateFin()
    {
        $time = $this->getHeureFin()->format('H:i:s');
        $date = $this->getDateSortie()->format('Y-m-d');
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
     * Set nom
     *
     * @param string $nom
     * @return Sortie
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Sortie
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
     * @return Sortie
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
     * Set adresse
     *
     * @param string $adresse
     * @return Sortie
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set anneeScolaire
     *
     * @param \CEC\MainBundle\AnneeScolaire\AnneeScolaire $anneeScolaire
     * @return Sortie
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
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     * @return Sortie
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set heureDebut
     *
     * @param \DateTime $heureDebut
     * @return Sortie
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return \DateTime
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set heureFin
     *
     * @param \DateTime $heureFin
     * @return Sortie
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    /**
     * Get heureFin
     *
     * @return \DateTime
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Sortie
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
     * Add lycéens
     *
     * @param \CEC\MembreBundle\Entity\Eleve $lyceen
     * @return Seance
     */
    public function addLyceen(\CEC\MembreBundle\Entity\Eleve $lyceen)
    {
        $this->lyceens[] = $lyceen;
    
        return $this;
    }

    /**
     * Remove lycéens
     *
     * @param \CEC\MembreBundle\Entity\Eleve $lyceen
     */
    public function removeLyceen(\CEC\MembreBundle\Entity\Eleve $lyceen)
    {
        $this->lyceens->removeElement($lyceen);
    }

    /**
     * Get lycéens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLyceens()
    {
        return $this->lyceens;
    }
	
    /**
     * Set nbLyceens
     *
     * @param integer $nbLyceens
     * @return Sortie
     */
    public function setNbLyceens($nbLyceens)
    {
        $this->nbLyceens = $nbLyceens;

        return $this;
    }

    /**
     * Get nbLyceens
     *
     * @return integer
     */
    public function getNbLyceens()
    {
        return $this->nbLyceens;
    }

    /**
     * Set nbTuteurs
     *
     * @param integer $nbTuteurs
     * @return Sortie
     */
    public function setNbTuteurs($nbTuteurs)
    {
        $this->nbTuteurs = $nbTuteurs;

        return $this;
    }

    /**
     * Get nbTuteurs
     *
     * @return integer
     */
    public function getNbTuteurs()
    {
        return $this->nbTuteurs;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Sortie
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     * @return Sortie
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set okCR
     *
     * @param integer $okCR
     * @return Sortie
     */
    public function setOkCR($okCR)
    {
        $this->okCR = $okCR;

        return $this;
    }

    /**
     * Get okCR
     *
     * @return integer
     */
    public function getOkCR()
    {
        return $this->okCR;
    }
	
	public function setPlaces($places)
	{
		$this->places = $places;
		
		return $places;
	}
	
	public function getPlaces()
	{
		return $this->places;
	}
	
}
