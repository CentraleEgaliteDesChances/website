<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Professeur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\MembreBundle\Entity\ProfesseurRepository")
 * @UniqueEntity(fields="mail", message="Email already taken")
 */
class Professeur implements UserInterface, \Serializable
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
	 * @Assert\NotBlank(message = "Le prénom ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le prénom ne peut excéder 100 caractères."
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
	 * @Assert\NotBlank(message = "Le nom de famille ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le nom de famille ne peut excéder 100 caractères."
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=150)
	 * @Assert\Email(
     *     message = "L'adresse email n'est pas valide.",
     *     checkHost = true
     * )
     * @Assert\NotBlank(message = "L'adresse email ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "L'adresse email ne peut excéder 255 caractères."
     * )
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="lycee", type="string", length=20)
     */
    private $lycee;

    /**
     * @var string
     *
     * @ORM\Column(name = "telephone", type = "string", length = 15, nullable = true)
     * @Assert\Regex(
     *     pattern = "/^((0[1-7] ?)|\+33 ?[67] ?)([0-9]{2} ?){4}$/",
     *     message = "Le numéro de téléphone n'est pas valide."
     * )
     * @Assert\MaxLength(
     *     limit = 15,
     *     message = "Un numéro de téléphone ne peut excéder 15 caractères."
     * )
     */
    private $telephone;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;
	
	    /**
     * Mot de passe hashé du membre.
     * Ce champ contient le mot de passe en clair lors de sa modification par
     * formulaire, avant d'être hashé et remplaçé dans ce champ. Dans la BDD, seul
     * le mot de passe hashé est donc enregistré — et comparé lors de l'authentification.
     *
     * Le mot de passe, requis, est une chaîne de caractère de longueur comprise entre 5 et 100 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "motDePasse", type = "string", length = 100)
     * @Assert\NotBlank(message = "Merci de spécifier un mot de passe.")
     * @Assert\Type(
     *     type = "string",
     *     message = "Le mot de passe doit être une chaîne de caractères."
     * )
     * @Assert\Length(
     *     min = "5",
     *     max = "100",
     *     minMessage = "Le mot de passe doit contenir au moins 5 caractères.",
     *     maxMessage = "Le mot de passe ne peut excéder 100 caractères."
     * )
     */
    private $motDePasse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
	 * @Gedmo\Timestampable(on = "create")
     * @Assert\DateTime()
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetime")
	 * @Gedmo\Timestampable(on = "update")
     * @Assert\DateTime()
     */
    private $dateModification;

    /**
     * @var boolean
     *
     * @ORM\Column(name="referent", type="boolean")
     */
    private $referent;


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
	
	/**
     * Retourne la description d'un membre.
     * La description est le résultat de la concaténation du prénom et du nom.
     *
     * @return string Description du membre.
     */
    public function __toString()
    {

        return $this->getPrenom() . ' ' . $this->getNom();
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
     * @return Professeur
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
     * @return Professeur
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
     * Set mail
     *
     * @param string $mail
     * @return Professeur
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set lycee
     *
     * @param string $lycee
     * @return Professeur
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
     * Set telephone
     *
     * @param string $telephone
     * @return Professeur
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
     * Set roles
     *
     * @param array $roles
     * @return Professeur
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
     * @return Professeur
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
     * @return Professeur
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
     * Set referent
     *
     * @param boolean $referent
     * @return Professeur
     */
    public function setReferent($referent)
    {
        $this->referent = $referent;
    
        return $this;
    }

    /**
     * Get referent
     *
     * @return boolean 
     */
    public function getReferent()
    {
        return $this->referent;
    }
	
	/**
     * Set motDePasse
     *
     * @param string $motDePasse
     * @return Professeur
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
}
