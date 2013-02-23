<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lycee
 */
class Lycee
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $adresse;

    /**
     * @var integer
     */
    private $codePostal;

    /**
     * @var string
     */
    private $ville;

    /**
     * @var string
     */
    private $statut;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var boolean
     */
    private $ZEP;

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
     * Set nom
     *
     * @param string $nom
     * @return Lycee
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
     * Set adresse
     *
     * @param string $adresse
     * @return Lycee
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
     * Set codePostal
     *
     * @param integer $codePostal
     * @return Lycee
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
    
        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Lycee
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    
        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Lycee
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Lycee
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set ZEP
     *
     * @param boolean $zEP
     * @return Lycee
     */
    public function setZEP($zEP)
    {
        $this->ZEP = $zEP;
    
        return $this;
    }

    /**
     * Get ZEP
     *
     * @return boolean 
     */
    public function getZEP()
    {
        return $this->ZEP;
    }
    public function isZEP() { return $this->getZEP(); }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Lycee
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
     * @return Lycee
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
     * @var \CEC\TutoratBundle\Entity\CordeeLyceeReference
     */
    private $cordees;


    /**
     * Set id
     *
     * @param integer $id
     * @return Lycee
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Set cordees
     *
     * @param \CEC\TutoratBundle\Entity\CordeeLyceeReference $cordees
     * @return Lycee
     */
    public function setCordees(\CEC\TutoratBundle\Entity\CordeeLyceeReference $cordees = null)
    {
        $this->cordees = $cordees;
    
        return $this;
    }

    /**
     * Get cordees
     *
     * @return \CEC\TutoratBundle\Entity\CordeeLyceeReference 
     */
    public function getCordees()
    {
        return $this->cordees;
    }
    /**
     * @var \CEC\TutoratBundle\Entity\EnseignantLyceeReference
     */
    private $enseignants;


    /**
     * Set enseignants
     *
     * @param \CEC\TutoratBundle\Entity\EnseignantLyceeReference $enseignants
     * @return Lycee
     */
    public function setEnseignants(\CEC\TutoratBundle\Entity\EnseignantLyceeReference $enseignants = null)
    {
        $this->enseignants = $enseignants;
    
        return $this;
    }

    /**
     * Get enseignants
     *
     * @return \CEC\TutoratBundle\Entity\EnseignantLyceeReference 
     */
    public function getEnseignants()
    {
        return $this->enseignants;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cordees = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add cordees
     *
     * @param \CEC\TutoratBundle\Entity\CordeeLyceeReference $cordees
     * @return Lycee
     */
    public function addCordee(\CEC\TutoratBundle\Entity\CordeeLyceeReference $cordees)
    {
        $this->cordees[] = $cordees;
    
        return $this;
    }

    /**
     * Remove cordees
     *
     * @param \CEC\TutoratBundle\Entity\CordeeLyceeReference $cordees
     */
    public function removeCordee(\CEC\TutoratBundle\Entity\CordeeLyceeReference $cordees)
    {
        $this->cordees->removeElement($cordees);
    }

    /**
     * Add enseignants
     *
     * @param \CEC\TutoratBundle\Entity\EnseignantLyceeReference $enseignants
     * @return Lycee
     */
    public function addEnseignant(\CEC\TutoratBundle\Entity\EnseignantLyceeReference $enseignants)
    {
        $this->enseignants[] = $enseignants;
    
        return $this;
    }

    /**
     * Remove enseignants
     *
     * @param \CEC\TutoratBundle\Entity\EnseignantLyceeReference $enseignants
     */
    public function removeEnseignant(\CEC\TutoratBundle\Entity\EnseignantLyceeReference $enseignants)
    {
        $this->enseignants->removeElement($enseignants);
    }
    /**
     * @var boolean
     */
    private $pivot;


    /**
     * Set pivot
     *
     * @param boolean $pivot
     * @return Lycee
     */
    public function setPivot($pivot)
    {
        $this->pivot = $pivot;
    
        return $this;
    }

    /**
     * Get pivot
     *
     * @return boolean 
     */
    public function getPivot()
    {
        return $this->pivot;
    }
    public function isPivot() { return $this->getPivot(); }
}