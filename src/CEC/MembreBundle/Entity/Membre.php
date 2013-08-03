<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Représente un membre de l'association et donc un utilisateur du site interne.
 * 
 * Un membre possède un droit d'accès au site interne et peut affectuer des modifications
 * pour tout ce qui est accessible (activités, séances, lycéens, compte-rendus, etc.).
 * Lorsqu'un membre est identifié comme membre du buro, il peut accéder aux fonctions
 * avancées (nouvelle année, passations, gestion des membres).
 *
 * Un membre est identifié par son identifiant (prénom + nom), qui doit être unique.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.1
 * 
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CEC\MembreBundle\Entity\MembreRepository")
 * @UniqueEntity(
 *     fields = {"prenom", "nom"},
 *     message = "Un utilisateur possédant ce prénom et ce nom existe déjà."
 * )
 */
class Membre implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * Prénom du membre.
     * Le prénom est requis et ne peut excéder 100 caractères.
     * Il est, associé au nom, unique parmi tous les membres enregistrés.
     *
     * @var string
     *
     * @ORM\Column(name = "prenom", type = "string", length = 100)
     * @Assert\NotBlank(message = "Le prénom ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le prénom ne peut excéder 100 caractères."
     * )
     */
    private $prenom;

    /**
     * Nom de famille du membre.
     * Le nom de famille est requis et ne peut excéder 100 caractères.
     * Il est, associé au prénom, unique parmi tous les membres enregistrés.
     *
     * @var string
     *
     * @ORM\Column(name = "nom", type = "string", length = 100)
     * @Assert\NotBlank(message = "Le nom de famille ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le nom de famille ne peut excéder 100 caractères."
     * )
     */
    private $nom;

    /**
     * Adresse email du membre.
     * L'adresse email est nécessaire pour contacter le membre et permettre
     * l'envoi automatique de messages de rappel lors des séances par exemple.
     * Elle est requise et ne peut excéder 255 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "email", type = "string", length = 255)
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
    private $email;

    /**
     * Numéro de téléphone du membre.
     * Ce champ n'est pas requis mais permet de pouvoir contacter par téléphone
     * (fixe ou portable) un tuteur en cas de besoin. La syntaxe du numéro est vérifiée.
     *
     * @var string
     *
     * @ORM\Column(name = "telephone", type = "string", length = 15)
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
     * Mot de passe hashé du membre.
     * Ce champ contient le mot de passe en clair lors de sa modification par
     * formulaire, avant d'être hashé et remplaçé dans ce champ. Dans la BDD, seul
     * le mot de passe hashé est donc enregistré — et comparé lors de l'authentification.
     *
     * Le mot de passe, requis, est une chaîne de caractère de longueur comprise entre 5 et 50 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "motDePasse", type = "string", length = 255)
     * @Assert\NotBlank(message = "Merci de spécifier un mot de passe.")
     * @Assert\Type(
     *     type = "string",
     *     message = "Le mot de passe doit être une chaîne de caractères."
     * )
     * @Assert\Length(
     *     min = "5",
     *     max = "50",
     *     minMessage = "Le mot de passe doit contenir au moins 5 caractères.",
     *     maxMessage = "Le mot de passe ne peut excéder 50 caractères."
     * )
     */
    private $motDePasse;

    /**
     * Année de la promotion du membre.
     * Ce champ permet de spécifier la promotion de l'Ecole Centrale du membre, afin de le
     * retrouver plus rapidement si nécessaire. Il est donc requis, et doit se composer de 
     * l'année d'obtention du diplôme uniquement (p2014 donc 2014).
     *
     * @var integer
     *
     * @ORM\Column(name = "promotion", type = "integer")
     * @Assert\NotBlank(message = "La promotion ne peut être vide.")
     * @Assert\Range(
     *     min = 1990,
     *     max = 9999,
     *     minMessage = "Votre année de promotion doit être supérieure à 1990.",
     *     maxMessage = "Votre année de promotion doit être inférieure à 9999."
     * )
     */
    private $promotion;
    
    /**
     * Le membre est-il au buro de l'association ?
     * Les membres du buro peuvent accéder aux fonctions avancées (nouvelle année, passations,
     * et gestion des membres).
     *
     * @var boolean
     *
     * @ORM\Column(name = "buro", type = "boolean")
     * @Assert\Type(
     *     type = "boolean",
     *     message = "Merci d'indiquer de manière valide s'il s'agit d'un membre du Buro de l'association."
     * )
     */
    private $buro;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateCreation", type = "datetime")
     * @Gedmo\Timestampable(on = "create")
     * @Assert\DateTime()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     * 
     * @var \DateTime
     *
     * @ORM\Column(name = "dateModification", type = "datetime")
     * @Gedmo\Timestampable(on = "update")
     * @Assert\DateTime()
     */
    private $dateModification;

    /**
     * Lycée pour lequel le membre est VP Lycée.
     * Ce champ pour rester null si le membre n'est pas VP Lycée.
     * 
     * @var CEC\TutoratBundle\Entity\Lycee
     *
     * @ORM\ManyToOne(targetEntity = "CEC\TutoratBundle\Entity\Lycee", inversedBy = "vpLycees" )
     */
    private $vpLycee;

    /**
     * Groupe de tutorat fréquenté régulièrement par le membre.
     * Ce champ permet de définir le groupe de tutorat du membre ; in fine, cela lui permet d'accéder
     * au menu de tutorat avec les informations sur son groupe, soon/ses lycée(s), les prochaine séances,
     * le choix d'activité et la rédaction de compte-rendus. 
     * Un tuteur appartenant à un groupe est considéré comme "actif" pour l'activité de tutorat. Il sera
     * comptabilisé dans les statistiques et recevra — si disponible — les notifications associées.
     * 
     * @var CEC\TutoratBundle\Entity\Groupe
     *
     * @ORM\ManyToOne(targetEntity = "CEC\TutoratBundle\Entity\Groupe", inversedBy = "tuteurs" )
     */
    private $groupe;

    /**
     * Groupe de tutorat fréquenté régulièrement par le membre.
     * Ce champ permet de définir le groupe de tutorat du membre ; in fine, cela lui permet d'accéder
     * au menu de tutorat avec les informations sur son groupe, soon/ses lycée(s), les prochaine séances,
     * le choix d'activité et la rédaction de compte-rendus. 
     * Un tuteur appartenant à un groupe est considéré comme "actif" pour l'activité de tutorat. Il sera
     * comptabilisé dans les statistiques et recevra — si disponible — les notifications associées.
     * 
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity = "Secteur", inversedBy = "membres" )
     */
    private $secteurs;

    /**
     * Séances auxquelles le tuteur a participé.
     * Ce champ liste les séances de tutorat pour lesquelles le membre a indiqué qu'il participait.
     * Ceci sert principalement deux objectifs :
     *     - élaborer des statistiques avancées sur l'encadrement et le taux d'activité des tuteurs ;
     *     - faciliter l'organisation des séances en sachant le nombre de tuteurs disponibles.
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes de Seance pour
     * ajouter un tuteur à une séance.
     * 
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity = "CEC\TutoratBundle\Entity\Seance", mappedBy = "tuteurs" )
     */
    private $seances;
        
    /**
     * Divers documents (versions d'une activite) téléchargés par le membre.
     * Lorsque l'on télécharge une version d'une activité, l'identité de l'auteur est conservée.
     *
     * ATTENTION : la suppression du membre entraîne la suppression des documents associés à l'auteur,
     *             et ceci pour des raisons de sécurité.
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes de Seance pour
     * ajouter un tuteur à une séance.
     * 
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity = "CEC\ActiviteBundle\Entity\Document", mappedBy = "auteur", cascade = {"remove"} )
     */
    private $documents;

    /**
     * Compte-rendus rédigés par un membre.
     * L'auteur d'un compte-rendu est associé à celui-ci au moment de la rédaction du compte-rendu (et non
     * au moment de sa création, c'est-à-dire lorsque l'on ajoute une activité à une séance).
     *
     * ATTENTION : la suppression du membre entraîne la suppression des compte-rendus associés à l'auteur,
     *             et ceci pour des raisons de sécurité.
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes de Seance pour
     * ajouter un tuteur à une séance.
     * 
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity = "CEC\ActiviteBundle\Entity\CompteRendu", mappedBy = "auteur", cascade = {"remove"} )
     */
    private $compteRendus;
        

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->secteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->compteRendus = new \Doctrine\Common\Collections\ArrayCollection();
        
        // Valeurs par défaut
        $this->setPromotion(date('Y') + 3);
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
    
    
    //
    // Doctrine-generated accessors
    //
    

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
     * Add documents
     *
     * @param \CEC\ActiviteBundle\Entity\Document $documents
     * @return Membre
     */
    public function addDocument(\CEC\ActiviteBundle\Entity\Document $documents)
    {
        $this->documents[] = $documents;
    
        return $this;
    }

    /**
     * Remove documents
     *
     * @param \CEC\ActiviteBundle\Entity\Document $documents
     */
    public function removeDocument(\CEC\ActiviteBundle\Entity\Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add compteRendus
     *
     * @param \CEC\ActiviteBundle\Entity\CompteRendu $compteRendus
     * @return Membre
     */
    public function addCompteRendu(\CEC\ActiviteBundle\Entity\CompteRendu $compteRendus)
    {
        $this->compteRendus[] = $compteRendus;
    
        return $this;
    }

    /**
     * Remove compteRendus
     *
     * @param \CEC\ActiviteBundle\Entity\CompteRendu $compteRendus
     */
    public function removeCompteRendu(\CEC\ActiviteBundle\Entity\CompteRendu $compteRendus)
    {
        $this->compteRendus->removeElement($compteRendus);
    }

    /**
     * Get compteRendus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompteRendus()
    {
        return $this->compteRendus;
    }
}