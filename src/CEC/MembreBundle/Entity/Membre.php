<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

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
 * @version 1.2
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CEC\MembreBundle\Entity\MembreRepository")
 * @UniqueEntity(fields="mail", message="Email already taken")
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
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "Le prénom ne peut excéder 100 caractères."
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
	 * Tableau des roles attribués au membre
	 *
	 * @var array
	 * 
	 * @ORM\Column(name="roles", type="array")
	 */
	 
	 protected $roles;

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
     * Lycées pour lesquels le membre est VP Lycée.
     * Ce champ pour rester null si le membre n'est pas VP Lycée.
     *
     * @var CEC\TutoratBundle\Entity\Lycee
     *
     * @ORM\ManyToMany(targetEntity = "CEC\TutoratBundle\Entity\Lycee", mappedBy = "vpLycees" )
     */
    private $lyceesPourVP;

    /**
     * Groupe de tutorat fréquenté régulièrement par le membre.
     * Ce champ permet de garder en mémoire quels groupes a fréquenté le tuteur suivant les années scolaires
     *
     * @var CEC\TutoratBundle\Entity\GroupeTuteurs
     *
     * @ORM\OneToMany(targetEntity = "CEC\TutoratBundle\Entity\GroupeTuteurs", mappedBy = "tuteur", cascade={"persist", "remove"}, orphanRemoval=true )
     */
    private $groupeParAnnee;

    /**
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
	* @var \Doctrine\Common\Collections\Collection
	*
	*@ORM\ManyToMany(targetEntity="CEC\SecteurProjetsBundle\Entity\Projet", mappedBy="contacts")
	*/
	private $contactProjets;

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
     * Les quizz actus téléchargés par le membre.
     * Lorsque l'on télécharge un quizz actu, l'identité de l'auteur est conservée.
     *
     * ATTENTION : la suppression du membre entraîne la suppression des documents associés à l'auteur,
     *             et ceci pour des raisons de sécurité.
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity = "CEC\ActiviteBundle\Entity\QuizzActu", mappedBy = "auteur", cascade = {"remove"} )
     */
    private $quizzActus;

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
        $this->secteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->quizzActus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->compteRendus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = [];
        $this->groupeParAnnee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contactProjets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lyceesPourVP = new \Doctrine\Common\Collections\ArrayCollection();

        // Valeurs par défaut
        $this->setPromotion(date('Y') + 3);
        $this->setBuro(false);
        $this->setRoles(['ROLE_TUTEUR']);
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
     * On attribue à tous le rôle d'utilisateur ("ROLE_USER"). Les rôles sont ensuite
     * attribués suivant les secteurs, puis suivant si le membre appartient au buro ou non ("ROLE_BURO").
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {

        $this->roles = [];
        foreach($roles as $role)
        {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
    * Fonction qui met à jour la liste des roles du membre. Appelée après chaque insert/update
    * A appeler dès qu'on modifie des roles dans un controleur.
    */
    public function updateRoles()
    {
        $this->setRoles(array('ROLE_TUTEUR'));

        // Le rôle buro est supérieur hiérarchique de tous les rôles donc, s'il est attribué, pas la peine de faire le reste.
        if ($this->buro)
        {
            $this->addRole('ROLE_BURO');
            return $this;
        }


        $secteurs = $this->secteurs;
        foreach($secteurs as $secteur)
        {
            $nom = $secteur->getNom();
            switch($nom)
            {
                case "Secteur Activités Scientifiques":
                    $this->addRole("ROLE_SECTEUR_ACTIS_SCIENTIFIQUES");
                    break;
                case "Secteur Activités Culturelles":
                    $this->addRole('ROLE_SECTEUR_ACTIS_CULTURELLES');
                    break;
                case "Secteur Fundraising":
                    $this->addRole("ROLE_SECTEUR_FUNDRAISING");
                    break;
                case "Secteur Evènements":
                    $this->addRole("ROLE_SECTEUR_EVCOM");
                    break;
                case "Secteur Good Morning London":
                    $this->addRole("ROLE_SECTEUR_GML");
                    $this->addRole("ROLE_SECTEUR_PROJETS");
                    break;
                case "Secteur Centrale Prépa":
                    $this->addRole("ROLE_SECTEUR_PREPA");
                    $this->addRole("ROLE_SECTEUR_PROJETS");
                    break;
                case "Secteur Focus Europe":
                    $this->addRole("ROLE_SECTEUR_FOCUS_EUROPE");
                    $this->addRole("ROLE_SECTEUR_PROJETS");
                    break;
                case "Secteur Stage Théâtre":
                    $this->addRole("ROLE_SECTEUR_THEATRE");
                    $this->addRole("ROLE_SECTEUR_PROJETS");
                    break;
                case "Secteur (Art)cessible":
                    $this->addRole("ROLE_SECTEUR_ARTCESSIBLE");
                    $this->addRole("ROLE_SECTEUR_PROJETS");
                    break;
                case "Secteur Geek":
                    $this->addRole("ROLE_SECTEUR_GEEK");
                    break;
                case "Secteur Saclay":
                    $this->addRole("ROLE_SECTEUR_SACLAY");
                    break;
                case "Secteur Europen":
                    $this->addRole("ROLE_SECTEUR_EUROPEN");
                    break;
                case "Secteur Sorties":
                    $this->addRole("ROLE_SECTEUR_SORTIES");
                    break;
                default:
                    break;
            }
        }

        if(count($this->lyceesPourVP) > 0)
            $this->addRole('ROLE_VP_LYCEE');

        return $this;

    }

    /**
    * Remove role
    */
    public function removeRole($oldRole)
    {
        if (in_array($oldRole, $this->roles)) {
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
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
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
     * Set buro
     *
     * @param boolean $buro
     * @return Membre
     */
    public function setBuro($buro)
    {
        $this->buro = $buro;

        if ($buro) 
            $this->addRole("ROLE_BURO");
        else
            $this->removeRole("ROLE_BURO");

        return $this;
    }

    /**
     * Get buro
     *
     * @return boolean
     */
    public function getBuro()
    {
        return $this->buro;
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
     * Add lyceesPourVP
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lyceesPourVP
     * @return Membre
     */
    public function addLyceesPourVP(\CEC\TutoratBundle\Entity\Lycee $lyceesPourVP)
    {
        $this->lyceesPourVP[] = $lyceesPourVP;
        $this->addRole('ROLE_VP_LYCEE');

        return $this;
    }

    /**
     * Remove lyceesPourVP
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lyceesPourVP
     */
    public function removeLyceesPourVP(\CEC\TutoratBundle\Entity\Lycee $lyceesPourVP)
    {
        $this->lyceesPourVP->removeElement($lyceesPourVP);
        if (count($this->lyceesPourVP)==0)
            $this->removeRole('ROLE_VP_LYCEE');

        return $this;
    }

    /**
     * Get lyceesPourVP
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLyceesPourVP()
    {
        return $this->lyceesPourVP->toArray();
    }

    /**
     * Add secteurs
     *
     * @param \CEC\MembreBundle\Entity\Secteur $secteurs
     * @return Membre
     */
    public function addSecteur(\CEC\MembreBundle\Entity\Secteur $secteur)
    {
        $this->secteurs[] = $secteur;
        
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

        return $this;
    }

    /**
     * Get secteurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecteurs()
    {
        return $this->secteurs->toArray();
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

        return $this;
    }

    /**
     * Get seances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeances()
    {
        return $this->seances->toArray();
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

        return $this;
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents->toArray();
    }
	
	/**
     * Add contact_projets
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Projet $projet
     * @return Membre
     */
    public function addContactProjet(\CEC\SecteurProjetsBundle\Entity\Projet $projet)
    {
        $this->contactProjets[] = $projet;

        return $this;
    }

    /**
     * Remove contact_projets
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Projet $projet
     */
    public function removeContactProjet(\CEC\SecteurProjetsBundle\Entity\Projet $projet)
    {
        $this->contactProjets->removeElement($projet);

        return $this;
    }

    /**
     * Get contact_projets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactProjets()
    {
        return $this->contactProjets->toArray();
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

        return $this;
    }

    /**
     * Get compteRendus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompteRendus()
    {
        return $this->compteRendus->toArray();
    }

    /**
     * Add quizzActus
     *
     * @param \CEC\ActiviteBundle\Entity\QuizzActu $quizzActus
     * @return Membre
     */
    public function addQuizzActu(\CEC\ActiviteBundle\Entity\QuizzActu $quizzActus)
    {
        $this->quizzActus[] = $quizzActus;

        return $this;
    }

    /**
     * Remove quizzActus
     *
     * @param \CEC\ActiviteBundle\Entity\QuizzActu $quizzActus
     */
    public function removeQuizzActu(\CEC\ActiviteBundle\Entity\QuizzActu $quizzActus)
    {
        $this->quizzActus->removeElement($quizzActus);
    }

    /**
     * Get quizzActus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizzActus()
    {
        return $this->quizzActus->toArray();
    }


    /**
     * Add groupeParAnnee
     *
     * @param \CEC\TutoratBundle\Entity\GroupeTuteurs $groupeParAnnee
     * @return Membre
     */
    public function addGroupeParAnnee(\CEC\TutoratBundle\Entity\GroupeTuteurs $groupeParAnnee)
    {
        $this->groupeParAnnee[] = $groupeParAnnee;
    
        return $this;
    }

    /**
     * Remove groupeParAnnee
     *
     * @param \CEC\TutoratBundle\Entity\GroupeTuteurs $groupeParAnnee
     */
    public function removeGroupeParAnnee(\CEC\TutoratBundle\Entity\GroupeTuteurs $groupeParAnnee)
    {
        $this->groupeParAnnee->removeElement($groupeParAnnee);
    }

    /**
     * Get groupeParAnnee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupeParAnnee()
    {
        return $this->groupeParAnnee->toArray();
    }

    public function getGroupe()
    {
        $e = $this->groupeParAnnee->first();
        if(!$e) return null;
        
        do
        {
            if($e->getAnneeScolaire() == AnneeScolaire::withDate())
            {
                return $e->getGroupe();
            }
        }while($e = $this->groupeParAnnee->next());
        return null;
    }

    /**
     * Set checkMail
     *
     * @param boolean $checkMail
     * @return Membre
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
        
        return $this;
    }

    /**
     * Permet d'avoir un string des roles du membres avec une bonne syntaxe
     *
     * @return string
     */
    public function getRolesBienEcrit() {
        $result = "Tuteur";
        if (count($this->roles) > 1) {
            foreach ($this->roles as $role) {
                if ($role != "ROLE_TUTEUR") {
                    switch ($role) {
                    case "ROLE_VP_LYCEE":
                        $result = $result.", "."VP Lycée";
                            break;
                    case "ROLE_SECTEUR_ACTIS_SCIENTIFIQUES":
                        $result = $result.", "."Acti Sci";
                            break;
                    case "ROLE_SECTEUR_ACTIS_CULTURELLES" :
                        $result = $result.", "."ActiKu";
                        break;
                    case "ROLE_SECTEUR_EVCOM":
                        $result = $result.", "."Ev-Com";
                        break;
                    case "ROLE_SECTEUR_GML":
                        $result = $result.", "."GML";
                        break;
                    case "ROLE_SECTEUR_PREPA":
                        $result = $result.", "."Centrale Prépa";
                        break;
                    case "ROLE_SECTEUR_FOCUS_EUROPE":
                        $result = $result.", "."Focus Europe";
                        break;
                    case "ROLE_SECTEUR_THEATRE":
                        $result = $result.", "."Stage Théatre";
                        break;
                    case "ROLE_SECTEUR_ARTCESSIBLE":
                        $result = $result.", "."(Art)ccessible";
                        break;
                    case "ROLE_SECTEUR_GEEK":
                        $result = $result.", "."Geek";
                        break;
                    case "ROLE_SECTEUR_SACLAY":
                        $result = $result.", "."Saclay";
                        break;
                    case "ROLE_SECTEUR_EUROPEN":
                        $result = $result.", "."Europen";
                        break;
                    case "ROLE_SECTEUR_SORTIES":
                        $result = $result.", "."Sorties";
                        break;
                    case "ROLE_BURO":
                        $result = $result.", "."Buro";
                        break;
                    }
                }
            }
        }
        return $result;
    }
}
