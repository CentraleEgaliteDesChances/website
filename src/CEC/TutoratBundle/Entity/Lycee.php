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
    private $zep;

    /**
     * @var boolean
     */
    private $pivot;

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
     *
     * @ORM\OneToMany(targetEntity="\CEC\MembreBundle\Entity\Professeur", mappedBy="lycee")
     */
    private $professeurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vpLycees;

    /**
     * @var \CEC\TutoratBundle\Entity\Cordee
     */
    private $cordee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groupes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enseignants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vpLycee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groupes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set zep
     *
     * @param boolean $zep
     * @return Lycee
     */
    public function setZep($zep)
    {
        $this->zep = $zep;
    
        return $this;
    }

    /**
     * Get zep
     *
     * @return boolean 
     */
    public function getZep()
    {
        return $this->zep;
    }

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
     * Add professeurs
     *
     * @param \CEC\MembreBundle\Entity\Professeur $professeurs
     * @return Lycee
     */
    public function addProfesseur(\CEC\MembreBundle\Entity\Professeur $professeurs)
    {
        $this->professeurs[] = $professeurs;
    
        return $this;
    }

    /**
     * Remove professeurs
     *
     * @param \CEC\MembreTutoratBundle\Entity\Professeur $professeurs
     */
    public function removeProfesseur(\CEC\MembreBundle\Entity\Professeur $professeurs)
    {
        $this->professeurs->removeElement($professeurs);
    }

    /**
     * Get professeurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProfesseurs()
    {
        return $this->professeurs;
    }

    /**
     * Add vpLycees
     *
     * @param \CEC\MembreBundle\Entity\Membre $vpLycees
     * @return Lycee
     */
    public function addVpLycee(\CEC\MembreBundle\Entity\Membre $vpLycees)
    {
        $this->vpLycees[] = $vpLycees;
    
        return $this;
    }

    /**
     * Remove vpLycees
     *
     * @param \CEC\MembreBundle\Entity\Membre $vpLycees
     */
    public function removeVpLycee(\CEC\MembreBundle\Entity\Membre $vpLycees)
    {
        $this->vpLycees->removeElement($vpLycees);
    }

    /**
     * Get vpLycees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVpLycees()
    {
        return $this->vpLycees;
    }

    /**
     * Set cordee
     *
     * @param \CEC\TutoratBundle\Entity\Cordee $cordee
     * @return Lycee
     */
    public function setCordee(\CEC\TutoratBundle\Entity\Cordee $cordee = null)
    {
        $this->cordee = $cordee;
    
        return $this;
    }

    /**
     * Get cordee
     *
     * @return \CEC\TutoratBundle\Entity\Cordee 
     */
    public function getCordee()
    {
        return $this->cordee;
    }

    /**
     * Add groupes
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupes
     * @return Lycee
     */
    public function addGroupe(\CEC\TutoratBundle\Entity\Groupe $groupes)
    {
        $this->groupes[] = $groupes;
    
        return $this;
    }

    /**
     * Remove groupes
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupes
     */
    public function removeGroupe(\CEC\TutoratBundle\Entity\Groupe $groupes)
    {
        $this->groupes->removeElement($groupes);
    }

    /**
     * Get groupes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupes()
    {
        return $this->groupes;
    }
    
    /**
     * Retourne la description d'un lycée
     */
    public function __toString()
    {
        
        return $this->getNom() . ' (' . $this->getVille() . ')';
    }
    
}
