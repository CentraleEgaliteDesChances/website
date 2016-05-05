<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * Eleve
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\MembreBundle\Entity\EleveRepository")
 * @UniqueEntity(fields="mail", message="Email already taken")
 */
class Eleve implements UserInterface, \Serializable
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
     * @var boolean
     *
     * @ORM\Column(name="telephonePublic", type="boolean")
     */
    private $telephonePublic = false;

    /**
     * @var string
     *
     * @ORM\COlumn(name="adresse", type="string")
     */
    private $adresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="code_postal", type="integer")
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string")
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="nomPere", type="string", nullable=true)
     */
    private $nomPere;

    /**
     * Numéro de téléphone du membre.
     * Ce champ n'est pas requis mais permet de pouvoir contacter par téléphone
     * (fixe ou portable) un tuteur en cas de besoin. La syntaxe du numéro est vérifiée.
     *
     * @var string
     *
     * @ORM\Column(name = "telephoneParent", type = "string", length = 15, nullable = true)
     * @Assert\Regex(
     *     pattern = "/^((0[1-7] ?)|\+33 ?[67] ?)([0-9]{2} ?){4}$/",
     *     message = "Le numéro de téléphone n'est pas valide."
     * )
     * @Assert\Length(
     *     max = 15,
     *     maxMessage = "Un numéro de téléphone ne peut excéder 15 caractères."
     * )
     */
    private $telephoneParent;

    /**
     * @var string
     *
     * @ORM\Column(name="nomMere", type="string", nullable=true)
     */
    private $nomMere;

    /**
     * @var \Date
     *
     * @ORM\Column(name="datenaiss", type="date")
     */
    private $datenaiss;

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
     * @ORM\ManyToOne(targetEntity="\CEC\TutoratBundle\Entity\Lycee", inversedBy="delegues")
     */
    private $delegue;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\CEC\SecteurProjetsBundle\Entity\Reunion", mappedBy="presents")
     */
    private $reunions;

    /**
     * Groupe de tutorat fréquenté régulièrement par le membre.
     * Ce champ permet de définir le groupe de tutorat de l'élève en fonction de l'année scolaire.
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity = "CEC\TutoratBundle\Entity\GroupeEleves", mappedBy = "lyceen",cascade={"persist", "remove"}, orphanRemoval=true )
     */
    private $groupeParAnnee;

    /**
     * Lycée de l'élève
     * @ORM\ManyToOne(targetEntity="\CEC\TutoratBundle\Entity\Lycee", inversedBy="lyceens")
     */
    private $lycee;

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
     * @ORM\ManyToMany(targetEntity = "CEC\TutoratBundle\Entity\Seance", mappedBy = "lyceens" )
     */
    private $seances;

    /**
     *
     * Répertorie les sorties auxquelles s'est inscrit le lycéen.
     * ATTENTION : la suppression du lycéen supprime ses inscriptions.
     *
     * Il ne s'agit pas du coté propriétaire. Utiliser les méthodes de SortieEleve pour ajouter un lycéen à une sortie.
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\CEC\SecteurSortiesBundle\Entity\SortieEleve", mappedBy="lyceen")
     */
    private $sorties;

    /**
     *@var \Doctrine\Common\Collections\Collection
     *
     *@ORM\OneToMany(targetEntity="\CEC\SecteurProjetsBundle\Entity\ProjetEleve", mappedBy="lyceen")
     */
    private $projetsParAnnee;

    /**
     * Booléen enregistrant si le membre choisit de recevoir ou non les mails automatiques de CEC
     *
     *@ORM\Column(name="checkMail", type="boolean")
     */
    private $checkMail = true;

    /**
     * Booléen enregistrant si l'élève a bien rendu sa charte élève
     * @var boolean
     *
     * @ORM\COlumn(name="charte_eleve_rendu", type="boolean")
     */
    private $charteEleveRendue;

    /**
     * Booléen enregistrant si l'élève a bien rendu son autorisation parentale
     * @var boolean
     *
     * @ORM\COlumn(name="autorisation_parentale_rendue", type="boolean")
     */
    private $autorisationParentaleRendue;

    /**
     * Booléen enregistrant si l'élève a bien rendu son droit à l'image
     * @var boolean
     *
     * @ORM\COlumn(name="droit_image_rendue", type="boolean")
     */
    private $droitImageRendue;

    /**
     * Niveau de l'élève (Secondes, Premières ou Terminales)
     * Il est représenté par un string et on le modifie toujours de sorte à ce qu'il soit égal à (Secondes|Premières|Terminales)
     *
     *
     * @var string
     *
     * @ORM\Column(name = "niveau", type = "string", length = 11)
     * @Assert\NotBlank(message = "Merci de spécifier votre niveau de scolarité.")
     * @Assert\Regex(
     *     pattern="/Seconde|Première|Terminale/",
     *     match=true,
     *     message="Merci de spécifier votre niveau de scolarité "
     *     )
     */
    private $niveau;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sorties = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reunions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = [];
        $this->groupeParAnnee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setRoles(["ROLE_ELEVE"]);
        $this->projetsParAnnee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->charteEleveRendue = false;
        $this->autorisationParentaleRendue = false;
        $this->droitImageRendue = false;
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
     * Set mail
     *
     * @param string $mail
     * @return Eleve
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
     *
     * Vérifier si le telephone est public
     * @return boolean*/
    public function getTelephonePublic()
    {
        return $this->telephonePublic;
    }

    /**
     *
     * Change l'état du téléphone
     * @return boolean*/
    public function setTelephonePublic($boolean)
    {
        $this->telephonePublic = $boolean;
        return $this;
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

        $this->roles = [];
        foreach($roles as $role)
        {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * Update les roles donnés à l'utilisateur
     */
    public function updateRoles()
    {
        $this->setRoles(['ROLE_ELEVE']);

        if($this->delegue)
            $this->addRole('ROLE_ELEVE_DELEGUE');

        return $this;
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
     * Remove role
     */
    public function removeRole($role)
    {

        if (in_array($role, $this->roles)) {
            $roles = $this->roles;
            foreach ($roles as $key => $oldRole) {
                if ($role == $oldRole) {
                    unset($roles[$key]);
                }
            }
            $this->roles = $roles;
        }

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
     * Set delegue
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $delegue
     * @return Eleve
     */
    public function setDelegue($delegue)
    {
        $this->delegue = $delegue;

        return $this;
    }

    /**
     * Get delegue
     *
     * @return \CEC\TutoratBundle\Entity\Lycee
     */
    public function getDelegue()
    {
        return $this->delegue;
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
     * Set reunions
     *
     * @param array $reunions
     * @return Eleve
     */
    public function setReunions($reunions)
    {
        $this->reunions = $reunions;

        return $this;
    }

    /**
     * Add reunions
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Reunion $reunion
     * @return Eleve
     */
    public function addReunion( \CEC\SecteurProjetsBundle\Entity\Reunion $reunion)
    {
        $this->reunions[] = $reunion;
        return $this;
    }

    /**
     * Remove reunion
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Reunion $reunion
     */
    public function removeReunion(\CEC\SecteurProjetsBundle\Entity\Reunion $reunion)
    {
        $this->secteurs->removeElement($reunion);
    }

    /**
     * Get reunions
     *
     * @return array
     */
    public function getReunions()
    {
        return $this->reunions->toArray();
    }



    /**
     * Add projetsParAnnee
     *
     * @param \CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee
     * @return Eleve
     */
    public function addProjetsParAnnee(\CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee)
    {
        $this->projetsParAnnee[] = $projetsParAnnee;

        return $this;
    }

    /**
     * Remove projetsParAnnee
     *
     * @param \CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee
     */
    public function removeProjetsParAnnee(\CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee)
    {
        $this->projetsParAnnee->removeElement($projetsParAnnee);
    }

    /**
     * Get projetsParAnnee
     *
     * @return array
     */
    public function getProjetsParAnnee()
    {
        return $this->projetsParAnnee->toArray();
    }

    /**
     * Add sorties
     *
     * @param \CEC\SecteurSortiesBundle\Entity\Sortie $sortie
     * @return Membre
     */
    public function addSortie(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
    {
        $this->sorties[] = $sortie;

        return $this;
    }


    /**
     * Remove sorties
     *
     * @param \CEC\SecteurSortiesBundle\Entity\Sortie $sortie
     */
    public function removeSortie(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
    {
        $this->sorties->removeElement($sortie);
    }

    /**
     * Get sorties
     *
     * @return array
     */
    public function getSorties()
    {
        return $this->sorties->toArray();
    }


    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Eleve
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
     * @return Eleve
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
     * @return Eleve
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
     * Add seances
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seances
     * @return Eleve
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
     * @return array
     */
    public function getSeances()
    {
        return $this->seances->toArray();
    }

    /**
     * Set nomPere
     *
     * @param string $nomPere
     * @return Eleve
     */
    public function setNomPere($nomPere)
    {
        $this->nomPere = $nomPere;

        return $this;
    }

    /**
     * Get nomPere
     *
     * @return string
     */
    public function getNomPere()
    {
        return $this->nomPere;
    }

    /**
     * Set nomMere
     *
     * @param string $nomMere
     * @return Eleve
     */
    public function setNomMere($nomMere)
    {
        $this->nomMere = $nomMere;

        return $this;
    }

    /**
     * Get nomMere
     *
     * @return string
     */
    public function getNomMere()
    {
        return $this->nomMere;
    }

    /**
     * Set telephoneParent
     *
     * @param string $telephoneParent
     * @return Eleve
     */
    public function setTelephoneParent($telephoneParent)
    {
        $this->telephoneParent = $telephoneParent;

        return $this;
    }

    /**
     * Get telephoneParent
     *
     * @return string
     */
    public function getTelephoneParent()
    {
        return $this->telephoneParent;
    }

    /**
     * Set lycee
     *
     * @param \CEC\TutoratBundle\Entity\Lycee $lycee
     * @return Eleve
     */
    public function setLycee(\CEC\TutoratBundle\Entity\Lycee $lycee = null)
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
     * Add groupeParAnnee
     *
     * @param \CEC\TutoratBundle\Entity\GroupeEleves $groupeParAnnee
     * @return Eleve
     */
    public function addGroupeParAnnee(\CEC\TutoratBundle\Entity\GroupeEleves $groupeParAnnee)
    {
        $this->groupeParAnnee[] = $groupeParAnnee;

        return $this;
    }

    /**
     * Remove groupeParAnnee
     *
     * @return array
     * @param \CEC\TutoratBundle\Entity\GroupeEleves $groupeParAnnee
     */
    public function removeGroupeParAnnee(\CEC\TutoratBundle\Entity\GroupeEleves $groupeParAnnee)
    {
        $this->groupeParAnnee->removeElement($groupeParAnnee);

        return $this;
    }

    /**
     * Get groupeParAnnee
     *
     * @return array
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
     * @return Eleve
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

    /**
     * @return boolean
     */
    public function isCharteEleveRendue()
    {
        return $this->charteEleveRendue;
    }

    /**
     * @param boolean $charteEleveRendue
     */
    public function setCharteEleveRendue($charteEleveRendue)
    {
        $this->charteEleveRendue = $charteEleveRendue;
    }

    /**
     * @return boolean
     */
    public function isAutorisationParentaleRendue()
    {
        return $this->autorisationParentaleRendue;
    }

    /**
     * @param boolean $autorisationParentaleRendue
     */
    public function setAutorisationParentaleRendue($autorisationParentaleRendue)
    {
        $this->autorisationParentaleRendue = $autorisationParentaleRendue;
    }

    /**
     * @return boolean
     */
    public function isDroitImageRendue()
    {
        return $this->droitImageRendue;
    }

    /**
     * @param boolean $droitImageRendue
     */
    public function setDroitImageRendue($droitImageRendue)
    {
        $this->droitImageRendue = $droitImageRendue;
    }

    /**
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @return Eleve
     * @param string $niveau
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
        return $this;
    }

}