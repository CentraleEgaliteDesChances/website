<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Parent d'un eleve
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\MembreBundle\Entity\ParentEleveRepository")
 * @UniqueEntity(fields="mail", message="Email already taken")
 */
class ParentEleve implements UserInterface, \Serializable
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
     * Numéro de téléphone du membre.
     * Ce champ n'est pas requis mais permet de pouvoir contacter par téléphone
     * (fixe ou portable) un tuteur en cas de besoin. La syntaxe du numéro est vérifiée.
     *
     * @var string
     *
     * @ORM\Column(name = "telephone", type = "string", length = 15, nullable = true)
     * @Assert\Regex(
     *     pattern = "/^((0[1-7] ?)|\+33 ?[67] ?)([0-9]{2} ?){4}$/",
     *     message = "Le numéro de téléphone n'est pas valide."
     * )
     * @Assert\Length(
     *     max = 15,
     *     maxMessage = "Un numéro de téléphone ne peut excéder 15 caractères."
     * )
     */
    private $telephone;



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
     * Booléen enregistrant si le membre choisit de recevoir ou non les mails automatiques de CEC
     *
     *@ORM\Column(name="checkMail", type="boolean")
     */
    private $checkMail = true;


    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="CEC\MembreBundle\Entity\Eleve")

     * @ORM\JoinColumn(nullable=false)
     */

    private $eleves;

    /**
     * ParentEleve constructor.
     */
    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }

    /**
     * ParentEleve constructor.
     */
    

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
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
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
     * Returns the roles granted to the user.
     *
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_PARENT');
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
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return ParentEleve
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return ParentEleve
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return ParentEleve
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     * @return ParentEleve
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * Set motDePasse
     *
     * @param string $motDePasse
     * @return ParentEleve
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
     * @return boolean
     */
    public function getCheckMail()
    {
        return $this->checkMail;
    }

    /**
     * @param boolean $checkMail
     * @return ParentEleve
     */
    public function setCheckMail($checkMail)
    {
        $this->checkMail = $checkMail;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * @param \DateTime $dateModification
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @return Collection
     */
    public function getEleves()
    {
        return $this->eleves;
    }

    /**
     * @param Collection $eleves
     * @return ParentEleve
     */
    public function setEleves($eleves)
    {
        $this->eleves = $eleves;
        return $this;
    }

    /**
     * @param Eleve $eleve
     * @return ParentEleve
     */
    public function addEleve($eleve)
    {
        $this->eleves->add($eleve);
        return $this;
    }


}
