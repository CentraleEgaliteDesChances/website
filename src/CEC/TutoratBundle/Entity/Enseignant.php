<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enseignant
 */
class Enseignant
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
    private $telephoneFixe;

    /**
     * @var string
     */
    private $telephonePortable;

    /**
     * @var string
     */
    private $commentaires;

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
     * Set prenom
     *
     * @param string $prenom
     * @return Enseignant
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
     * @return Enseignant
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
     * @return Enseignant
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
     * Set telephoneFixe
     *
     * @param string $telephoneFixe
     * @return Enseignant
     */
    public function setTelephoneFixe($telephoneFixe)
    {
        $this->telephoneFixe = $telephoneFixe;
    
        return $this;
    }

    /**
     * Get telephoneFixe
     *
     * @return string 
     */
    public function getTelephoneFixe()
    {
        return $this->telephoneFixe;
    }

    /**
     * Set telephonePortable
     *
     * @param string $telephonePortable
     * @return Enseignant
     */
    public function setTelephonePortable($telephonePortable)
    {
        $this->telephonePortable = $telephonePortable;
    
        return $this;
    }

    /**
     * Get telephonePortable
     *
     * @return string 
     */
    public function getTelephonePortable()
    {
        return $this->telephonePortable;
    }

    /**
     * Set commentaires
     *
     * @param string $commentaires
     * @return Enseignant
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    
        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Enseignant
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
     * @return Enseignant
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
     * Set id
     *
     * @param integer $id
     * @return Enseignant
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lycees;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lycees = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add lycees
     *
     * @param \CEC\TutoratBundle\Entity\EnseignantLyceeReference $lycees
     * @return Enseignant
     */
    public function addLycee(\CEC\TutoratBundle\Entity\EnseignantLyceeReference $lycees)
    {
        $this->lycees[] = $lycees;
    
        return $this;
    }

    /**
     * Remove lycees
     *
     * @param \CEC\TutoratBundle\Entity\EnseignantLyceeReference $lycees
     */
    public function removeLycee(\CEC\TutoratBundle\Entity\EnseignantLyceeReference $lycees)
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