<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Eleve
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\MembreBundle\Entity\EleveRepository")
 */
class Eleve
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
     * @ORM\Column(name="prenom", type="string", length=100)
	 * @Assert\NotBlank()
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
	 * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=200)
	 * @Assert\NotBlank()
	 * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="lycee", type="string", length=50)
	 * @Assert\NotBlank()
     */
    private $lycee;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=10
	 * @Assert\NotBlank()
     */
    private $classe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datenaiss", type="date")
	 * @Assert\NotBlank()
     */
    private $datenaiss;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

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
     * @return Eleve
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
     * @return Eleve
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
     * @return Eleve
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
     * Set lycee
     *
     * @param string $lycee
     * @return Eleve
     */
    public function setLycee($lycee)
    {
        $this->lycee = $lycee;
    
        return $this;
    }

    /**
     * Get lycee
     *
     * @return string 
     */
    public function getLycee()
    {
        return $this->lycee;
    }

    /**
     * Set classe
     *
     * @param string $classe
     * @return Eleve
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;
    
        return $this;
    }

    /**
     * Get classe
     *
     * @return string 
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set datenaiss
     *
     * @param \DateTime $datenaiss
     * @return Eleve
     */
    public function setDatenaiss($datenaiss)
    {
        $this->datenaiss = $datenaiss;
    
        return $this;
    }

    /**
     * Get datenaiss
     *
     * @return \DateTime 
     */
    public function getDatenaiss()
    {
        return $this->datenaiss;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return Eleve
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Eleve
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
     * @return Eleve
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
}
