<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Membre
 */
class Membre implements UserInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var string
     */
    private $motDePasse;

    /**
     * @var integer
     */
    private $promotion;

    /**
     * @var boolean
     */
    private $actif;

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateModification;

    /**
     * @var \CEC\TutoratBundle\Entity\Lycee
     */
    private $vpLycee;

    /**
     * @var \CEC\TutoratBundle\Entity\Groupe
     */
    private $groupe;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $secteurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $seances;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->secteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set prenom
     *
     * @param string $prenom
     * @return Membre
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Membre
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
     * Set email
     *
     * @param string $email
     * @return Membre
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Membre
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
     * Set motDePasse
     *
     * @param string $motDePasse
     * @return Membre
     */
    public function setMotDePasse($motDePasse)
    {
        $this->motDePasse = $motDePasse;
    
        return $this;
    }

    /**
     * Get motDePasse
     *
     * @return string 
     */
    public function getMotDePasse()
    {
        return $this->motDePasse;
    }

    /**
     * Set promotion
     *
     * @param integer $promotion
     * @return Membre
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    
        return $this;
    }

    /**
     * Get promotion
     *
     * @return integer 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Membre
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    
        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Membre
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
     * @return Membre
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
     * Set vpLycee
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $vpLycee
     * @return Membre
     */
    public function setVpLycee(\CEC\TutoratBundle\Entity\Lycee $vpLycee = null)
    {
        $this->vpLycee = $vpLycee;
    
        return $this;
    }

    /**
     * Get vpLycee
     *
     * @return \CEC\TutoratBundle\Entity\Lycee 
     */
    public function getVpLycee()
    {
        return $this->vpLycee;
    }

    /**
     * Set groupe
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupe
     * @return Membre
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
     * Add secteurs
     *
     * @param \CEC\MembreBundle\Entity\Secteur $secteurs
     * @return Membre
     */
    public function addSecteur(\CEC\MembreBundle\Entity\Secteur $secteurs)
    {
        $this->secteurs[] = $secteurs;
    
        return $this;
    }

    /**
     * Remove secteurs
     *
     * @param \CEC\MembreBundle\Entity\Secteur $secteurs
     */
    public function removeSecteur(\CEC\MembreBundle\Entity\Secteur $secteurs)
    {
        $this->secteurs->removeElement($secteurs);
    }

    /**
     * Get secteurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSecteurs()
    {
        return $this->secteurs;
    }

    /**
     * Add seances
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seances
     * @return Membre
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
     * @ORM\PrePersist
     */
    public function setDefaultValues()
    {
        if (!isset($this->promotion)) {
            $this->setPromotion(date('Y') + 3);
        }
        if (!isset($this->actif)) {
            $this->setActif(true);
        }
    }
    
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->getMotDePasse();
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }
}
