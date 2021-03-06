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
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "Le prénom ne peut excéder 100 caractères."
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
	 * @Assert\NotBlank(message = "Le nom de famille ne peut être vide.")
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "Le nom de famille ne peut excéder 100 caractères."
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
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "L'adresse email ne peut excéder 255 caractères."
     * )
     */
    private $mail;

    /**
     * @var \CEC\TutoratBundle\Entity\Lycee
     *
     * @ORM\ManyToOne(targetEntity="\CEC\TutoratBundle\Entity\Lycee", inversedBy="professeurs")
     */
    private $lycee;

    /**
    * @var string
    *
    * @ORM\Column(name="role", type="string")
    */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name = "telephoneFixe", type = "string", length = 15, nullable = true)
     * @Assert\Regex(
     *     pattern = "/^((0[1-7] ?)|\+33 ?[67] ?)([0-9]{2} ?){4}$/",
     *     message = "Le numéro de téléphone n'est pas valide."
     * )
     * @Assert\Length(
     *     max = 15,
     *     maxMessage = "Un numéro de téléphone ne peut excéder 15 caractères."
     * )
     */
    private $telephoneFixe;

    /**
     * @var string
     *
     * @ORM\Column(name = "telephonePortable", type = "string", length = 15, nullable = true)
     * @Assert\Regex(
     *     pattern = "/^((0[1-7] ?)|\+33 ?[67] ?)([0-9]{2} ?){4}$/",
     *     message = "Le numéro de téléphone n'est pas valide."
     * )
     * @Assert\Length(
     *     max = 15,
     *     maxMessage = "Un numéro de téléphone ne peut excéder 15 caractères."
     * )
     */
    private $telephonePortable;

    /**
     * Identifiant du membre.
     * Il est requis pour la connexion sur le site
     * Lors de la création d'une nouvelle instance, il est fait en srote que l'identifiant soit unique à chaque instance
     *
     * @var string
     *
     * @ORM\Column(name = "username", type = "string", length = 255)
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "L'username ne peut excéder 255 caractères."
     * )
     */
    private $username;

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
     * @var \CEC\TutoratBundle\Entity\Lycee
     *
     * @ORM\ManyToOne(targetEntity="CEC\TutoratBundle\Entity\Lycee", inversedBy="referents")
     */
    private $referent;

    /** 
    * Booléen enregistrant si le membre choisit de recevoir ou non les mails automatiques de CEC
    *
    *@ORM\Column(name="checkMail", type="boolean")
    */
    private $checkMail = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = [];
        $this->setRoles(["ROLE_PROFESSEUR"]);
        $this->setReferent(false);
    }


    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
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
     * @param \CEC\TutotatBundle\Entity\Lycee $lycee
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
     * @return \CEC\TutoratBundle\Entity\Lycee
     */
    public function getLycee()
    {
        return $this->lycee;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Professeur
     */
    public function setRole($role)
    {
        $this->role = $role;
        
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
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
        $this->roles = [];
        foreach($roles as $role)
        {
            $this->addRole($role);
        }
    
        return $this;
    }

    /**
    * Update les roles attribués au professeur
    */
    public function updateRoles()
    {
        $this->setRoles(['ROLE_PROFESSEUR']);

        switch($this->role)
        {
            case "proviseur":
                $this->addRole("ROLE_PROVISEUR");
                break;
            case "proviseurAdjoint":
                $this->addRole("ROLE_PROVISEUR");
                break;
            default:
                break;
        }

        if ($this->referent != null)
            $this->addRole('ROLE_PROFESSEUR_REFERENT');

        return $this;
    }
    /**
    * Remove role
    */
    public function removeRole($role)
    {

        if (in_array($role, $this->roles)) {
            $roles = $this->roles;
            foreach ($roles as $key => $role) {
                if ($role == $oldRole) {
                    unset($roles[$key]);
                }
            }
            $this->roles = $roles;
        }
    }

    /**
    * Add role
    */
    public function addRole($role)
    {

        if(!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

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
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
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
     * @param \CEC\TutoratBundle\Entity\Lycee $lycee
     * @return Professeur
     */
    public function setReferent($lycee)
    {
        $this->referent = $lycee;
    
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
     * Set telephoneFixe
     *
     * @param string $telephoneFixe
     * @return Professeur
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
     * @return Professeur
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
     * Set checkMail
     *
     * @param boolean $checkMail
     * @return Professeur
     */
    public function setCheckMail($checkMail)
    {
        $this->checkMail = $checkMail;
    
        return $this;
    }

    /**
     * Get checkMail
     *
     * @return boolean 
     */
    public function getCheckMail()
    {
        return $this->checkMail;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
}
