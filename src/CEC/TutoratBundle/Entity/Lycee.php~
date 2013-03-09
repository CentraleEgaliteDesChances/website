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
     */
    private $changementsCordee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $enseignants;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groupes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->changementsCordee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->enseignants = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add changementsCordee
     *
     * @param \CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsCordee
     * @return Lycee
     */
    public function addChangementsCordee(\CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsCordee)
    {
        $this->changementsCordee[] = $changementsCordee;
    
        return $this;
    }

    /**
     * Remove changementsCordee
     *
     * @param \CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsCordee
     */
    public function removeChangementsCordee(\CEC\TutoratBundle\Entity\ChangementCordeeLycee $changementsCordee)
    {
        $this->changementsCordee->removeElement($changementsCordee);
    }

    /**
     * Get changementsCordee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChangementsCordee()
    {
        return $this->changementsCordee;
    }

    /**
     * Add enseignants
     *
     * @param \CEC\TutoratBundle\Entity\ChangementEnseignantLycee $enseignants
     * @return Lycee
     */
    public function addEnseignant(\CEC\TutoratBundle\Entity\ChangementEnseignantLycee $enseignants)
    {
        $this->enseignants[] = $enseignants;
    
        return $this;
    }

    /**
     * Remove enseignants
     *
     * @param \CEC\TutoratBundle\Entity\ChangementEnseignantLycee $enseignants
     */
    public function removeEnseignant(\CEC\TutoratBundle\Entity\ChangementEnseignantLycee $enseignants)
    {
        $this->enseignants->removeElement($enseignants);
    }

    /**
     * Get enseignants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnseignants()
    {
        return $this->enseignants;
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
     * Get description
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . ' - ' . $this->getNom() . ' (' . $this->getVille() . ')';
    }
}