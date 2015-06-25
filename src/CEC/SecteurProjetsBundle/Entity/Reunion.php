<?php

namespace CEC\SecteurProjetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reunion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\SecteurProjetsBundle\Entity\ReunionRepository")
 */
class Reunion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heureDebut", type="time")
     */
    private $heureDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heureFin", type="time")
     */
    private $heureFin;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
	
	/**
	* @var \CEC\MembreBundle\Entity\Eleve
	*
	* @ORM\ManyToMany(targetEntity="\CEC\MembreBundle\Entity\Eleve", inversedBy="reunions")
	*/
	private $presents;
	
	/**
	* @var \CEC\SecteurProjetsBundle\Entity\Projet
	*
	* @ORM\ManyToOne(targetEntity="\CEC\SecteurProjetsBundle\Entity\Projet", inversedBy="reunions")
	*/
	private $projet;


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
     * @return Reunion
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
     * Set date
     *
     * @param \DateTime $date
     * @return Reunion
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
     * Set heureDebut
     *
     * @param \DateTime $heureDebut
     * @return Reunion
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
     * @return Reunion
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
     * Set adresse
     *
     * @param string $adresse
     * @return Reunion
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
     * Set description
     *
     * @param string $description
     * @return Reunion
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
     * Get presents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresents()
    {
        return $this->presents;
    }
	
	/**
     * Set projet
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Projet $projet
     * @return Reunion
     */
    public function setProjet(\CEC\SecteurProjetsBundle\Entity\Projet $projet)
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return \CEC\SecteurProjetsBundle\Entity\Projet
     */
    public function getProjet()
    {
        return $this->projet;
    }
	
	/**
     * Retourne la date de début de la reunion.
     *
     * @return \DateTime
     */
    public function retreiveDateDebut()
    {
        $time = $this->getHeureDebut()->format('H:i:s');
        $date = $this->getDate()->format('Y-m-d');
        $dateDebut = new \DateTime($date . ' ' . $time);
        return $dateDebut;
    }

    /**
     * Retourne la date de fin de la reunion.
     *
     * @return \DateTime
     */
    public function retreiveDateFin()
    {
        $time = $this->getHeureFin()->format('H:i:s');
        $date = $this->getDate()->format('Y-m-d');
        $dateFin = new \DateTime($date . ' ' . $time);
        return $dateFin;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->presents = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add presents
     *
     * @param \CEC\MembreBundle\Entity\Eleve $presents
     * @return Reunion
     */
    public function addPresent(\CEC\MembreBundle\Entity\Eleve $presents)
    {
        $this->presents[] = $presents;
    
        return $this;
    }

    /**
     * Remove presents
     *
     * @param \CEC\MembreBundle\Entity\Eleve $presents
     */
    public function removePresent(\CEC\MembreBundle\Entity\Eleve $presents)
    {
        $this->presents->removeElement($presents);
    }
}